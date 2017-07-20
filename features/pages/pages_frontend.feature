Feature: Access to page detail as anonymous

  As anonymous user
  I want to see detail page of page content type.

  @api
  Scenario: Anonymous should see page detail page with social share buttons.
    Given I am an anonymous user
    And I am viewing an "page" content:
      | title                   | The quick, brown fox jumps over a lazy dog |
      | field_page_body         | DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz, vex nymphs. |
      | field_has_social_share  | 1   |
      | nid                     | 666 |
    Then I should get a 200 HTTP response
    And print current URL

    And I should see the heading "The quick, brown fox jumps over a lazy dog"
    And I should see the text "DJs flock by when MTV ax quiz prog"
    And I should see a "article.Prose.u-layout-prose" element
    And I should see a ".StaticPages--withShare .DashboardShare-at-top .DashboardShare" element
    And I should not see a ".StaticPages--withShare .DashboardShare-at-bottom .DashboardShare" element
    And I should see a ".StaticPages--withShare .DashboardShare .Icon-facebook" element
    And I should see a ".StaticPages--withShare .DashboardShare .Icon-twitter" element
    And I should see a ".StaticPages--withShare .DashboardShare .Icon-linkedin" element
    And I should not see a ".StaticPages--withoutShare .DashboardShare" element

    And the url should match "/quick-brown-fox-jumps-over-lazy-dog"
