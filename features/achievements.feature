Feature:
    In order to encourage actions, behaviour or participation from attendees
    As a LAN organiser
    I need to be able to create and award achievements

    Background:
        Given an admin with username "Zero Cool" exists
        Given I am logged in as "Zero Cool"
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        Given the following LAN exists:
            | name             | description            | start      | end     | venue      | published |
            | Hack the planet! | This is our world now. | 1 hour ago | 2 hours | Cyberdelia | yes       |

    Scenario: Creating an achievement

        When I go to the homepage
        And I follow "Achievements"
        And I follow "Create"
        And I fill in "Name" with "Hacked The Gibson"
        And I fill in "Description" with "\"God\" wouldn't be up this late."
        And I press "Submit"

        Then I should see "Hacked The Gibson"

    Scenario: Editing an achievement

        Given the following achievements exist:
            | name              | description                     | lan              |
            | Hacked The Gibson | "God" wouldn't be up this late. | Hack the planet! |

        When I go to the homepage
        And I follow "Achievements"
        And I follow "Hacked The Gibson"
        And I follow "Edit"
        And I fill in "Name" with "A Minor Glitch"
        And I fill in "Description" with "Rabbit, Flu-shot, someone talk to me."
        And I press "Submit"

        Then I should see "A Minor Glitch"

#    TODO: Requires Selenium due to Javascript being used for "delete" button
#    Scenario: Deleting an achievement
#
#    TODO: Feature currently broken
#    Scenario: Awarding an achievement
#         Given the following achievements exist:
#             | name              | description                     | lan              |
#            | Hacked The Gibson | "God" wouldn't be up this late. | Hack the planet! |
#
#    TODO: Feature currently broken
#    Scenario: Revoking an achievement
