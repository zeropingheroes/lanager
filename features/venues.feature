Feature: Venues
    In order to inform attendees where a LAN is taking place
    As an admin
    I need to be able to create, edit and delete venues

    Background:
        Given an admin with username "Lord Nikon" exists
        Given I am logged in as "Lord Nikon"

    Scenario: Creating a new venue
        When I go to the homepage
        And I follow "Venues"
        And I follow "Create"
        And I fill in "Name" with "Cyberdelia"
        And I fill in "Street Address" with "Clifden Road, Brentford, Greater London"
        And I fill in "Description" with "The ultimate cyberpunk club lounge"
        And I press "Submit"

        Then I should see "Cyberdelia"
        And I should see "Clifden Road, Brentford, Greater London"

    Scenario: Editing an existing venue's details
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |

        When I go to the homepage
        And I follow "Venues"
        And I follow "Cyberdelia"
        And I follow "Edit"
        And I fill in "Name" with "Ellingson Mineral Corporation HQ"
        And I fill in "Street Address" with "Lloyd's building, Lime Street, London, EC3M 7AW"
        And I fill in "Description" with "Together, Anything is Possible"
        And I press "Submit"

        Then I should see "Ellingson Mineral Corporation HQ"
        And I should see "Lloyd's building, Lime Street, London, EC3M 7AW"

#    Scenario: Deleting an existing venue
#        Given the following venue exists:
#            | name       | street_address                          | description                        |
#            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
#
#        When I go to the homepage
#        And I follow "Venues"
#        And I follow "Cyberdelia"
#        And I follow "Delete" - currently uses javascript so cannot be tested by Goutte
#
#        Then the url should match "/venues"
#        And I should not see "Cyberdelia"

    Scenario: Attempting to create a venue with no name or street address should show an error
        When I go to the homepage
        And I follow "Venues"
        And I follow "Create"
        And I press "Submit"

        Then I should see "The name field is required"
        And I should see "The street address field is required"

# TODO:
# Scenario: Attempting to create a venue with a name and street address that are too long should show an error
