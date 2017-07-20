Feature: Check Monitoring indicator bundle form

  As Editor user
  I should see forms of Monitoring indicator.

  @api
  Scenario: Editor should see the "add" form of monitoring indicator bundle.
    Given I am logged in dashboard as a user with the "editor" role
    When I go to "admin/structure/dashboard_indicator/add"
    Then I should get a 200 HTTP response
    And I should see a "#edit-key-unique-0-value" element
    And I should see a "#edit-name-0-value" element
    And I should see a ".field--name-field-indicator-description" element
    And I should see a "#edit-field-indicator-value-title-0-value" element
    And I should see a "#edit-field-indicator-value-descr-0-value" element
    And I should see a "#edit-field-indicator-value-0-value" element
    And I should see a "#edit-field-indicator-baseline-2013-0-value" element
    And I should see a "#edit-field-indicator-targets-wrapper" element

    # Custom box.
    And I should see a "#edit-field-indicator-cb-title-0-value" element
    And I should see a "#edit-field-indicator-cb-description-0-value" element
    And I should see a "#edit-field-indicator-cb-value-wrapper .field-add-more-submit.button" element

    And I should see a "#edit-actions" element

  @api
  Scenario: Editor should see the edit/delete button of indicator bundle.
    Given I am logged in dashboard as a user with the "editor" role
    When I go to "admin/structure/dashboard_indicator"
    Then I should get a 200 HTTP response

  @api
  Scenario: Editor should see the path of indicator full view.
    Given I am logged in dashboard as a user with the "editor" role
    When I go to "admin/structure/dashboard_indicator/1"
    Then I should get a 200 HTTP response

  @api
  Scenario: Anonymous should not see the path of indicator full view.
    Given I am an anonymous user
    When I go to "admin/structure/dashboard_indicator/1"
    Then I should get a 403 HTTP response
