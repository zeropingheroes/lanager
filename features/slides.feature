Feature: Slides
    As an organiser
    I want to create, edit and delete slides
    So I can share useful information with attendees via a looping presentation

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00 | 2025-09-17 18:00 | Cyberdelia | yes       |

    Scenario: Creating a new slide
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Slides"
        And I follow "Create"
        And I fill in "Name" with "Cookie Monster"
        And I fill in "Content" with:
            """
            I WANT A COOKIE. GIVE ME A COOKIE NOW!
            """
        And I fill in "Position" with "1"
        And I fill in "Duration" with "10"
        And I check "Published"
        And I press "Submit"
        And I follow "Cookie Monster"

        Then I should see "Cookie Monster"

    Scenario: Viewing the slide list
        Given I am logged in as "Zero Cool"
        And the following slides exist:
            | name           | content                                     | position | duration | published | lan              |
            | Cookie Monster | I WANT A COOKIE. GIVE ME A COOKIE NOW!      | 1        | 10       | yes       | Hack the planet! |
            | Rabbit         | The rabbit is in the administration system. | 2        | 10       | yes       | Hack the planet! |

        When I go to the homepage
        And I follow "Slides"

        Then I should see "Cookie Monster"
        And I should see "Rabbit"
