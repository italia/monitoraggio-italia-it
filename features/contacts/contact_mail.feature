Feature: Check e-mail sent.

  As anonymous
  I should send contact e-mail.

@api @email
  Scenario: Test contact mail.
    Given I log in as "Editor"
    When I go to "/contact"
    Then I should get a 200 HTTP response
    And I check "consent_data"
    And I fill in the following:
     | message[0][value] | text mail test |
    And I press the "Invia messaggio" button
    And I should not see the text "There was a problem with your form submission. Please wait 6 seconds and try again."
    And I should see the text "La tua richiesta è stata inoltrata, grazie."
    And the last email sent should have a body containing "text mail test"
    And the last email sent should have a body containing "Richiesta di informazioni"
    And the last email sent should have a body containing "E-mail: editor@dashboard.loc"
    And the last email sent should have a body containing "Nome: Editor"
