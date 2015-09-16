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
    And I send a POST request to "/orders/" with body:
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
    And I send a POST request to "/orders/" with body:
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

  Scenario: Successfully shows the order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/orders/{{ order.id }}"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "execution_at": "2015-09-01"
    }
    """

  Scenario: Not found exception on view
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/orders/0"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Order does not exists",
      "errors": @null@
    }
    """

  Scenario: Update order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders/{{ order.id }}" with body:
    """
    {
      "id": "any",
      "executionAt": "2015-10-01"
    }
    """
    And print pretty response
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "id": @integer@,
      "execution_at": "2015-10-01"
    }
    """

  Scenario: Not found exception on update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders/0"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Order does not exists",
      "errors": @null@
    }
    """

  Scenario: Ineffective order update
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders/{{ order.id }}" with body:
    """
    {
      "id": null,
      "executionAt": null
    }
    """
    And print pretty response
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

  Scenario: successfully delete order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/orders/{{ order.id }}"
    Then the response code should be 204
    And order should not exists

  Scenario: Not found exception on delete
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    When I set header "Content-Type" with value "application/json"
    And I send a DELETE request to "/orders/0"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Order does not exists",
      "errors": @null@
    }
    """

  Scenario: list orders
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/orders/"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
      "total": "1",
      "result": [
        {
          "id": @integer@,
          "execution_at": "2015-09-01",
          "active": "@boolean@"
        }
      ]
    }
    """

  Scenario: successfully delete order
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders/{{ order.id }}/activate"
    Then the response code should be 204
    And order should be active


  Scenario: not found order on activate
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And order on "2015-09-01" exists
    When I set header "Content-Type" with value "application/json"
    And I send a POST request to "/orders/0/activate"
    Then the response code should be 404
    And the JSON should match pattern:
    """
    {
      "code": 404,
      "message": "Order does not exists",
      "errors": @null@
    }
    """
