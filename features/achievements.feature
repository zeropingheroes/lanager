Feature: Achievements
    As a LAN organiser
    I want to create, edit and delete achievements
    So I can reward attendees

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

    Scenario: Creating a new achievement
        Given I am logged in as "Zero Cool"

        When I go to "/achievements"
        And I follow "Create"
        And I fill in "Name" with "Hacked The Gibson"
        And I fill in "Description" with "God wouldn't be up this late."
        And I press "Submit"

        Then I should see "Hacked The Gibson"

    Scenario: Editing an existing achievement
        Given I am logged in as "Zero Cool"
        And the following achievements exist:
            | name              | description                     | lan              |
            | Hacked The Gibson | "God" wouldn't be up this late. | Hack the planet! |

        When I go to "/achievements"
        And I follow "Hacked The Gibson"
        And I follow "Edit"
        And I fill in "Name" with "A Minor Glitch"
        And I fill in "Description" with "Rabbit, Flu-shot, someone talk to me."
        And I press "Submit"

        Then I should see "A Minor Glitch"

    Scenario: Viewing the achievement list
        Given the following achievements exist:
            | name              | description                           | lan              |
            | Hacked The Gibson | "God" wouldn't be up this late.       | Hack the planet! |
            | A Minor Glitch    | Rabbit, Flu-shot, someone talk to me. | Hack the planet! |

        When I go to "/achievements"

        Then I should see "Hacked The Gibson"
        And I should see "A Minor Glitch"
