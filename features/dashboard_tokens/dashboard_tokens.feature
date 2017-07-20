Feature: Check Dashboard tokens existance into the metatag settings form

  As Administrator user
  I should see Dashboard token existance
  inside the input fields of settings metatag form.

  @api
  Scenario: Admin should see the dashboard tokens inside the input fields of settings metatag form of 'Page'.
    Given I am logged in dashboard as a user with the "administrator" role
    When I go to "admin/config/search/metatag/node__page"
    Then I should get a 200 HTTP response
    And I should see a "#edit-description" element
    And I should see "[dashboard:description:200]" in the "#edit-description" element
    And the "edit-og-description" field should contain "[dashboard:description:200]"
    And the "edit-twitter-cards-description" field should contain "[dashboard:description:200]"