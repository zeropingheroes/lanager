Feature: Attendees
    As an attendee
    I want to see who else is attending the LAN
    So I can visit their profiles

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

    Scenario: Viewing the attendee list
        Given the user "Cereal Killer" has attended the LAN "Hack the planet!"

        When I go to the homepage
        And I follow "Attendees"

        Then I should see "Cereal Killer"

