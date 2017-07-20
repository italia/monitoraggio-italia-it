Feature: Check Editor can access to configuration contact form and custom block.

  As "editor" user
  I should see form to config contact form and block.

@api
  Scenario: Editor can see contact form editor.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/config/contact-form-config"
  Then I should get a 200 HTTP response
  And I should see a "#edit-dash-contact-intro" element
  And I should see a "#edit-dash-contact-subject" element
  And I should see a "#edit-dash-contact-text" element
  And I should see a "#edit-dash-contact-recipient" element
  And I should see a "#edit-dash-contact-message" element
  And I should see a "#edit-dash-contact-message-error" element
  And I should see a "#edit-dash-contact-consent" element
  And I should see a "#edit-submit" element

@api
  Scenario: Editor can see contact block form.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/config/general-settings/contact-block"
  Then I should get a 200 HTTP response
  And I should see a "#edit-contact-bs-title" element
  And I should see a "#edit-contact-bs-text-value" element

@api
  Scenario: Anonymous can't see contact and block configuration.
  Given I am an anonymous user
  When I go to "/admin/config/general-settings/contact-block"
  Then I should get a 403 HTTP response
