Feature: Check taxonomies for site

  As administrator user
  I should see taxonomies of dashboard.

  @api
  Scenario: Administrator should see taxonomies and edit them.
  Given I am logged in dashboard as a user with the "administrator" role
  When I go to "admin/structure/taxonomy"
  Then I should get a 200 HTTP response
  And I should see the text "Mesi"
  And I should see the text "Anni"

  When I go to "admin/structure/taxonomy/manage/months/overview"
  Then I should get a 200 HTTP response
  And I should see the text "Gennaio"

  When I go to "admin/structure/taxonomy/manage/years/overview"
  Then I should get a 200 HTTP response
  And I should see the text "2013"

  @api
  Scenario: Anonymous should not see pages for taxonomies.
  Given I am an anonymous user
  When I go to "admin/structure/taxonomy"
  Then I should get a 403 HTTP response

