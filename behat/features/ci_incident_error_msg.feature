Feature: CI Incident Error Message
  In order to be able to see CI Incidents Error Messages in the site after migration
  As a privileged user
  I need to be able to view a CI Incidents Error Messages

  @api @wip
  Scenario Outline: Check CI Incidents Error messages exists
    Given I login with user "admin"
    When  I visit "admin/content/message?type=ci_incident_error"
    Then  I should see text matching "<title>"

  Examples:
    | title                                                 |
    | DavidKohav/test-example branch feature-365 has failed |
    | Gizra/Gizra branch develop has failed                 |
    | drupal/drupal branch bug-120 has failed               |
