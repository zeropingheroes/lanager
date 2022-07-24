Feature: Users
    As an attendee
    I want to view other attendee profiles
    So I can find out about them

    Background:
        Given an admin with username "Zero Cool" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |
        And the user "Zero Cool" has attended the LAN "Hack the planet!"

    Scenario: Viewing a user profile
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Profile"

        Then I should see "Hack the planet!"
