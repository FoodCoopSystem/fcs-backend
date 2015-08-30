Feature: User profile
  In order to display user specific information
  as a API client
  I need to be able to get users profile

  Scenario: Get user profile
    Given I am authenticated as "admin" and "password"
    When I send a GET request to "/profile"
    Then print response
    Then the response code should be 200
