@database
Feature: Nearest order
  In order to place an order from supplier
  as a admin
  I need to see nearest order items

  Scenario: List order items
    Given User "admin" exists with:
      | Property  | Value           |
      | Roles     | ROLE_ADMIN      |
    And I am authenticated as "admin"
    And active order on "2015-09-01" exists
    And producent "Supplier" exists with product:
      | Name  | My product |
      | Price | 12.22      |
    And in order there is item with 3 products
    When I set header "Content-Type" with value "application/json"
    And I send a GET request to "/order/current"
    Then the response code should be 200
    And the JSON should match pattern:
    """
    {
        "total": "1",
        "result": [
            {
                "quantity": 3,
                "owner": {
                    "first_name": @null@,
                    "last_name": @null@
                },
                "product": {
                    "name": "My product",
                    "price": 12.22,
                    "producent": {
                        "name": "Supplier"
                    }
                }
            }
        ]
    }
    """
