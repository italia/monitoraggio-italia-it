Feature: Check Anonymous user see homepage.

  As anonymous user
  I should see homepage elements.

@api
  Scenario: Anonymous can see homepage elements.
  Given I am an anonymous user
  When I go to "/"
  Then I should get a 200 HTTP response
  And I follow "Esplora"
  Then I should get a 200 HTTP response
  And print current URL

  And I should see a "#block-project-left-menu" element
  And I should see a ".MonitoringProject" element
  And I should see a ".MonitoringProject h1.MonitoringProject-name" element
  And I should see a ".MonitoringProject .MonitoringProject-description" element
  And I should see a ".MonitoringProject .MonitoringProject-indicators:nth-last-child(n+1).Accordion" element
  And I should see a ".MonitoringProject .MonitoringProject-indicators:nth-last-child(n+1) .MonitoringIndicator-header" element
  And I should see a ".MonitoringProject .MonitoringProject-indicators:nth-last-child(n+1) .Accordion-panel" element
  And I should see a ".MonitoringProject .MonitoringProject-indicators:nth-last-child(n+1) .Accordion-panel .MonitoringIndicator" element
  And I should see a ".MonitoringProject .MonitoringProject-indicators:nth-last-child(n+1) .ChartBox--actualValue:nth-last-child(n+1)" element
  And I should see a ".MonitoringProject--withShare .DashboardShare-at-top .DashboardShare" element
  And I should see a ".MonitoringProject--withShare .DashboardShare-at-bottom .DashboardShare" element
  And I should see a ".MonitoringProject--withShare .DashboardShare .Icon-facebook" element
  And I should see a ".MonitoringProject--withShare .DashboardShare .Icon-twitter" element
  And I should see a ".MonitoringProject--withShare .DashboardShare .Icon-linkedin" element
  And I should not see a ".MonitoringProject--withoutShare .DashboardShare" element
  And I should see a ".MonitoringProject-datalink" element
