Feature: Access to page data indicators as anonymous

  As anonymous user
  I want to see detail page of "Dati degli indicatori di crescita digitale"

@api
  Scenario: Anonymous should see page detail page.
    Given I am an anonymous user
    When I go to "/area-dati/indicatori-crescita-digitale"
    Then I should get a 200 HTTP response
    And print current URL

    And I should see a ".StaticPages" element
    And I should see a ".StaticPages--table" element
    And I should see the text "Dati degli Indicatori di crescita digitale"
    And I should see the text "Descrizione"
    And I should see the text "Link"
    And I should see the text "Frequenza di aggiornamento"
    And I should see a ".DataArea--projects" element
    And I should see a ".DataArea--indicators" element
    And I should see an ".DataArea--indicators:nth-last-child(n+7)" element
