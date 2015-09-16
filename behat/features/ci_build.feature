Feature: CI Build
  In order to be able to see CI Builds in the site after migration
  As a privileged user
  I need to be able to view a CI Build page

  @api
  Scenario Outline: Check access to the CI Builds
    Given I login with user "admin"
    When  I visit "<title>" node of type "ci_build"
    Then  I should have access to the page

  Examples:
    | title                   |
    | Gizra/Gizra             |
    | drupal/drupal           |
    | DavidKohav/test-example |

  @api
  Scenario: Check after disabling of the CI build a new CI build item is removed.
    Given I login with user "William"
    When I create repository and CI build "William/app5"
    And I disable CI Build  "William/app5"
    Then The CI Build item for build "William/app5" should be removed

  @api
  Scenario: Check after re-enabling of the CI build a new CI build item is created.
      Given I login with user "William"
      When I enable CI Build  "William/app5"
      Then The CI Build item for build "William/app5" should be created

  @api
  Scenario: Check flagging subscription flag.
    Given I login with user "William"
    When I flag subscription to node "William/app5"
    Then The "Subscribe CI Builds" flag on the node "William/app5" should be "flagged"

  @api
  Scenario: Check unflagging subscription flag.
    Given I login with user "William"
    When I unflag subscription to node "William/app5"
    Then The "Subscribe CI Builds" flag on the node "William/app5" should be "unflagged"
