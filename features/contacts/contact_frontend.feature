Feature: Check Anonymous user see contact form.

  As anonymous user
  I should see contact form.

@api
  Scenario: Anonymous can see contact form.
  Given I am an anonymous user
  When I go to "/contact"
  Then I should get a 200 HTTP response
  And I should see a ".contact-message-contact-us-form #edit-name" element
  And I should see a ".contact-message-contact-us-form #edit-mail" element
  And I should see a ".contact-message-contact-us-form .form-textarea.Form-input.Form-textarea" element
  And I should see a ".contact-message-contact-us-form #edit-consent-data" element
  And I should see a ".contact-message-contact-us-form #edit-submit" element
  And I should see the text "Contattaci"
  And I should see a ".contact-message-contact-us-form .captcha" element
  Then check dashboard contact form interest area select

@api
  Scenario: Anonymous can see contact form.
  Given I am an anonymous user
  And I am on the homepage
  Then I should get a 200 HTTP response
  And I should see a "#block-contactblock" element
