Feature: Venues
    In order to inform attendees where a LAN is taking place
    As an admin
    I need to be able to create, edit and delete venues

    Background:
        Given an admin with username "Lord Nikon" exists

    Scenario: Creating a new venue for all to see
        Given I am logged in as "Lord Nikon"

        When I go to "venues/create"
        And I fill in "Name" with "Cyberdelia"
        And I fill in "Street Address" with "Clifden Road, Brentford, Greater London"
        And I fill in "Description" with "The ultimate cyberpunk club lounge"
        And I press "Submit"
        And I log out

        Then I go to "venues"
        And I should see "Cyberdelia"
