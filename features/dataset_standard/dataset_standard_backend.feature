Feature: Check Editor can access to entity dataset standard.

  As "editor" user
  I should add dataset standard.

@api
  Scenario: Editor role should not see dataset list.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/structure/dataset"
  Then I should get a 403 HTTP response

@api
  Scenario: Editor role should see form to add entity dataset.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/structure/dataset/add"
  Then I should get a 200 HTTP response
  And I should see the text "Chiave"
  And I should see the text "Nome"
  And I should see the text "Description"
  And I should see the text "Data"
  And I should see the text "Aggiungi un nuovo "
  And I should see the text "Widget"
