@database
Feature: Product
  In order to place an order with products
  as a admin
  I need to be able to CRUD products

  Scenario: Successfully create product
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And prducent "Coffee supplier" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/product" with body:
    """
    {
      "id": "any",
      "name": "Coffee",
      "description": "Wonderful coffee",
      "price": 22.33,
      "producent": {
        "id": {{ producent.id }}
      }
    }
    """
    Then the response code should be 201
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "@string@",
      "description": "@string@",
      "price": @double@,
      "producent": {
          "id": @integer@,
          "name": "@string@"
      }
    }
    """

  Scenario: Ineffective product creation
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/product" with body:
    """
    {
      "id": null,
      "name": null,
      "description": null,
      "price": null,
      "producent": null
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
                },
                "description": [],
                "price": {
                    "errors": [
                        "This value should not be blank."
                    ]
                },
                "producent": {
                    "errors": [
                        "This value should not be blank."
                    ]
                }
            }
        }
    }
    """
