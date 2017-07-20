Feature: Redirect tests

  As a anonymous user
  I want to test if redirect works well.

@api
  Scenario: Node page machine path should redirect to alias.
    Given I am an anonymous user
    Given "page" content:
    | title                    | nid | body             |
    | Page test for redirect   | 999 | PLACEHOLDER BODY |

    When I go to "/node/999"
    Then print current URL
    Then I should get a 200 HTTP response
    And the url should match "/page-test-redirect"

    When I go to "/node/999"
    Then print current URL
    Then I should get a 200 HTTP response
    And the url should match "/it/page-test-redirect"

    When I go to "/page-test-redirect"
    Then print current URL
    Then I should get a 200 HTTP response
    And the url should match "/it/page-test-redirect"

@api
  Scenario: Project full view page machine path should redirect to alias.
    Given I am an anonymous user
    #It's not possible to given a fake entity, test it on really project.
    When I go to "/project/1"
    Then print current URL
    Then I should get a 200 HTTP response
    And the url should match "/progetto/fatturazione-elettronica"

    When I go to "/project/1"
    Then print current URL
    Then I should get a 200 HTTP response
    And the url should match "/it/progetto/fatturazione-elettronica"
