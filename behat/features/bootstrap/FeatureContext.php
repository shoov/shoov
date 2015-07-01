<?php

use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

class FeatureContext extends DrupalContext implements SnippetAcceptingContext {

  /**
   * @When /^I login with user "([^"]*)"$/
   */
  public function iLoginWithUser($name) {
    $password = $name == 'admin' ? 'admin' : '1234';
    $this->loginUser($name, $password);
  }

  /**
   * Login a user to the site.
   *
   * @param $name
   *   The user name.
   * @param $password
   *   The use password.
   */
  protected function loginUser($name, $password) {
    $this->user = new stdClass();
    $this->user->name = $name;
    $this->user->pass = $password;
    $this->login();
  }

  /**
   * @When /^I login with bad credentials$/
   */
  public function iLoginWithBadCredentials() {
    return $this->loginUser('wrong-foo', 'wrong-bar');
  }

  /**
   * @When /^I visit "([^"]*)" node of type "([^"]*)"$/
   */
  public function iVisitNodePageOfType($title, $type) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($type))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => $type,
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $nid = key($result['node']);
    $this->getSession()->visit($this->locatePath('node/' . $nid));
  }

  /**
   * @when I visit repository :title
   */
  public function iVisitRepository($title) {
    $this->iVisitNodePageOfType($title, 'repository');
  }

  /**
   * @when I visit CI build :title
   */
  public function iVisitCIBuild($title) {
    $this->iVisitNodePageOfType($title, 'ci_build');
  }

  /**
   * @Then I should have access to the page
   */
  public function iShouldHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('200');
  }

  /**
   * @Then I should not have access to the page
   */
  public function iShouldNotHaveAccessToThePage() {
    $this->assertSession()->statusCodeEquals('403');
  }

  /**
   * @When I should be able to edit :title node of type :type
   */
  public function iShouldBeAbleToEditNodeOfType($title, $type) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($type))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => $type,
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $nid = key($result['node']);
    if (node_access('update', node_load($nid), user_load_by_name($this->user->name))) {
      return;
    }
    $params = array(
      '@title' => $title,
      '@type' => $type,
    );
    throw new \Exception(format_string("You can't edit the node @title of @type not found.", $params));
  }

  /**
   * @When I should be able to create node of type :type
   */
  public function iShouldBeAbleToCreateNodeOfType($type) {
    if (node_access('create', $type, user_load_by_name($this->user->name))) {
      return;
    }
    throw new \Exception(format_string("User @user can't create node of @type", array('@type' => $type, '@user' => $this->user->name)));
  }

  /**
   * @When I should not be able to create node of type :type
   */
  public function iShouldNotBeAbleToCreateNodeOfType($type) {
    if (node_access('create', $type, user_load_by_name($this->user->name))) {
      throw new \Exception(format_string("User @user can create node of @type", array('@type' => $type, '@user' => $this->user->name)));
    }
  }

  /**
   * @When I create message of type :type
   */
  public function iCreateMessageOfType($type, $options = NULL) {
    $account = user_load_by_name($this->user->name);

    $message = message_create($type, array('uid' => $account->uid));
    $wrapper = entity_metadata_wrapper('message', $message);

    if ($type == 'ci_build') {
      $wrapper->field_ci_build->set($options->ci_build_id);
      $wrapper->field_ci_build_status->set($options->status);
      $wrapper->field_ci_build_timestamp->set(time());
    }

    $wrapper->save();
  }

  /**
   * @when I create CI build item with status :status for CI build :ci_build
   */
  public function iCreateCIBuildItemWithStatusForCIBuild($status, $ci_build) {
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'ci_build')
      ->propertyCondition('title', $ci_build)
      ->range(0,1)
      ->execute();

    $ci_build_id = array_keys($entity['node'])[0];

    $options = [
      'status' => $status,
      'ci_build' => $ci_build_id
    ];
    $this->iCreateMessageOfType('ci_build', $options);
  }

  /**
   * @Then CI build :ci_build should have status :status
   */
  public function CIBuildShouldHaveStatus($ci_build, $status) {
    $status = ($status == 'OK') ? NULL : strtolower($status);

    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'ci_build')
      ->propertyCondition('title', $ci_build)
      ->range(0, 1)
      ->execute();

    $node = node_load(array_keys($entity['node'])[0]);
    $wrapper = entity_metadata_wrapper('node', $node);

    $ci_build_status = $wrapper->field_ci_build_incident_status->value();
    if ($ci_build_status != $status) {
      $params = [
        '@title' => $ci_build,
        '@ci_build_status' => $ci_build_status,
        '@status' => $status
      ];
      throw new \Exception(format_string("CI build @title have status @ci_build_status instead of @status", $params));
    }
  }

  /**
   * @Then CI build "William/app" should have failed count equal to :number
   */
  public function CIBuildShouldHaveFailedCountEqualTo($ci_build, $number) {
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'ci_build')
      ->propertyCondition('title', $ci_build)
      ->range(0, 1)
      ->execute();

    $node = node_load(array_keys($entity['node'])[0]);
    $wrapper = entity_metadata_wrapper('node', $node);

    $failed_count = $wrapper->field_ci_build_failed_count->value();
    if ($failed_count != $number) {
      $params = [
        '@ci_build' => $ci_build,
        '@failed_count' => $failed_count,
        '@number' => $number
      ];
      throw new \Exception(format_string("CI build @ci_build have failed count @failed_count instead of @number", $params));
    }
  }

  /**
   * @When I create :title node of type :type
   */
  public function iCreateNodeOfType($title, $type, $repository = NULL) {
    $account = user_load_by_name($this->user->name);
    $values = array(
      'type' => $type,
      'uid' => $account->uid,
    );
    $entity = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $entity);
    $wrapper->title->set($title);
    if ($type == 'repository') {
      $wrapper->field_github_id->set(123456);
    }
    elseif ($type == 'ci_build') {
      if (!$repository) {
        return;
      }

      $query = new EntityFieldQuery();
      $entity = $query->entityCondition('entity_type', 'node')
        ->propertyCondition('type', 'repository')
        ->propertyCondition('title', $repository)
        ->range(0,1)
        ->execute();

      $repository_id = array_keys($entity['node'])[0];
      $wrapper->og_repo->set($repository_id);
    }
    $wrapper->save();
  }

  /**
   * @When I create repository :title
   */
  public function iCreateRepository($title) {
    $this->iCreateNodeOfType($title, 'repository');
  }

  /**
   * @when I create CI build :title for repository :repository
   */
  public function iCreateCIBuildForRepository($title, $repository) {
    $this->iCreateNodeOfType($title, 'ci_build', $repository);
  }

  /**
   * @When I delete :title node of type :type
   */
  public function iDeleteNodeOfType($title, $type) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($type))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => $type,
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $nid = key($result['node']);
    node_delete($nid);
  }

  /**
   * @Then I should not be able to add content to :title repository
   */
  public function iShouldNotBeAbleToAddContentToRepository($title) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'repository')
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => 'repository',
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $gid = key($result['node']);
    $account = user_load_by_name($this->user->name);
    if (node_access('update', node_load($gid), $account)) {
      throw new \Exception(format_string("User @user can add content to @title group", array('@title' => $title, '@user' => $this->user->name)));
    }
  }

  /**
   * @Then Node :title of type :type should be deleted
   */
  public function nodeOfTypeShouldBeDeleted($title, $type) {
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($type))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      return;
    }

    $params = array(
      '@title' => $title,
      '@type' => $type,
    );
    throw new \Exception(format_string("Node @title of @type was found.", $params));
  }
}
