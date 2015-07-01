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
        $params = [
          '@title' => $title
        ];
        throw new \Exception(format_string('Failed to create a new CI build @title because repository is undefined.', $params));
      }

      $query = new EntityFieldQuery();
      $entity = $query->entityCondition('entity_type', 'node')
        ->propertyCondition('title', $repository)
        ->propertyCondition('type', 'repository')
        ->range(0,1)
        ->execute();

      $repository_id = array_keys($entity['node'])[0];
      $wrapper->og_repo->set($repository_id);
    }
    $wrapper->save();
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

  /**
   * @When I create repository and CI build :title
   */
  public function iCreateRepositoryAndCiBuild($title) {
    // First, create a new repository.
    $this->iCreateNodeOfType($title, 'repository');
    // Second, create a new CI build.
    $this->iCreateNodeOfType($title, 'ci_build', $title);
  }

  /**
   * @When /^I set status "([^"].+)" for CI build "([^"].+)" items "([0-9]+)" times?$/
   */
  public function iSetStatusForCiBuildItemsTimes($status, $ci_build, $times) {
    if ($times <= 0) return;

    // Get ID of CI build.
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('title', $ci_build)
      ->propertyCondition('type', 'ci_build')
      ->propertyOrderBy('vid', 'DESC')
      ->range(0, 1)
      ->execute();

    $ci_build_id = array_keys($entity['node'])[0];
    if (!$ci_build_id) {
      $params = [
        '@title' => $ci_build
      ];
      throw new \Exception(format_string('Failed to get ID of CI build @title', $params));
    }

    // Find last CI build item.
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'message')
      ->fieldCondition('field_ci_build_status', 'value', 'queue')
      ->fieldCondition('field_ci_build', 'target_id', $ci_build_id)
      ->range(0,1)
      ->execute();

    // TODO: Check that count of CI build items only one.

    $message = message_load(array_keys($entity['message'])[0]);
    $wrapper = entity_metadata_wrapper('message', $message);
    $wrapper->field_ci_build_status->set(strtolower($status));
    $wrapper->save();

    $this->iSetStatusForCiBuildItemsTimes($status, $ci_build, $times - 1);
  }

  /**
   * @Then I should see status for CI build :ci_build equal to :status
   */
  public function iShouldSeeStatusForCiBuildEqualTo($ci_build, $status) {
    $status = (strtolower($status) == 'ok') ? NULL : strtolower($status);

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
      throw new \Exception(format_string("CI build @ci_build have status @ci_build_status instead of @status", $params));
    }

  }

  /**
   * @Then I should see failed count for CI build :ci_build equal to :count
   */
  public function iShouldSeeFailedCountForCiBuildEqualTo($ci_build, $count) {
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('title', $ci_build)
      ->propertyCondition('type', 'ci_build')
      ->propertyOrderBy('vid', 'DESC')
      ->range(0, 1)
      ->execute();

    $ci_build = node_load(array_keys($entity['node'])[0]);
    $wrapper = entity_metadata_wrapper('node', $ci_build);
    $failed_count = $wrapper->field_ci_build_failed_count->value();
    if ($failed_count != $count) {
      $params = [
        '@title' => $ci_build,
        '@failed_count' => $failed_count,
        '@count' => $count
      ];
      throw new \Exception(format_string('CI build @title have failed count equal to @failed_count instead of @count', $params));
    }
  }

  /**
   * @Then I should see incident with status :status for CI build :ci_build
   */
  public function iShouldSeeIncidentForCiBuild($status, $ci_build) {
    // Get ID of CI build.
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('title', $ci_build)
      ->propertyCondition('type', 'ci_build')
      ->propertyOrderBy('vid', 'DESC')
      ->range(0, 1)
      ->execute();

    $ci_build_id = array_keys($entity['node'])[0];
    if (!$ci_build_id) {
      $params = [
        '@title' => $ci_build
      ];
      throw new \Exception(format_string('Failed to get ID of CI build @title', $params));
    }

    // Get incident.
    $query = new EntityFieldQuery();
    $entity = $query->entityCondition('entity_type', 'node')
      ->propertyCondition('type', 'ci_incident')
      ->fieldCondition('field_ci_build', 'target_id', $ci_build_id)
      ->range(0, 1)
      ->execute();

    // Predefine properties for exception.
    $params = [
      '@title' => $ci_build,
      '@status' => $status
    ];
    $error_message = format_string('The incident for CI build @title doesn\'t contain "@status" item', $params);

    $ci_incident = node_load(array_keys($entity['node'])[0]);
    $wrapper = entity_metadata_wrapper('node', $ci_incident);
    if ($status == 'error') {
      $failing_build = $wrapper->field_failing_build->value();
      if (!$failing_build) {
        throw new \Exception($error_message);
      }
    }
    elseif ($status == 'fixed') {
      $fixed_build = $wrapper->field_fixed_build->value();
      if (!$fixed_build) {
        throw new \Exception($error_message);
      }
    }
  }
}
