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
  public function iCreateNodeOfType($title, $type) {
    $account = user_load_by_name($this->user->name);
    $values = array(
      'type' => $type,
      'uid' => $account->uid,
    );
    $entity = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $entity);
    $wrapper->title->set($title);
    $wrapper->field_github_id->set(123456);
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
}
