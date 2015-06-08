Feature: User
  In order to be able to see migrated users.
  As a privileged user
  I need to be able to view a migrated users.

  @api
  Scenario Outline: Check users exists
    Given I login with user "admin"
    When  I visit "admin/people"
    Then  I should see text matching "<name>"

  Examples:
    | name    |
    | John    |
    | Emma    |
    | William |
