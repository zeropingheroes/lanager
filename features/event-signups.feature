Feature: Event signups
    In order to show my intent to participate in an event
    As an attendee
    I need to be able to sign up to events

    Background:
        Given an admin with username "Zero Cool" exists
        Given a user with username "Cereal Killer" exists
        Given the LAN "Hack The Planet!" is happening all day today at "Cyberdelia" on "Clifden Road, Brentford, Greater London"

    Scenario: When a user signs up to an event which is open for signups, their username appears on the event page
#        Given the event "wipE'out" is starting in "1 hour"

    Scenario: When a user removes their signup from an event which is open for signups, they do not show on the event page

    Scenario: When a user attempts to sign up for an event which does not allow signups, they see an error

    Scenario: When a user attempts to sign up for an event which is not yet open for signups, they see an error

    Scenario: When a user attempts to sign up for an event which is no longer open for signups, they see an error

