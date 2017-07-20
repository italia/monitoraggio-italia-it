Feature: Check Anonymous users can access rest-elements resource.

  As "anonymous" user
  I should have access rest-elements resource.

@api
  Scenario: Anonymous user should access rest-elements resource.
  Given I am an anonymous user
  When I go to "/v1/rest-elements?_format=json"
  Then I should get a 200 HTTP response
