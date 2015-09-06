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
    Scenario: Check that after disnabling of the CI build a new CI build item
    is removed.
      Given I login with user "admin"
      When I disable CI Build  "Gizra/Gizra"
      Then The CI Build item for build "Gizra/Gizra" should be removed

  @api
  Scenario: Check that after re-enabling of the CI build a new CI build item
    is created.
      Given I login with user "admin"
      When I enable CI Build  "Gizra/Gizra"
      Then The CI Build item for build "Gizra/Gizra" should be created
