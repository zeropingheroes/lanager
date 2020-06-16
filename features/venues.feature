Feature: Venues
    In order to inform attendees where a LAN is taking place
    As an admin
    I need to be able to create, edit and delete venues

    Background:
        Given an admin with username "Lord Nikon" exists

    Scenario: Creating a new venue for all to see
        Given I am logged in as "Lord Nikon"
        When I create the venue:
            | name       | street address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And I log out
        Then I visit the path "venues"
        And I should see the text "Cyberdelia"
