Feature: Role Assignments
    As an organiser
    I want to assign roles to users
    So they can help administer the site with me

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists

    Scenario: Creating a new role assignment
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Role Assignments"
        And I select "Cereal Killer" from "User"
        And I select "Admin" from "Role"
        And I press "Assign Role"

        Then I should see "You have assigned Cereal Killer the role Admin"
