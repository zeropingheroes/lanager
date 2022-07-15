Feature: LANs
    As an organiser
    I want to create, edit and delete LANs
    So information related to a LAN is grouped together

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |

    Scenario: Creating a new LAN
        Given I am logged in as "Zero Cool"

        When I go to "/lans/create"
        And I fill in "Name" with "Hack the planet!"
        And I fill in "Start" with "2025-09-15 18:00:00"
        And I fill in "End" with "2025-09-17 18:00:00"
        And I select "Cyberdelia" from "Venue"
        And I check "Published"
        And I press "Submit"
        And I go to "/lans"

        Then I should see "Hack the planet!"
        And I should see "September 2025"

    Scenario: Editing an existing LAN
        Given I am logged in as "Zero Cool"
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

        When I go to "/lans"
        And I follow "Hack the planet!"
        And I follow "Edit"
        And I fill in "Name" with "Hack the Gibson"
        And I fill in "Start" with "2025-08-10 18:00:00"
        And I fill in "End" with "2025-08-12 18:00:00"
        And I check "Published"
        And I press "Submit"
        And I go to "/lans"

        Then I should see "Hack the Gibson"
        And I should see "August 2025"

    Scenario: Viewing the LAN list
        Given the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |
            | Hack the Gibson  | 2025-08-10 18:00:00 | 2025-08-12 18:00:00 | Cyberdelia | yes       |

        When I go to "/lans"

        Then I should see "Hack the planet!"
        And I should see "Hack the Gibson"
