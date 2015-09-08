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
    And   "1" CI build item for CI build "William/app10" are set to status "in_progress"
    Then  The "field_requeue_count" value should be "0" for the CI build "William/app10"

  @api
  Scenario: Check CI Build message requeue counter is 1 after changing from "in_progress" to "queue".
    Given I login with user "William"
    When  I create repository and CI build "William/app11"
    And   "1" CI build item for CI build "William/app11" are set to status "in_progress"
    And   I change CI build "William/app11" status from "in_progress" to "queue"
    Then  The "field_requeue_count" value should be "1" for the CI build "William/app11"


