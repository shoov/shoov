Feature: UI Build
  In order to be able to see UI Builds in the site after migration
  As a privileged user
  I need to be able to view a UI Build page

  @api
  Scenario Outline: Check access to the UI Builds
    Given I login with user "admin"
    When  I visit "<title>" node of type "ui_build"
    Then  I should have access to the page

  Examples:
    | title                           |
    | Update README.md (03d000)       |
    | Change interface (7b5200)       |
    | Declare abstract class (40bd00) |
