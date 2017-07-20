Feature: Check Editr can access to entity project monitoring.

  As "editor" user
  I should add, edit and delete entity Project bundle monitoring.

@api
  Scenario: Editor role should see list of entity projects elements.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/structure/project"
  Then I should get a 200 HTTP response
  And I should see the text "Project ID"
  And I should see the text "Identificativo"
  And I should see the text "Titolo"
  And I should see the text "Operazioni"
  And I should see the link "Modifica"

  When I follow "Aggiungi progetto"
  Then I should get a 200 HTTP response

@api
  Scenario: Editor role should see form to add entity project.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/structure/project/add"
  Then I should get a 200 HTTP response
  And I should see the text "Dati editoriali"
  And I should see the text "Nome"
  And I should see the text "Chiave"
  And I should see the text "Sottotitolo"
  And I should see the text "Descrizione"
  And I should see the text "Logo"
  And I should see the text "Link"
  And I should see the text "Dati analitici"
  And I should see the text "Peso"
  And I should see the text "Indicators"
  And I should see the text "Crea Monitoring indicator"
  And I should see the text "Metadata"
  And I should see a "#edit-field-has-social-share-value" element
  And I should see a "#edit-field-monitor-hp-on-value" element
  And the "edit-field-has-social-share-value" checkbox should be checked
  And I should see a "#edit-path-0-pathauto" element

@api
  Scenario: Editor can see DESI settings form.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/config/general-settings/desi"
  Then I should get a 200 HTTP response
  And I should see a "#edit-desi-link-label" element
  And I should see a "#edit-desi-link-path" element

@api
  Scenario: Anonymous can't see DESI settings form.
  Given I am an anonymous user
  When I go to "/admin/config/general-settings/desi"
  Then I should get a 403 HTTP response
