Feature: CI Build Message
  In order to be able to see CI Builds Messages in the site after migration
  As a privileged user
  I need to be able to view a CI Build Messages

  @api
  Scenario Outline: Check CI Builds messages exists
    Given I login with user "admin"
    When  I visit "admin/content/message?type=ci_build"
    Then  I should see text matching "<title>"

  Examples:
    | title                                              |
    | shoov-tester/gizra-behat branch master Done        |
    | shoov-tester/gizra-behat branch master in progress |
    | Gizra/Gizra branch develop Queue                   |
