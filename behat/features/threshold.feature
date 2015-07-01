Feature: Check threshold functionally
  In order to be able to create CI build items with error state
  As a privileges user
  I need to see new status of CI build and new incidents.

  @api
  Scenario: Check create new repository.
    Given I login with user "William"
    When  I create repository "William/app"
    And   I visit repository "William/app"
    Then  I should have access to the page

  @api
  Scenario: Check create new CI build for new created repository.
    Given I login with user "William"
    When  I create CI build "William/app" for repository "William/app"
    And   I visit CI build "William/app"
    Then  I should have access to the page

  @api
  Scenario: Check create new CI build item with status "OK" for new created CI build.
    Given I login with user "William"
    When  I create CI build item with status "Done" for CI build "William/app"
    Then  CI build "William/app" should have status "OK"

  @api
  Scenario: Check create new CI build item with status "Error" for new created CI build.
    Given I login with user "William"
    When  I create CI build item with status "Error" for CI build "William/app"
    Then  CI build "William/app" should have status "Unconfirmed error"
    And   CI build "William/app" should have failed count equal to "1"

  @api
  Scenario: Check create new CI build item with status "Error" for new created CI build.
    Given I login with user "William"
    When  I create CI build item with status "Error" for CI build "William/app"
    Then  CI build "William/app" should have status "Error"
    And   CI build "William/app" should have failed count equal to "2"
    And   Incident for CI build "William/app" should be created.

