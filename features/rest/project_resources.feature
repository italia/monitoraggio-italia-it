Feature: Check Anonymous users can access project category REST Resource.

  As "anonymous" user
  I should have access to the project category REST Resource.

@api
  Scenario: Anonymous user should access the project category REST Resource.
  Given I am an anonymous user
  When I go to "/v1/category/pagopa?_format=json"
  Then I should get a 200 HTTP response
