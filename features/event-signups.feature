Feature: Event signups
    In order to show my intent to participate in an event
    As an attendee
    I need to be able to sign up to events

    Background:
        Given an admin with username "Zero Cool" exists
        Given a user with username "Cereal Killer" exists
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        Given the following LAN exists:
            | name             | description            | start      | end     | venue      | published |
            | Hack the planet! | This is our world now. | 1 hour ago | 2 hours | Cyberdelia | yes       |

    Scenario: When a user signs up to an event which is open for signups, their username appears on the event page
        Given the following event exists:
            | name     | description       | start  | end     | signups_open | signups_close | lan              | published |
            | wipE'out | A dangerous game. | 1 hour | 2 hours | now          | 30 minutes    | Hack the planet! | yes       |
        And I am logged in as "Cereal Killer"
        When I go to the homepage
        And I follow "Events"
        And I follow "List"
        And I follow "wipE'out"
        And I press "Sign up"
        Then I should see "Cereal Killer"

    Scenario: When a user removes their signup from an event which is open for signups, they do not show on the event page

    Scenario: When a user attempts to sign up for an event which does not allow signups, they see an error

    Scenario: When a user attempts to sign up for an event which is not yet open for signups, they see an error

    Scenario: When a user attempts to sign up for an event which is no longer open for signups, they see an error

    Scenario: When a user attempts to delete another user's signup, they are not permitted
