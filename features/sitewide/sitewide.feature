Feature: frontend sitewide elements

  As a anonymous user
  I want to test if there are frontend theme elements.

@api
  Scenario: Test anonymous can see header and footer elements.
    Given I am an anonymous user
    And I am on the homepage
    Then I should get a 200 HTTP response
    # Header.
    Then I should see a ".Header .Header-banner .Header-owner" element
    Then I should see a ".Header .Header-logo.Grid-cell" element
    # Logo.
    Then the ".Header .Header-logo.Grid-cell" element should contain "/themes/custom/dashboard/logo.svg"
    Then I should see a ".Header .Header-title.Grid-cell .Header-titleLink" element
    # MegaMenu.
    Then I should see a ".Offcanvas .Offcanvas-content nav .Linklist" element
    Then I should see a ".Hamburger-toggleContainer" element
    # Footer.
    Then I should see a ".Footer" element
    Then I should see a ".Footer .Footer-logo" element
    Then I should see a ".Footer .Footer-siteName" element
    Then I should see a ".Footer .Footer-block" element
    Then I should see a ".Footer .Footer-block .Footer-blockTitle" element
    Then I should see a ".Footer .Footer-block .Footer-subBlock .Footer-socialIcons" element
    Then I should see a ".Footer .Footer-links" element

    # Footer elements
    Then I should see a ".Footer #block-contactblock" element
    Then I should see "Contatti" in the ".Footer #block-contactblock h2" element

    #Social items
    Then I should see 4 ".Footer-socialIcons .Icon" elements
    Then I should see a ".Footer-socialIcons .Icon-facebook" element
    Then I should see a ".Footer-socialIcons .Icon-twitter" element
    Then I should see a ".Footer-socialIcons .Icon-youtube" element
    Then I should see a ".Footer-socialIcons .Icon-medium" element

@api
  Scenario: Test anonymous can see 404 page.
    Given I am an anonymous user
    When I go to "/page-not-real-only-to-test-404-page"
    Then I should get a 404 HTTP response
    # 404 Page.
    Then I should see a ".ErrorPage" element
    Then I should see "404" in the ".ErrorPage-title" element
    Then I should see a ".ErrorPage .ErrorPage-subtitle" element
