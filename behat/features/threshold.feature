Feature: Check threshold functionally
  In order to be able to create CI build items with error state
  As a privileges user
  I need to see new status of CI build and new incidents.

  @api
  Scenario: Check that CI build have status "OK" if last two items are "Done"
    Given I login with user "William"
    When  I create repository and CI build "William/app1"
    And   I set status "Done" for CI build "William/app1" items "2" times
    Then  I should see status for CI build "William/app1" equal to "Ok"

  @api
  Scenario: Check that CI build have status "Unconfirmed error" if last item are "Error"
    Given I login with user "William"
    When  I create repository and CI build "William/app2"
    And   I set status "Error" for CI build "William/app2" items "1" time
    Then  I should see status for CI build "William/app2" equal to "unconfirmed_error"

  @api
  Scenario: Check that CI build have status "Unconfirmed error" if last item are "Error"
    Given I login with user "William"
    When  I create repository and CI build "William/app3"
    And   I set status "Error" for CI build "William/app3" items "2" times
    Then  I should see status for CI build "William/app3" equal to "error"

  @api @new
  Scenario: Create two failed builds, after one done build and check that counter of failed builds is zero and two incident Failed and Fixed exists.
    Given I login with user "William"
    When  I create repository and CI build "William/app4"
    And   I set status "Error" for CI build "William/app4" items "2" times
    And   I set status "Done" for CI build "William/app4" items "1" time
    Then  I should see status for CI build "William/app4" equal to "Ok"
    And   I should see failed count for CI build "William/app4" equal to "0"
    And   I should see incident with status "error" for CI build "William/app4"
    And   I should see incident with status "fixed" for CI build "William/app4"
