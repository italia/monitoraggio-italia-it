Feature: Check Anonymous user see homepage.

  As anonymous user
  I should see homepage elements.

@api
  Scenario: Anonymous can see homepage elements.
  Given I am an anonymous user
  When I go to "/"
  Then I should get a 200 HTTP response
  And I should see a ".u-cf .js-Masonry-container" element

  And I should see an ".Masonry-item:nth-last-child(n+7)" element
  And I should see an ".Masonry-item:nth-last-child(n+7) .ProjectCard" element
  And I should see an ".Masonry-item:nth-last-child(n+7) .ProjectCard-header" element
  And I should see an ".Masonry-item:nth-last-child(n+7) .ProjectCard-link" element
