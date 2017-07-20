Feature: Check Monitoring indicator bundle form

  As Administrator
  I should see forms of Dashboard Widget.

  @api
  Scenario: Administrator should see the listing page of dashboard widget indicator bundle.
    Given I am logged in dashboard as a user with the "administrator" role
    When I go to "admin/structure/dashboard_widget_type"
    Then I should get a 200 HTTP response

  @api
  Scenario: Administrator should see the "add" form of dashboard widget bundle.
    Given I am logged in dashboard as a user with the "administrator" role
    When I go to "admin/structure/dashboard_widget_type/add"
    Then I should get a 200 HTTP response
    And I should see a "#edit-label" element
