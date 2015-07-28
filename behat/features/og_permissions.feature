Feature: OG permissions
  Check og permissions are set correctly

  @api
  Scenario Outline: Check authenticated user can edit own group content
    Given I login with user "emma"
    Then  I should be able to edit "<title>" group content of type "<type>"

  Examples:
    | type        | title                      |
    | ci_build    | DavidKohav/test-example    |
    | ci_incident | incident 2                 |
    | screenshot  | browserstack-chrome google |
    | ui_build    | Fix error (356a19)         |

  @api
  Scenario Outline: Check authenticated user can delete own group content
    Given I login with user "emma"
    Then  I should be able to delete "<title>" group content of type "<type>"

  Examples:
    | type        | title                      |
    | ci_build    | DavidKohav/test-example    |
    | ci_incident | incident 2                 |
    | screenshot  | browserstack-chrome google |
    | ui_build    | Fix error (356a19)         |

  @api
  Scenario Outline: Check authenticated user can create group content
    Given I login with user "emma"
    Then  I should be able to create group content of type "<type>"

  Examples:
    | type        |
    | ci_build    |
    | ci_incident |
    | screenshot  |
    | ui_build    |

