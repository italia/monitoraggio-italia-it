Feature: Check access of dashboard/login

  As anonymous i should see dashboard/login url rather than user/login.

  @api @dashboard_url
  Scenario: Anonymous user shouldn't see the user/login page.
    Given I am an anonymous user
    When I go to "user/login"
    Then I should not get a 200 HTTP response

  @api @dashboard_url
  Scenario: Anonymous user shouldn't see the user/password page.
    Given I am an anonymous user
    When I go to "user/password"
    Then I should not get a 200 HTTP response

  @api @dashboard_url
  Scenario: Anonymous user should see the dashboard/login page.
    Given I am an anonymous user
    When I go to "dashboard/login"
    Then I should get a 200 HTTP response

  @api @dashboard_url
  Scenario: Anonymous user should see the /dashboard page.
    Given I am an anonymous user
    When I go to "dashboard"
    Then I should get a 200 HTTP response

  @api @dashboard_url
  Scenario: Anonymous user should see the /dashboard page.
    Given I am logged in dashboard as a user with the "editor" role
    When I go to "dashboard"
    Then I should get a 200 HTTP response
