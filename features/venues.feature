Feature: Venues
    In order to inform attendees where a LAN is taking place
    As an admin
    I need to be able to create, edit and delete venues

    Background:
        Given an admin with username "Lord Nikon" exists
        Given I am logged in as "Lord Nikon"

    Scenario: Creating a new venue for all to see
        When I go to "venues/create"
        Then print last response
        And I fill in "Name" with "Cyberdelia"
        And I fill in "Street Address" with "Clifden Road, Brentford, Greater London"
        And I fill in "Description" with "The ultimate cyberpunk club lounge"
        And I press "Submit"
        And I log out

        Then I go to "venues"
        And I should see "Cyberdelia"

    Scenario: Editing an existing venue's name
        When I go to "venues"
        And I follow "Edit"
        And I fill in "Name" with "Ellingson Mineral Corporation HQ"
        And I fill in "Street Address" with "Lloyd's building, Lime Street, London, EC3M 7AW"
        And I fill in "Description" with "Together, Anything is Possible"
        And I press "Submit"
        And I log out

        Then I go to "venues"
        And I should see "Ellingson Mineral Corporation HQ"
        And I should see "Lloyd's building, Lime Street, London, EC3M 7AW"
