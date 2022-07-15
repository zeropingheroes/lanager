Feature: Venues
    As an organiser
    I want to create, edit and delete venues
    So I can inform attendees where a LAN is taking place

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists

    Scenario: Creating a new venue
        Given I am logged in as "Zero Cool"

        When I go to "/venues/create"
        And I fill in "Name" with "Cyberdelia"
        And I fill in "Street Address" with "Clifden Road, Brentford, Greater London"
        And I fill in "Description" with "The ultimate cyberpunk club lounge"
        And I press "Submit"

        Then I should see "Cyberdelia"
        And I should see "Clifden Road, Brentford, Greater London"
        And I should see "The ultimate cyberpunk club lounge"

    Scenario: Editing an existing venue
        Given I am logged in as "Zero Cool"
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |

        When I go to "/venues"
        And I follow "Cyberdelia"
        And I follow "Edit"
        And I fill in "Name" with "Ellingson Mineral Corporation HQ"
        And I fill in "Street Address" with "Lloyd's building, Lime Street, London, EC3M 7AW"
        And I fill in "Description" with "Together, Anything is Possible"
        And I press "Submit"

        Then I should see "Ellingson Mineral Corporation HQ"
        And I should see "Lloyd's building, Lime Street, London, EC3M 7AW"

    Scenario: Viewing the venue list
        Given the following venues exist:
            | name                             | street_address                                  | description                        |
            | Cyberdelia                       | Clifden Road, Brentford, Greater London         | The ultimate cyberpunk club lounge |
            | Ellingson Mineral Corporation HQ | Lloyd's building, Lime Street, London, EC3M 7AW | Together, Anything is Possible     |

        When I go to "/venues"

        Then I should see "Cyberdelia"
        And I should see "Ellingson Mineral Corporation HQ"
