Feature: Event signups
    As an attendee
    I want to sign up to events
    So I can show that I plan to attend particular events

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |
        And the following events exist:
            | name     | description       | start  | end     | signups_open | signups_close | lan              | published |
            | wipE'out | A dangerous game. | 1 hour | 2 hours | now          | 30 minutes    | Hack the planet! | yes       |

    Scenario: Creating a new event signup
        Given I am logged in as "Cereal Killer"

        When I go to the homepage
        And I follow "Events"
        And I follow "List"
        And I follow "wipE'out"
        And I press "Sign up"

        Then I should see "Cereal Killer"

