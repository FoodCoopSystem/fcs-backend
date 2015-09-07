@database
Feature: Basket
  In order to place an order with products
  as a user
  I need to be able to manage basket

  Scenario: Successfully create basket item
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value             |
      | Name        | Coffee            |
      | Description | Delicious coffees |
      | Price       | 1.23              |
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/basket" with body:
    """
    {
      "id": "any",
      "quantity": 1,
      "product": {
        "id": {{ product.id }}
      }
    }
    """
    Then the response code should be 201
    And the JSON should match pattern:
    """
    {
      "id":@integer@,
      "quantity":1,
      "product":{
        "id":@integer@,
        "name":"Coffee"
      }
    }
    """

  Scenario: Successfully create basket item when already exists
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value             |
      | Name        | Coffee            |
      | Description | Delicious coffees |
      | Price       | 1.23              |
    And basket item with "8" products exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/basket" with body:
    """
    {
      "id": "any",
      "product": {
        "id": {{ product.id }}
      },
      "quantity": 2
    }
    """
    And print pretty response
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id":@integer@,
      "quantity":10,
      "product":{
        "id":@integer@,
        "name":"Coffee"
      }
    }
    """

  Scenario: successfully delete basket
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value             |
      | Name        | Coffee            |
      | Description | Delicious coffees |
      | Price       | 1.23              |
    And basket item with "2" products exists
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/basket/{{ basket.id }}"
    Then the response code should be 204
    And basket should not exists

  Scenario: Not found exception on delete
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/basket/0"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Basket item 0 does not exists",
      "errors": @null@
    }
    """

  Scenario: list products
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value             |
      | Name        | Coffee            |
      | Description | Delicious coffees |
      | Price       | 1.23              |
    And basket item with "2" products exists
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/basket"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "total": "1",
      "result": [
        {
          "id":@integer@,
          "quantity":2,
          "product":
          {
            "id":@integer@,
            "name":"Coffee",
            "description": "@string@",
            "price": @double@,
            "producent": {
              "name": "@string@"
            }
          }
        }
      ]
    }
    """
