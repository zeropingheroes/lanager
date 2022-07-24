Feature: Guides
    As an organiser
    I want to create, edit and delete guides
    So I can share important and useful information with attendees

    Background:
        Given an admin with username "Zero Cool" exists
        And a user with username "Cereal Killer" exists
        And the following venues exist:
            | name       | street_address                          | description                        |
            | Cyberdelia | Clifden Road, Brentford, Greater London | The ultimate cyberpunk club lounge |
        And the following LANs exist:
            | name             | start               | end                 | venue      | published |
            | Hack the planet! | 2025-09-15 18:00:00 | 2025-09-17 18:00:00 | Cyberdelia | yes       |

    Scenario: Creating a new guide
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

    Scenario: Editing an existing guide
        Given I am logged in as "Zero Cool"
        And the following guides exist:
            | title              | content                                          | published | lan              |
            | Acid Burn's Laptop | RISC architecture is going to change everything. | yes       | Hack the planet! |

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

    Scenario: Viewing the guide list
        Given the following guides exist:
            | title              | content                                          | published | lan              |
            | Acid Burn's Laptop | RISC architecture is going to change everything. | yes       | Hack the planet! |
            | Guide to IBM PCs   | The Pink Shirt Book.                             | yes       | Hack the planet! |

        When I go to the homepage
        And I follow "Guides"

        Then I should see "Acid Burn's Laptop"
        And I should see "Guide to IBM PCs"
