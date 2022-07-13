Feature:
    In order to embed images into pages on LANager
    As a LAN organiser
    I need to upload images to LANager

    Background:
        Given an admin with username "Zero Cool" exists

    Scenario: Admin uploads an image
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I go to "/images"
        And I attach the file "public/img/bg.jpg" to "images"
        And I press "Upload"

        Then I should see "Image(s) successfully uploaded"
        And I should see "bg.jpg"

#    TODO: Requires Selenium due to Javascript being used for "delete" button
#    Scenario: Admin deletes an image
