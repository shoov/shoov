Feature: Screenshot
  In order to be able to see the content Screenshot in the site after migration
  As a privileged user
  I need to be able to view a Screenshot page

  @api
  Scenario Outline: Check access to the Screenshot
    Given I login with user "admin"
    When  I visit "<title>" node of type "screenshot"
    Then  I should have access to the page

  Examples:
    | title                      |
    | browserstack-chrome google |
    | browserstack-ie11 wiki     |
    | default duckduck           |

