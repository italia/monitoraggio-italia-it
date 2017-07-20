Feature: Check users roles.

  As "editor" user
  I should have specific permissions.

  @api
  Scenario: Editor role should see toolbar and specific elements inside.
  Given I am logged in dashboard as a user with the "editor" role
  Then I should see the text "Gestisci i progetti"
  Then I should see the text "Gestisci le pagine statiche"
  And I should see a ".dashboard-backend" element
  And print current URL
  When I go to homepage
  Then I should get a 200 HTTP response
  And I should see a "#toolbar-bar" element
  And I should see "Vedi il profilo" in the "#toolbar-item-user-tray .toolbar-menu .account" element
  And I should see "Modifica il profilo" in the "#toolbar-item-user-tray .toolbar-menu .account-edit" element
  And I should see "Esci" in the "#toolbar-item-user-tray .toolbar-menu .logout" element

  And I should see "Gestisci Dashboard" in the "#toolbar-bar" element
  And I should see "Panoramica dei contenuti" in the "#toolbar-bar" element
  And I should see "Impostazioni generali" in the "#toolbar-bar" element
  And I should see "Configurazione per la pagina contatti" in the "#toolbar-bar" element
  And I should see "Configurazioni per cookie banner" in the "#toolbar-bar" element

  And I should not see a "#toolbar-item-administration" element

  @api
  Scenario: Editor role should see administration views.
  Given I am logged in dashboard as a user with the "editor" role
  When I go to "/admin/dashboard-content/projects"
  Then I should get a 200 HTTP response
  And I should see "Gestisci i progetti" in the ".page-title" element
  And I should see a ".views-field-field-monitor-logo" element
  And I should see a ".views-field-name" element
  And I should see a ".views-field-field-monitor-indicator" element
  And I should see a ".views-field-changed" element
  And I should see a ".views-field-view-project" element
  And I should see a ".views-field-operations" element

  When I go to "/admin/dashboard-content/static-pages"
  Then I should get a 200 HTTP response
  And I should see "Gestisci le pagine statiche" in the ".page-title" element
  And I should see a ".views-field-title" element
  And I should see a ".views-field-changed" element
  And I should see a ".views-field-view-node" element
  And I should see a ".views-field-operations" element
