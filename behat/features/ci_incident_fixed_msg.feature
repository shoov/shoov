Feature: CI Incident Fixed Message
  In order to be able to see CI Incidents Fixed Messages in the site after migration
  As a privileged user
  I need to be able to view a CI Incidents Fixed Messages

  @api
  Scenario Outline: Check CI Incidents Error messages exists
    Given I login with user "admin"
    When  I visit "admin/content/message?type=ci_incident_fixed"
    Then  I should see text matching "<title>"

  Examples:
    | title                                               |
    | amitaibu/gizra-behat branch master is fixed         |
    | DavidKohav/test-example branch feature-365 is fixed |
    | Gizra/Gizra branch develop is fixed                 |
