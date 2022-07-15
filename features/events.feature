Feature: Events
    As an organiser
    I want to create, edit and delete events
    So attendees can find about what events are scheduled for the LAN

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

    Scenario: Creating a new event
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-15 21:00:00"
        And I check "Published"
        And I press "Submit"

        Then I should see "wipE'out"
        And I should see "A dangerous game."

    Scenario: Editing an existing event
        Given I am logged in as "Zero Cool"
        And the following events exist:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-15 20:00:00 | Hack the planet! | yes       |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "wipE'out"
        And I follow "Edit"
        And I fill in "Name" with "Pac-Man"
        And I fill in "Description" with "A labyrinth of fun & amusement!"
        And I fill in "Start" with "2025-09-15 20:00:00"
        And I fill in "End" with "2025-09-15 22:00:00"
        And I check "Published"
        And I press "Submit"

        Then I should see "Pac-Man"
        And I should see "A labyrinth of fun & amusement!"

    Scenario: Viewing the event list
        Given the following events exist:
            | name     | description                     | start               | end                 | lan              | published |
            | wipE'out | A dangerous game.               | 2025-09-15 18:00:00 | 2025-09-15 20:00:00 | Hack the planet! | yes       |
            | Pac-Man  | A labyrinth of fun & amusement! | 2025-09-15 20:00:00 | 2025-09-15 22:00:00 | Hack the planet! | yes       |

        When I go to the homepage
        And I follow "List"

        Then I should see "wipE'out"
