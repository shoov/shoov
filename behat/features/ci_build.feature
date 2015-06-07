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
