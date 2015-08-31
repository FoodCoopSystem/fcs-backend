@database
Feature: User profile
  In order to display user specific information
  as a API client
  I need to be able to get users profile

  Scenario: Get user profile
    Given User "admin" exists with:
      | Property  | Value           |
      | FirstName | Marcin          |
      | LastName  | Dryka           |
      | Email     | marcin@dryka.pl |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I send a GET request to "/profile"
    Then the response code should be 200
    And print pretty response
    And the JSON should match pattern:
    """
    {
      "first_name":"Marcin",
      "last_name":"Dryka",
      "email":"@string@.isEmail()",
      "roles":"@array@.inArray('ROLE_ADMIN')"
    }
    """
