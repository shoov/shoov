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

