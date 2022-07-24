Feature: Navigation links
    As an organiser
    I want to create, edit and delete navigation links
    So I can customise the links on the navigation bar

    Background:
        Given an admin with username "Zero Cool" exists

    Scenario: Creating a new navigation link
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Navigation"
        And I follow "Create"
        And I fill in "Title" with "Mess With The Best"
        And I fill in "URL" with "/mess-with-the-best"
        And I fill in "Position" with "1"
        And I press "Submit"
        And I go to the homepage

        Then I should see "Mess With The Best"

    Scenario: Editing an existing navigation link
        Given I am logged in as "Zero Cool"
        And the following navigation links exist:
            | title              | url                 | position |
            | Mess With The Best | /mess-with-the-best | 1        |

        When I go to the homepage
        And I follow "Navigation"
        And I follow "Edit"
        And I fill in "Title" with "Die Like The Rest"
        And I fill in "URL" with "/die-like-the-rest"
        And I press "Submit"
        And I go to the homepage

        Then I should see "Die Like The Rest"
        And I should not see "Mess With The Best"

    Scenario: Viewing the navigation link list
        Given I am logged in as "Zero Cool"
        And the following navigation links exist:
            | title              | url                 | position |
            | Mess With The Best | /mess-with-the-best | 1        |
            | Die Like The Rest  | /die-like-the-rest  | 2        |

        When I go to the homepage
        And I follow "Navigation"

        Then I should see "Mess With The Best"
        And I should see "Die Like The Rest"
