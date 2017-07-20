Feature: Basic check for translation.

  As anonymous user
  I should see dashboard with ITA language.

  @api @translation
  Scenario: Anonymous should see login page in italian.
  Given I am an anonymous user
  When I go to "/it/dashboard/login"
  Then I should get a 200 HTTP response
  And print current URL
  And I should see the text "Accedi"
  And I should see the text "Nome utente"
  And I should see the link "Reimposta la tua password"

  When I go to "/en/dashboard/login"
  Then I should get a 200 HTTP response
  And print current URL
  And I should see the text "Log in"
  And I should see the text "Username"
  And I should see the link "Reset your password"

  @api @translation
  Scenario: Anonymous should see custom string translated.
  Given I am an anonymous user
  And I am on the homepage
  And I should see the text "Esplora"
  When I go to "/it/progetto/fascicolo-sanitario-elettronico"
  And I should see the text "Visualizza i dati di FASCICOLO SANITARIO ELETTRONICO in formato JSON"
