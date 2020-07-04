Feature: Events
    In order to inform LAN attendees about what is going on at the LAN
    As an admin
    I need to be able to create, edit and delete events

    Background:
        Given an admin with username "Zero Cool" exists
        Given I am logged in as "Zero Cool"
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        Given the following LAN exists:
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
        Given the following event exists:
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
        Given the following event exists:
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
        And I should see "0"

    Scenario: Unpublished events should be labelled for admins
        Given the following event exists:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Hack the planet! | no        |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"

        Then I should see "wipE'out"
        And I should see "Unpublished"

    Scenario: Creating an event which allows attendee signups up until the event start time
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-15 21:00:00"
        And I fill in "Signups Open" with "2025-09-15 12:00:00"
        And I fill in "Signups Close" with "2025-09-15 19:00:00"
        And I check "Published"
        And I press "Submit"

        Then I should see "wipE'out"
        And I should see "A dangerous game."

    Scenario: Changing an existing event's start, end and signup times
        Given the following event exists:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Hack the planet! | yes       |

        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "wipE'out"
        And I follow "Edit"
        And I fill in "Name" with "Kate vs Dade"
        And I fill in "Description" with "Mess with the best, die like the rest."
        And I fill in "Start" with "2025-09-15 18:00:00"
        And I fill in "End" with "2025-09-16 23:59:00"
        And I fill in "Signups Open" with "2025-09-15 17:00:00"
        And I fill in "Signups Close" with "2025-09-15 18:00:00"
        And I press "Submit"

        Then I should see "Kate vs Dade"
        And I should see "Mess with the best, die like the rest."

    Scenario: A guest viewing the events list should not be able to see an unpublished event
        Given the following event exists:
            | name     | description       | start               | end                 | lan              | published |
            | wipE'out | A dangerous game. | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Hack the planet! | no        |

        When I go to the homepage
        And I log out
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"

        Then I should not see "wipE'out"
        And I should not see "A dangerous game."
        And I should not see "1"

    Scenario: Attempting to create an event with a start time after end time should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-15 18:00:00"
        And I check "Published"
        And I press "Submit"

        Then I should see "The start must be a date before end."

    Scenario: Attempting to create an event with no name, start or end should show errors
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I press "Submit"

        Then I should see "The name field is required."
        Then I should see "The start field is required."
        Then I should see "The end field is required."

    Scenario: Attempting to create an event which starts after the LAN finishes should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 17:00:00"
        And I fill in "End" with "2025-09-15 18:00:00"
        And I press "Submit"

        Then I should see "Events must start and finish within the LAN's start and end time"

    Scenario: Attempting to create an event which ends after the LAN finishes should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-17 20:00:00"
        And I press "Submit"

        Then I should see "Events must start and finish within the LAN's start and end time"

    Scenario: Attempting to create an event with a signup opening after signup closing should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-15 20:00:00"
        And I fill in "Signups Open" with "2025-09-15 17:00:00"
        And I fill in "Signups Close" with "2025-09-15 16:00:00"
        And I press "Submit"

        Then I should see "The signups open must be a date before signups close."

    Scenario: Attempting to create an event with a signup opening time after the event starts should show an error
        When I go to the homepage
        And I follow "LANs"
        And I follow "Hack the planet!"
        And I follow "List"
        And I follow "Create"
        And I fill in "Name" with "wipE'out"
        And I fill in "Description" with "A dangerous game."
        And I fill in "Start" with "2025-09-15 19:00:00"
        And I fill in "End" with "2025-09-15 23:00:00"
        And I fill in "Signups Open" with "2025-09-15 20:00:00"
        And I fill in "Signups Close" with "2025-09-15 21:00:00"
        And I press "Submit"

        Then I should see "The signups open must be a date before or equal to start."
