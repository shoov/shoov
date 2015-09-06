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
   * Get node ID by bundle and title.
   *
   * @param string $bundle
   *    The name of bundle (type) of node searching for.
   * @param string $title
   *    The title of node searching for.
   *
   * @return int
   *    The Node ID.
   *
   * @throws \Exception
   *    The error if node not found.
   */
  public function getNodeIdByTitleAndBundle($bundle, $title) {
    $bundle = str_replace(array(' ', '-'), '_', $bundle);
    $query = new \entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', strtolower($bundle))
      ->propertyCondition('title', $title)
      ->propertyCondition('status', NODE_PUBLISHED)
      ->range(0, 1)
      ->execute();
    if (empty($result['node'])) {
      $params = array(
        '@title' => $title,
        '@type' => $bundle,
      );
      throw new \Exception(format_string("Node @title of @type not found.", $params));
    }
    $nid = key($result['node']);
    return $nid;
  }

  /**
   * @When /^I visit "([^"]*)" node of type "([^"]*)"$/
   */
  public function iVisitNodePageOfType($title, $type) {
    $nid = $this->getNodeIdByTitleAndBundle($type, $title);
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
   * @param $title
   * @param $type
   * @param $operation
   * @throws Exception
   */
  public function checkUserHasOgAccessForOperation($title, $type, $operation) {
    $nid = $this->getNodeIdByTitleAndBundle($type, $title);

    if (og_node_access(node_load($nid), $operation, user_load_by_name($this->user->name))) {
      // User has access.
      return;
    }

    $params = array(
      '@title' => $title,
      '@type' => $type,
      '@user' => $this->user->name,
      '@op' => $operation,
    );
    throw new \Exception(format_string("User @user can't @op group content @title of type @type not found.", $params));
  }

  /**
   * Check node access for operation.
   *
   * @param string $title
   *  Node title.
   * @param string $type
   *  Node bundle (type).
   * @param string $operation
   *  Operation name: 'view', 'update', 'delete'.
   *
   * @throws Exception
   *  Throws exception when node not found.
   */
  public function checkUserHasAccessForOperation($title, $type, $operation) {
    $nid = $this->getNodeIdByTitleAndBundle($type, $title);

    if (node_access($operation, node_load($nid), user_load_by_name($this->user->name))) {
      // User has access.
      return;
    }

    $params = array(
      '@title' => $title,
      '@type' => $type,
      '@user' => $this->user->name,
      '@op' => $operation,
    );
    throw new \Exception(format_string("User @user can't @op group content @title of type @type not found.", $params));
  }

  /**
   * @When I should be able to delete :title group content of type :type
   */
  public function iShouldBeAbleToDeleteGroupContentOfType($title, $type) {
    $this->checkUserHasOgAccessForOperation($title, $type, 'delete');
  }

  /**
   * @When I should be able to edit :title group content of type :type
   */
  public function iShouldBeAbleToEditGroupContentOfType($title, $type) {
    $this->checkUserHasOgAccessForOperation($title, $type, 'update');
  }

  /**
   * @When I should be able to edit :title node of type :type
   */
  public function iShouldBeAbleToEditNodeOfType($title, $type) {
    $this->checkUserHasAccessForOperation($title, $type, 'update');
  }

  /**
   * @Then I should be able to create group content of type :type
   */
  public function iShouldBeAbleToCreateGroupContentOfType($type) {
    $bundle = str_replace(array(' ', '-'), '_', $type);
    if (og_node_access(strtolower($bundle), 'create', user_load_by_name($this->user->name))) {
      // User has access.
      return;
    }

    $params = array(
      '@type' => $type,
      '@user' => $this->user->name,
    );
    throw new \Exception(format_string("User @user can't create group content of type @type not found.", $params));
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
  public function iCreateNodeOfType($title, $type, $repository = NULL, $github_id = NULL, $check_saving = FALSE) {
    $account = user_load_by_name($this->user->name);
    $values = array(
      'type' => $type,
      'uid' => $account->uid,
    );
    $entity = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $entity);
    $wrapper->title->set($title);
    if ($type == 'repository') {
      $github_id = $github_id ? $github_id : rand(99999, 1000000);
      $wrapper->field_github_id->set($github_id);
    }
    elseif ($type == 'ci_build') {
      if (!$repository) {
        $params = array('@title' => $title);
        throw new \Exception(format_string('Failed to create a new CI build "@title" because repository is undefined.', $params));
      }

      $repository_id = $this->getNodeIdByTitleAndBundle('repository', $repository);
      $wrapper->og_repo->set($repository_id);
    }

    try {
      $wrapper->save();
      return TRUE;
    }
    catch (\Exception $e) {
      if (!$check_saving) {
        throw $e;
      }
      return FALSE;
    }
  }

  /**
   * @When I create repository :title with GitHub ID :id
   */
  public function iCreateNodeRepositoryWithGithubId($title, $github_id) {
    $this->iCreateNodeOfType($title, 'repository', NULL, $github_id);
  }

  /**
   * @Then I should not be able to create repository with GitHub ID :github_id
   */
  public function iShouldNotBeAbleToCreateRepositoryWithGithubId($github_id) {
    $saved = $this->iCreateNodeOfType('Test repository ' . $github_id, 'repository', NULL, $github_id, TRUE);
    if ($saved) {
      throw new \Exception(format_string("GitHub ID @githubid was duplicated.", array('@githubid' => $github_id)));
    }
  }


  /**
   * @When I delete :title node of type :type
   */
  public function iDeleteNodeOfType($title, $type) {
    $nid = $this->getNodeIdByTitleAndBundle($type, $title);
    node_delete($nid);
  }

  /**
   * @Then I should not be able to add content to :title repository
   */
  public function iShouldNotBeAbleToAddContentToRepository($title) {
    $gid = $this->getNodeIdByTitleAndBundle('repository', $title);
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
  public function iCreateRepositoryAndCiBuild($title, $github_id = NULL) {
    // Create a new repository.
    $this->iCreateNodeOfType($title, 'repository', NULL, $github_id);
    // Create a new CI build.
    $this->iCreateNodeOfType($title, 'ci_build', $title);
  }

  /**
   * @When I create repository and CI build :title with GitHub ID :id
   */
  public function iCreateRepositoryAndCiBuildWithGithubId($title, $github_id) {
    $this->iCreateRepositoryAndCiBuild($title, $github_id);
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
    $nid = $this->getNodeIdByTitleAndBundle($bundle, $title);
    return node_load($nid);
  }

  /**
   * @When I disable CI Build  :title
   */
  public function iDisableCiBuild($title) {
    $node = $this->getNodeByTitleAndBundle($title, 'ci_build');
    $wrapper = entity_metadata_wrapper('node', $node);
    $wrapper->field_ci_build_enabled->set(FALSE);
    $wrapper->save();
  }

  /**
   * @When I enable CI Build  :title
   */
  public function iEnableCiBuild($title) {
    $node = $this->getNodeByTitleAndBundle($title, 'ci_build');
    $wrapper = entity_metadata_wrapper('node', $node);
    $wrapper->field_ci_build_enabled->set(TRUE);
    $wrapper->save();
  }

  /**
   * @Then The CI Build item for build :title should be removed
   */
  public function theCiBuildItemForBuildShouldBeRemoved($title) {
    $node = $this->getNodeByTitleAndBundle($title, 'ci_build');
    $items = $this->getCiBuildItemsByStatus($node, 'queue');
    if ($items) {
      $error_message = format_string('The CI Build queue items for CI build "@title" are not removed',
        array('@title' => $title));
      throw new \Exception($error_message);
    }
  }

  /**
   * @Then The CI Build item for build :title should be created
   */
  public function theCiBuildItemForBuildShouldBeCreated($title) {
    $node = $this->getNodeByTitleAndBundle($title, 'ci_build');
    $items = $this->getCiBuildItemsByStatus($node, 'queue');
    if (!$items) {
      $error_message = format_string('The CI Build queue item for CI build "@title" is not created',
        array('@title' => $title));
      throw new \Exception($error_message);
    }
  }

  /**
   * Get all messages with certain status of certain CI Build.
   *
   * @param $node
   *  The CI Build node.
   * @param $status
   *  Status field value. Defaults to "queue".
   *
   * @return array
   *  Return array of messages IDs.
   */
  public function getCiBuildItemsByStatus($node, $status ='queue') {
    $account = user_load($node->uid);
    $query = new EntityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'message')
      ->entityCondition('bundle', 'ci_build')
      ->fieldCondition('field_ci_build', 'target_id', $node->nid)
      ->fieldCondition('field_ci_build_status', 'value', $status)
      // Add user's account to the metadata of the query in order not to get
      // items user doesn't have access to.
      ->addMetaData('account', $account)
      ->execute();

    return empty($result['message']) ? array() : array_keys($result['message']);
  }

}
