Feature:
    In order to easily navigate LANager
    As a user
    I need to see an easy to understand error message and navigation links when I visit a page that doesn't exist

    Scenario: User visits a page that doesn't exist
        When I go to "/nonexistent-url"

        Then I should see "Not found"
