@database
Feature: Orders
  In order let others to place orders
  as a admin
  I need to be able to CRUD orders (schedule)

  Scenario: Successfully create order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders" with body:
    """
    {
      "id": "any",
      "executionAt": "2015-09-01"
    }
    """
    Then the response code should be 201
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "execution_at": "2015-09-01"
    }
    """

  Scenario: Ineffective order creation
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders" with body:
    """
    {
      "id": null,
      "executionAt": null
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
          "executionAt": {
            "errors": [
              "This value should not be blank."
            ]
          }
        }
      }
    }
    """

  @wip
  Scenario: Successfully shows the order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value            |
      | Name        | Coffee           |
      | Description | Delicious coffee |
      | Price       | 1.23             |
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/product/{{ product.id }}"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "Coffee",
      "description": "Delicious coffee",
      "price": 1.23,
      "producent": {
        "id": @integer@,
        "name": "Coffee supplier"
      }
    }
    """

  @wip
  Scenario: Not found exception on view
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/product/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Product ABC does not exists",
      "errors": @null@
    }
    """

  @wip
  Scenario: Update product
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value            |
      | Name        | Coffee           |
      | Description | Delicious coffee |
      | Price       | 1.23             |
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/product/{{ product.id }}" with body:
    """
    {
      "id": "any",
      "name": "Better coffee",
      "description": "A really good one!",
      "price": 2.34,
      "producent": {
        "id": {{ producent.id }},
        "name": "Coffee supplier"
      }
    }
    """
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "name": "Better coffee",
      "description": "A really good one!",
      "price": 2.34,
      "producent": {
        "id": @integer@,
        "name": "Coffee supplier"
      }
    }
    """

  @wip
  Scenario: Not found exception on update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/product/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Product ABC does not exists",
      "errors": @null@
    }
    """

  @wip
  Scenario: Ineffective product update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And producent "Coffee supplier" exists with product:
      | Property    | Value            |
      | Name        | Coffee           |
      | Description | Delicious coffee |
      | Price       | 1.23             |
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/product/{{ product.id }}" with body:
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

  @wip
  Scenario: successfully delete product
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
    And I send a DELETE request to "/product/{{ product.id }}"
    Then the response code should be 204
    And product should not exists

  @wip
  Scenario: Not found exception on delete
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/product/ABC"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Product ABC does not exists",
      "errors": @null@
    }
    """

  @wip
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
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/product"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "total": "1",
      "result": [
        {
          "id": @integer@,
          "name": "Coffee",
          "description": "Delicious coffees",
          "price": 1.23,
          "producent": {
              "name": "Coffee supplier"
          }
        }
      ]
    }
    """