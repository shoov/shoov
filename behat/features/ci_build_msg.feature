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
    | shoov-tester/gizra-behat branch master In progress |
    | Gizra/Gizra branch develop Queue                   |

  @api
  Scenario: Check CI Build message requeue counter is 0 when created.
    Given I login with user "William"
    When  I create repository and CI build "William/app10"
    And   The CI build item for CI build "William/app10" is set to status "In progress"
    Then  The "Requeue Count" field value should be "0" for the CI build "William/app10"

  @api
  Scenario: Check CI Build message requeue counter is 1 after changing from "in_progress" to "queue".
    Given I login with user "William"
    When  I create repository and CI build "William/app11"
    And   The CI build item for CI build "William/app11" is set to status "In progress"
    And   I change CI build "William/app11" status from "In progress" to "Queue"
    Then  The "Requeue Count" field value should be "1" for the CI build "William/app11"


