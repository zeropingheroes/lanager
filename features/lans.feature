Feature: LANs
    In order to group information specific to a LAN together
    As an admin
    I need to be able to create, edit and delete LANs

    Background:
        Given an admin with username "Cereal Killer" exists
        Given I am logged in as "Cereal Killer"
        Given The following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |

    Scenario: Creating a new LAN
        When I go to the homepage
        And I follow "LANs"
        And I follow "Create"
        And I fill in "Name" with "Hack the planet!"
        And I fill in "Description" with "This is our world now. The world of the electron and the switch, the beauty of the baud."
        And I fill in "Start" with "2025-09-15 18:00:00"
        And I fill in "End" with "2025-09-17 18:00:00"
        And I select "Cyberdelia" from "Venue"
        And I check "Published"
        And I press "Submit"
        And I go to "/lans"

        Then I should see "Hack the planet!"
        And I should see "September 2025"
