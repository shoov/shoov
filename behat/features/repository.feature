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
    Then  I should be able to edit "Test repository" node of type "repository"

  @api
  Scenario: Check authenticated user can delete a repository
    Given I login with user "emma"
    When  I delete "Test repository" node of type "repository"
    Then  Node "Test repository" of type "repository" should be deleted

  @api
  Scenario Outline: Check authenticated user without groups can't create nodes of other types.
    Given I login with user "john"
    Then  I should not be able to create node of type "<type>"

  Examples:
    | type        |
    | ci_build    |
    | ci_incident |
    | screenshot  |
    | ui_build    |

  @api
  Scenario: Check authenticated user can't create nodes in not his groups.
    Given I login with user "emma"
    Then   I should not be able to add content to "drupal/drupal" repository

  @api
  Scenario: Check user can't create more than 1 repository with one GitHub ID.
    Given I login with user "emma"
    When  I create repository "Test repository" with GitHub ID "12345"
    Then  I should not be able to create repository with GitHub ID "12345"
