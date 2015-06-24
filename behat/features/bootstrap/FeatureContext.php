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
   * @When I start editing :title node of type :type
   */
  public function iStartEditingNodeOfType($title, $type) {
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
    $this->getSession()->visit($this->locatePath('node/' . $nid . '/edit'));
  }

  /**
   * @When I start creating node of type :type
   */
  public function iStartCreatingNodeOfType($type) {
    $this->getSession()->visit($this->locatePath('node/add/' . $type));
  }

  /**
   * @When I create :title node of type :type
   */
  public function iCreateNodeOfType($title, $type) {
    $user = user_load_by_name($this->user->name);
    $values = array(
      'type' => $type,
      'uid' => $user->uid,
      'status' => 1,
      'comment' => 1,
      'promote' => 0,
    );
    $entity = entity_create('node', $values);
    $wrapper = entity_metadata_wrapper('node', $entity);
    $wrapper->title->set($title);
    $entity->field_github_id[LANGUAGE_NONE][0] = array('value' => 123456);
    $wrapper->save();
  }

  /**
   * @When I delete :arg1 node of type :arg2
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
    $this->getSession()->visit($this->locatePath('node/' . $nid . '/delete'));
    $this->getSession()->getPage()->pressButton('Delete');
  }

  public function iCheckTheRadioButton($labelText) {
    $page = $this->getSession()->getPage();
    foreach ($page->findAll('css', 'label') as $label) {
      if ( $labelText === $label->getText() ) {
        $radioButton = $page->find('css', '#'.$label->getAttribute('for'));
        $value = $radioButton->getAttribute('value');
        $radioButton->selectOption($value, FALSE);
        return;
      }
    }
    throw new \Exception("Radio button with label {$labelText} not found");
  }

  /**
   * @Then I should see :value option in the repositories list
   */
  public function iShouldSeeOptionInTheRepositoriesList($value) {
    $selectElement = $this->getSession()->getPage()->find('named', array('select', 'og_repo[und][0][default]'));
    $options = $selectElement->findAll('css', 'option');
    foreach ($options as $option) {
      if ($option->getText() == $value) {
        return;
      }
    }
    throw new \Exception("{$value} was not found in the repositories list");
  }

  /**
   * @Then I should not see :value option in the repositories list
   */
  public function iShouldNotSeeOptionInTheRepositoriesList($value) {
    $selectElement = $this->getSession()->getPage()->find('named', array('select', 'og_repo[und][0][default]'));
    $options = $selectElement->findAll('css', 'option');
    foreach ($options as $option) {
      if ($option->getText() == $value) {
        throw new \Exception("{$value} was not found in the repositories list");
      }
    }
  }

}
