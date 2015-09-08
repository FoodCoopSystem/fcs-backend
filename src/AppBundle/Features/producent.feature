@database
Feature: Producent
  In order to categorize product for regular users and
  organize product suppling
  as a admin
  I need to be able to CRUD suppliers

  Scenario: Successfully create producent
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/producent/" with body:
    """
    {
      "id": "any",
      "name": "Coffee supplier"
    }
    """
    Then the response code should be 201
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "@string@"
    }
    """

  Scenario: Ineffective producent creation
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/producent/" with body:
    """
    {
      "id": null,
      "name": null
    }
    """
    Then the response code should be 400
    And the JSON should match pattern:
    """
    {
      "code": 400,
      "message": "Validation Failed",
      "errors": {
        "children": {
          "id": [],
          "name": {
            "errors": [
              "This value should not be blank."
            ]
          }
        }
      }
    }
    """

  Scenario: Successfully shows the producent
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/producent/{{ producent.id }}"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "Coffee supplier"
    }
    """

  Scenario: Not found exception on view
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/producent/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Producent ABC does not exists",
      "errors": @null@
    }
    """

  Scenario: Update producent
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/producent/{{ producent.id }}" with body:
    """
    {
      "id": "any",
      "name": "Better coffee supplier"
    }
    """
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "Better coffee supplier"
    }
    """

  Scenario: Not found exception on update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/producent/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Producent ABC does not exists",
      "errors": @null@
    }
    """

  Scenario: Ineffective producent update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/producent/{{ producent.id }}" with body:
    """
    {
      "id": null,
      "name": null
    }
    """
    Then the response code should be 400
    And the JSON should match pattern:
    """
    {
      "code": 400,
      "message": "Validation Failed",
      "errors": {
        "children": {
          "id": [],
          "name": {
            "errors": [
              "This value should not be blank."
            ]
          }
        }
      }
    }
    """

  Scenario: successfully delete producent
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/producent/{{ producent.id }}"
    Then the response code should be 204
    And producent should not exists

  Scenario: Not found exception on delete
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/producent/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Producent ABC does not exists",
      "errors": @null@
    }
    """

  Scenario: list products
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/producent/"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "total": "1",
      "result": [
        {
          "id": @integer@,
          "name": "Coffee supplier"
        }
      ]
    }
    """
