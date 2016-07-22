Feature: Buy a ticket on Meinfernbus
  In order to buy a ticket
  As a customer
  I need to be able to search, order and check the order

  Scenario: Search Meinfernbus for the route
    Given I'm on the main page
    When I choose the Dresden-Nürnberg route
    And I pick the date of "01.08.2016"
    And I add a kid to the trip
    And I start the search for one-way ride
    And I pick the second result
    Then I can check the product is in the bag