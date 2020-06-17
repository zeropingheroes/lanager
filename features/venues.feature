Feature: Venues
    In order to inform attendees where a LAN is taking place
    As an admin
    I need to be able to create, edit and delete venues

    Background:
        Given an admin with username "Lord Nikon" exists

    Scenario: Creating a new venue for all to see
        Given I am logged in as "Lord Nikon"

        When I visit "venues/create"
        And I type "Cyberdelia" into the "Name" field
        And I type "Clifden Road, Brentford, Greater London" into the "Street Address" field
        And I type "The ultimate cyberpunk club lounge" into the "Description" field
        And I submit the form
        And I log out

        Then I visit "venues"
        And I should see the text "Cyberdelia"
