Feature: Repository
  In order to be able to group content in the site
  As a privileged user
  I need to be able to view a repository page

  @api
  Scenario Outline: Check access to the repository
    Given I login with user "admin"
    When  I visit "<title>" node of type "repository"
    Then  I should have access to the page

  Examples:
    | title                    |
    | shoov-tester/gizra-behat |
    | DavidKohav/test-example  |
    | Gizra/Gizra              |

  @api
  Scenario: Check authenticated user can create a repository
    Given I login with user "emma"
    When  I create "Test repository" node of type "repository"
    And   I visit "Test repository" node of type "repository"
    Then  I should have access to the page

  @api
  Scenario: Check authenticated user has access to the repository
    Given I login with user "emma"
    When  I visit "Test repository" node of type "repository"
    Then  I should have access to the page

  @api
  Scenario: Check authenticated user can edit a repository
    Given I login with user "emma"
    When  I start editing "Test repository" node of type "repository"
    Then  I should have access to the page

  @api
  Scenario: Check authenticated user can delete a repository
    Given I login with user "emma"
    When  I delete "Test repository" node of type "repository"
    Then  I should see the text "Test repository"
    And   I should see the text "has been deleted."

  @api
  Scenario Outline: Check authenticated user without groups can't create nodes of other types.
    Given I login with user "john"
    When  I start creating node of type "<type>"
    Then  I should not have access to the page

  Examples:
    | type        |
    | ci-build    |
    | ci-incident |
    | screenshot  |
    | ui-build    |

  @api
  Scenario Outline: Check authenticated user can't create nodes in not his groups.
    Given I login with user "emma"
    When  I start creating node of type "<type>"
    Then  I should have access to the page
    And   I should not see "drupal/drupal" option in the repositories list

  Examples:
    | type        |
    | ci-build    |
    | ci-incident |
    | screenshot  |
    | ui-build    |
