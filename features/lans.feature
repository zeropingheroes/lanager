Feature: LANs
    In order to group information specific to a LAN together
    As a LAN organiser
    I need to be able to create, edit and delete LANs

    Background:
        Given an admin with username "Cereal Killer" exists
        Given I am logged in as "Cereal Killer"
        Given the following venue exists:
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

    Scenario: Editing an existing LAN's details
        Given the following LAN exists:
            | name             | description            | start               | end                 | venue      | published |
            | Hack the planet! | This is our world now. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "Edit"
        And I fill in "Name" with "Hack the Gibson"
        And I fill in "Description" with "A seriously righteous hack."
        And I fill in "Start" with "2025-08-10 18:00:00"
        And I fill in "End" with "2025-08-12 18:00:00"
        And I press "Submit"
        And I go to "/lans"

        Then I should see "Hack the Gibson"
        And I should see "August 2025"

    Scenario: A guest visiting the LANs index page should not see an unpublished LAN
        Given the following LAN exists:
            | name            | description                 | start               | end                 | venue      | published |
            | Hack the Gibson | A seriously righteous hack. | 2025-08-10 18:00:00 | 2025-08-12 18:00:00 | Cyberdelia | no        |

        When I go to the homepage
        And I log out
        And I go to "/lans"

        Then I should not see "Hack the Gibson"

    Scenario: A super admin visiting the LANs index page should see an unpublished LAN
        Given the following LAN exists:
            | name            | description                 | start               | end                 | venue      | published |
            | Hack the Gibson | A seriously righteous hack. | 2025-08-10 18:00:00 | 2025-08-12 18:00:00 | Cyberdelia | no        |

        When I go to "/lans"

        Then I should see "Hack the Gibson"

    Scenario: Attempting to create a LAN which has an "end" before its "start" should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Create"
        And I fill in "Name" with "Hack the Gibson"
        And I fill in "Description" with "A seriously righteous hack."
        And I fill in "Start" with "2025-09-17 18:00:00"
        And I fill in "End" with "2025-09-15 18:00:00"
        And I press "Submit"

        Then I should see "The start must be a date before end"

    Scenario: Attempting to create a LAN which overlaps with an existing LAN should show an error
        Given the following LAN exists:
            | name            | description                 | start               | end                 | venue      | published |
            | Hack the Gibson | A seriously righteous hack. | 2025-08-10 18:00:00 | 2025-08-12 18:00:00 | Cyberdelia | no        |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Create"
        And I fill in "Name" with "Hack the planet!"
        And I fill in "Start" with "2025-08-10 19:00:00"
        And I fill in "End" with "2025-08-15 18:00:00"
        And I press "Submit"

        Then I should see "LANs cannot overlap"

#    TODO
#    Scenario: Attempting to create a LAN with a venue that doesn't exist should show an error
#
#    Scenario: Attempting to create a LAN with an achievement that doesn't exist should show an error
#
#    Scenario: Attempting to create a LAN with a name that is too long should show an error
