Feature:
    In order to see who a LAN's attendees are, and how many there are
    As a LAN organiser (and an attendee)
    I need to see a list of attendees for any given LAN

    Background:
        Given an admin with username "Zero Cool" exists
        Given a user with username "Cereal Killer" exists
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |

    Scenario: A user logs in during a LAN and is added to the LAN's attendee list
        Given the following LAN exists:
            | name             | description            | start      | end     | venue      | published |
            | Hack the planet! | This is our world now. | 1 hour ago | 2 hours | Cyberdelia | yes       |

        When I am logged in as "Cereal Killer"
        And I go to the homepage
        And I follow "Attendees"

        Then I should see "Cereal Killer"

    Scenario: A user logs in and there is no LAN occurring, so they are not on the LAN's attendee list
        Given the following LAN exists:
            | name             | description            | start      | end         | venue      | published |
            | Hack the planet! | This is our world now. | tomorrow   | next friday | Cyberdelia | yes       |

        When I am logged in as "Cereal Killer"
        And I go to the homepage
        And I follow "Attendees"

        Then I should not see "Cereal Killer"

    Scenario: When a user has attended one or more LANs, the LAN names show on their profile
        Given the following LAN exists:
            | name             | description            | start      | end     | venue      | published |
            | Hack the planet! | This is our world now. | 1 hour ago | 2 hours | Cyberdelia | yes       |
        And the user "Cereal Killer" has attended the LAN "Hack the planet!"

        When I go to the homepage
        And I follow "Attendees"
        And I follow "Cereal Killer"

        Then I should see "Hack the planet!"
