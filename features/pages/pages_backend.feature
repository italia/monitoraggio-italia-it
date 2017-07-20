Feature: Check Editor can access to page content type form editing.

  As "editor" user
  I should add, edit and delete content type "page".

@api
  Scenario: Editor role should see "page" ctypa form.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/node/add/page"
  Then I should get a 200 HTTP response

  And I should see a "#edit-title-0-value" element
  And I should see a "#edit-field-page-body-0-value" element
  And I should see a "#edit-field-has-social-share-value" element
  And the "edit-field-has-social-share-value" checkbox should be checked
  And I should see the text "Meta tag"
  And I should not see a "#edit-menu-enabled" element
  And I should see a "#edit-path-0-alias" element
