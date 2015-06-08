Feature: CI Incident
  In order to be able to see CI Incidents in the site after migration
  As a privileged user
  I need to be able to view a CI Incident page

  @api
  Scenario Outline: Check access to the CI Incidents
    Given I login with user "admin"
    When  I visit "<title>" node of type "ci_incident"
    Then  I should have access to the page

  Examples:
    | title      |
    | incident 1 |
    | incident 3 |
    | incident 4 |
