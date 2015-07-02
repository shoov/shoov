Feature: Check threshold functionally
  In order to be able to create CI build items with different states
  As a privileges user
  I need to see the right status of CI build and new incidents

  @api
  Scenario: Create two successfully CI build items and check status of CI build, it's should be 'Ok'.
    Given I login with user "William"
    When  I create repository and CI build "William/app1"
    And   "2" CI build items for CI build "William/app1" are set to status "Done"
    Then  I should see status "Ok" for CI build "William/app1"

  @api
  Scenario: Create one failed CI build items and check status of CI build, it's should be 'Unconfirmed error'.
    Given I login with user "William"
    When  I create repository and CI build "William/app2"
    And   "1" CI build item for CI build "William/app2" are set to status "Error"
    Then  I should see status "Unconfirmed error" for CI build "William/app2"

  @api
  Scenario: Create two failed CI build items and check status of CI build, it's should be 'Error' and incident should be created.
    Given I login with user "William"
    When  I create repository and CI build "William/app3"
    And   "2" CI build item for CI build "William/app3" are set to status "Error"
    Then  I should see status "Error" for CI build "William/app3"
    And   I should see incident with status "Error" for CI build "William/app3"

  @api @wip
  Scenario: Create fix CI build item and check status, failed count and incident created for CI build.
    Given I login with user "William"
    When  I create repository and CI build "William/app4"
    And  "3" CI build item for CI build "William/app4" are set to status "Error"
    And  "1" CI build item for CI build "William/app4" are set to status "Done"
    Then  I should see status "Ok" for CI build "William/app4"
    And   I should see failed count "0" for CI build "William/app4"
    And   I should see incident with status "fixed" for CI build "William/app4"
