Feature: Images
    As an organiser
    I want to upload images
    So I can embed them in guides, events etc

    Background:
        Given an admin with username "Zero Cool" exists

    Scenario: Uploading an image
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I go to "/images"
        And I attach the file "public/img/bg.jpg" to "images"
        And I press "Upload"

        Then I should see "Image(s) successfully uploaded"
        And I should see "bg.jpg"

    Scenario: Renaming an image
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I go to "/images"
        And I attach the file "public/img/bg.jpg" to "images"
        And I press "Upload"
        And I follow "Edit"
        And I fill in "Filename" with "other.jpg"
        And I press "Submit"
        And I go to "/images"

        Then I should see "other.jpg"
