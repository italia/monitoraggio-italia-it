Feature: Check menus for site

  As administrator user
  I should see menus of dashboard.

  @api
  Scenario: Administrator should see menus and edit them.
  Given I am logged in dashboard as a user with the "administrator" role
  When I go to "admin/structure/menu"
  Then I should get a 200 HTTP response
  And I should see an "li.edit:nth-last-child(n+1)" element

  When I go to "admin/structure/menu/manage/footer"
  Then I should get a 200 HTTP response
  And I should see an "li.edit:nth-last-child(n+1)" element

  When I go to "admin/structure/menu/manage/main"
  Then I should get a 200 HTTP response
  And I should see an "li.edit:nth-last-child(n+1)" element

  When I go to "admin/structure/menu/manage/site-map"
  Then I should get a 200 HTTP response
  And I should see an "li.edit:nth-last-child(n+1)" element

  When I go to "admin/structure/menu/manage/menu-projects"
  Then I should get a 200 HTTP response
  And I should see an "li.edit:nth-last-child(n+1)" element
