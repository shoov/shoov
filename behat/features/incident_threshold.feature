Feature: Check incident threshold functionally
  In order to be able to create CI build items with different states
  As a privileged user
  I need to see the right status of CI build and new incidents

  @api
  Scenario: Create two successful CI build items and check status of CI build is "Ok".
    Given I login with user "William"
    When  I create repository and CI build "William/app1" with github id "111111"
    And   "2" CI build items for CI build "William/app1" are set to status "Done"
    Then  I should see status "Ok" for CI build "William/app1"

  @api
  Scenario: Create one failed CI build items and check status of CI build is "Unconfirmed error".
    Given I login with user "William"
    When  I create repository and CI build "William/app2" with github id "222222"
    And   "1" CI build item for CI build "William/app2" are set to status "Error"
    Then  I should see status "Unconfirmed error" for CI build "William/app2"

  @api
  Scenario: Create two failed CI build items and check status of CI build is "Error" and an incident is created.
    Given I login with user "William"
    When  I create repository and CI build "William/app3" with github id "333333"
    And   "2" CI build item for CI build "William/app3" are set to status "Error"
    Then  I should see status "Error" for CI build "William/app3"
    And   I should see incident with status "Error" for CI build "William/app3"

  @api
  Scenario: Create failed CI build items and later a fixed one and check incident is marked as fixed.
    Given I login with user "William"
    When I create repository and CI build "William/app4" with github id "444444"
    And  "3" CI build item for CI build "William/app4" are set to status "Error"
    And  "1" CI build item for CI build "William/app4" are set to status "Done"
    Then  I should see status "Ok" for CI build "William/app4"
    And   I should see failed count "0" for CI build "William/app4"
    And   I should see incident with status "Fixed" for CI build "William/app4"
