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
      // User has access.
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
  public function iCreateNodeOfType($title, $type, $repository = NULL, $github_id = 123456) {
    $account = user_load_by_name($this->user->name);
    $values = array(
      'type' => $type,
      'uid' => $account->uid,
    );
    $entity = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $entity);
    $wrapper->title->set($title);
    if ($type == 'repository') {
      $wrapper->field_github_id->set($github_id);
    }
    elseif ($type == 'ci_build') {
      if (!$repository) {
        $params = array('@title' => $title);
        throw new \Exception(format_string('Failed to create a new CI build @title because repository is undefined.', $params));
      }

      $query = new EntityFieldQuery();
      $result = $query->entityCondition('entity_type', 'node')
        ->entityCondition('bundle', 'repository')
        ->propertyCondition('title', $repository)
        ->range(0,1)
        ->execute();

      $repository_id = key($result['node']);
      $wrapper->og_repo->set($repository_id);
    }
    $wrapper->save();
  }

  /**
   * @Then I should not be able to create repository with github id :github_id
   */
  public function iShouldNotBeAbleToCreateRepositoryWithGithubId($github_id) {
    $this->iCreateNodeOfType('Test repository ' . $github_id, 'repository', NULL, $github_id);
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
    // Create a new repository.
    $this->iCreateNodeOfType($title, 'repository');
    // Create a new CI build.
    $this->iCreateNodeOfType($title, 'ci_build', $title);
  }

  /**
   * Update the status of existing CI build items
   *
   * As we are setting the status to "done" or "error" new CI build items are
   * automatically created.
   *
   * @When /^"([0-9]+)" CI build items? for CI build "([^"].+)" are set to status "([^"].+)"$/
   */
  public function iSetStatusForCiBuildItemsTimes($times, $ci_build_title, $status) {
    if ($times <= 0) {
      return;
    }

    $params = array('@title' => $ci_build_title);

    // Get the CI build.
    if (!$ci_build_node = $this->getNodeByTitleAndBundle($ci_build_title, 'ci_build')) {
      throw new \Exception(format_string('Failed to get ID of CI build @title', $params));
    }

    // Find the last CI build item.
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'message')
      ->fieldCondition('field_ci_build_status', 'value', 'queue')
      ->fieldCondition('field_ci_build', 'target_id', $ci_build_node->vid)
      ->execute();

    if (count($result['message']) > 1) {
      throw new \Exception(format_string('There can be only one CI build in "queue" status for the CI build @title.', $params));
    }

    $wrapper = entity_metadata_wrapper('message', key($result['message']));
    $wrapper->field_ci_build_status->set(strtolower($status));
    $wrapper->save();

    $this->iSetStatusForCiBuildItemsTimes($times - 1, $ci_build_title, $status);
  }

  /**
   * @Then I should see status :status for CI build :ci_build
   */
  public function iShouldSeeStatusForCiBuildEqualTo($ci_build, $status) {
    $statuses = array(
      'Ok' => NULL,
      'Error' => 'error',
      'Unconfirmed error' => 'unconfirmed_error'
    );

    $machine_status = $statuses[$status];

    // Get CI build.
    $ci_build_node = $this->getNodeByTitleAndBundle($ci_build, 'ci_build');
    $wrapper = entity_metadata_wrapper('node', $ci_build_node);

    $ci_build_status = $wrapper->field_ci_build_incident_status->value();
    if ($ci_build_status != $machine_status) {
      $params = array(
        '@title' => $ci_build,
        '@ci_build_status' => $ci_build_status,
        '@status' => $status
      );
      throw new \Exception(format_string("CI build @ci_build has status @ci_build_status instead of @status", $params));
    }

  }

  /**
   * @Then I should see failed count :count for CI build :ci_build
   */
  public function iShouldSeeFailedCountForCiBuildEqualTo($ci_build, $count) {
    $ci_build_node = $this->getNodeByTitleAndBundle($ci_build, 'ci_build');
    $wrapper = entity_metadata_wrapper('node', $ci_build_node);
    $failed_count = $wrapper->field_ci_build_failed_count->value();
    if ($failed_count == $count) {
      return;
    }

    $params = array(
      '@title' => $ci_build,
      '@failed_count' => $failed_count,
      '@count' => $count
    );
    throw new \Exception(format_string('CI build @title have failed count equal to @failed_count instead of @count', $params));
  }

  /**
   * @Then I should see incident with status :status for CI build :ci_build
   */
  public function iShouldSeeIncidentForCiBuild($status, $ci_build) {
    $params = array(
      '@title' => $ci_build,
      '@status' => $status
    );

    $status = strtolower($status);

    // Get ID of CI build.
    if (!$ci_build_node = $this->getNodeByTitleAndBundle($ci_build, 'ci_build')) {
      throw new \Exception(format_string('CI build "@title" was not found.', $params));
    }

    // Get the last incident of the CI build.
    if (!$ci_incident = shoov_ci_incident_get_latest_error_incident($ci_build_node, FALSE)) {
      throw new \Exception(format_string('CI incident for @title was not found', $params));
    }

    $wrapper = entity_metadata_wrapper('node', $ci_incident);

    $error_message = format_string('The CI Incident for CI build "@title" is not set to "@status" status.', $params);

    if ($status == 'error') {
      if (!$failing_build = $wrapper->field_failing_build->value()) {
        throw new \Exception($error_message);
      }
    }
    elseif ($status == 'fixed') {
      if (!$fixed_build = $wrapper->field_fixed_build->value()) {
        throw new \Exception($error_message);
      }
    }
  }

  /**
   * Find a node by title and bundle.
   *
   * @param string $title
   *    The title of node searching for.
   * @param string $bundle
   *    The name of bundle (type) of node searching for.
   *
   * @return object
   *    The Node object.
   *
   * @throws \Exception
   *    The error if node not found.
   */
  protected function getNodeByTitleAndBundle($title, $bundle) {
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', $bundle)
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->propertyOrderBy('vid', 'DESC')
      ->range(0, 1)
      ->execute();

    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@bundle' => $bundle
      );
      throw new \Exception(format_string('Node with title @title and bundle @bundle was not found.', $params));
    }

    return node_load(key($result['node']));
  }
}
