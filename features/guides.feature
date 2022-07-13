Feature: Guides
    In order to share important information with attendees
    As a LAN organiser
    I need to be able to draft and publish guides

        Background:
        Given an admin with username "Zero Cool" exists
        Given a user with username "Cereal Killer" exists
        Given the following venue exists:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        Given the following LAN exists:
            | name             | description            | start      | end     | venue      | published |
            | Hack the planet! | This is our world now. | 1 hour ago | 2 hours | Cyberdelia | yes       |

    Scenario: Admin publishes a guide
        Given I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Guides"
        And I follow "Create"
        And I fill in "Title" with "Acid Burn's Laptop"
        And I fill in "Content" with:
        """
        It's got a 28.8 bps modem. Active matrix display, a million psychedelic colors.
        It has a killer refresh rate. P6 chip.
        Triple the speed of the Pentium. It's not just the chip. It has a PCI bus.
        RISC architecture is going to change everything.
        """
        And I check "Published"
        And I press "Submit"
        And I go to the homepage
        And I follow "Guides"

        Then I should see "Acid Burn's Laptop"

    Scenario: Unauthenticated user visits the guide page and can't see an unpublished guide
        Given the following guides exist:
            | title              | content                                          | published | lan              |
            | Acid Burn's Laptop | RISC architecture is going to change everything. | no        | Hack the planet! |
            | Guide to IBM PCs   | The Pink Shirt Book.                             | yes       | Hack the planet! |

        When I go to the homepage
        And I follow "Guides"

        Then I should not see "Acid Burn's Laptop"
        And I should see "Guide to IBM PCs"

    Scenario: Admin edits an existing guide
        Given the following guides exist:
            | title              | content                                          | published | lan              |
            | Acid Burn's Laptop | RISC architecture is going to change everything. | no        | Hack the planet! |
        And I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Guides"
        And I follow "Acid Burn's Laptop"
        And I follow "Edit"
        And I fill in "Content" with:
        """
        It's got a 28.8 bps modem. Active matrix display, a million psychedelic colors.
        """
        And I press "Submit"
        And I go to the homepage
        And I follow "Guides"
        And I follow "Acid Burn's Laptop"

        Then I should see "It's got a 28.8 bps modem"
        And I should not see "RISC architecture"

    Scenario: Admin deletes an existing guide
        # TODO: Requires Selenium due to Javascript being used for "delete" button

    Scenario: Admin un-publishes existing guide
        Given the following guides exist:
            | title              | content                                          | published | lan              |
            | Acid Burn's Laptop | RISC architecture is going to change everything. | no        | Hack the planet! |
        And I am logged in as "Zero Cool"

        When I go to the homepage
        And I follow "Guides"
        And I follow "Acid Burn's Laptop"
        And I follow "Edit"
        And I uncheck "Published"
        And I press "Submit"

        Then I should see "This guide is unpublished and only visible to administrators"
