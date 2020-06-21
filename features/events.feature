Feature: Events
    In order to inform LAN attendees about what is going on at the LAN
    As an admin
    I need to be able to create, edit and delete events

    Background:
        Given an admin with username "Zero Cool" exists
        Given I am logged in as "Zero Cool"
        Given The following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        Given The following LAN exists:
            | name             | description            | start               | end                 | venue      | published |
            | Hack the planet! | This is our world now. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

    Scenario: Creating an event
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
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

    Scenario: Editing an existing event's details
        Given The following event exists:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Hack the planet! | yes       |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "wipE'out"
        And I follow "Edit"
        And I fill in "Name" with "DEFCON"
        And I fill in "Description" with "Everybody dies."
        And I fill in "Start" with "2025-09-15 20:00:00"
        And I fill in "End" with "2025-09-15 22:00:00"
        And I check "Published"
        And I press "Submit"

        Then I should see "DEFCON"
        And I should see "Everybody dies."

    Scenario: Un-publishing an existing event should mean it is not visible to guests
        Given The following event exists:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Hack the planet! | yes       |

        When I go to the homepage

        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "wipE'out"
        And I follow "Edit"
        And I uncheck "Published"
        And I press "Submit"
        And I log out
        And I go to the homepage
        And I go to "/lans"
        And I follow "Hack the planet!"

        Then I should not see "wipE'out"

#    Scenario: Creating an event which allows attendee signups
#    Scenario: Changing an existing event's start, end and signup times
#    Scenario: A guest viewing the events list should not be able to see an unpublished event
#    Scenario: Attempting to create an event with a start time after end time should show an error
#    Scenario: Attempting to create an event with no name should show an error
#    Scenario: Attempting to create an event which starts after the LAN finishes should show an error
#    Scenario: Attempting to create an event which ends after the LAN finishes should show an error
