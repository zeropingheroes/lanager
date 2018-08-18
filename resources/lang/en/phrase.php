<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Phrases
    |--------------------------------------------------------------------------
    |
    | Usage examples:
    | - Flash messages
    | - Help text
    | - Success / warning / error messages
    |
    | Text should:
    | - Use sentence case
    | - Only contain one sentence
    | - Not be terminated with a full stop (.)
    */

    // User interface
    'toggle-navigation' => 'Toggle navigation',
    'is-copyright' => 'is ©',
    'and-licensed-under' => 'and licensed under',
    'powered-by-steam' => 'Powered by Steam',

    // Account Authentication
    'profile-update-required' => 'Please update your profile to continue',
    'please-sign-in' => 'Please sign in through Steam',
    'no-steam-account' => 'Don\'t have a Steam account? No problem!',
    'create-steam-account' => 'Create a Steam account for free',
    'provider-not-supported' => 'Unsupported authentication provider ":provider"',
    'user-successfully-logged-in' => 'User :username successfully logged in',
    'user-successfully-logged-out' => 'User :username successfully logged out',

    // Roles
    'user-already-has-role' => ':user has already been assigned the role :role',
    'role-successfully-assigned' => ':user has been assigned the role :role',
    'role-successfully-unassigned' => ':user is no longer assigned the role :role',
    'cannot-assign-role-to-self' => 'You cannot assign roles to yourself',
    'cannot-unassign-role-from-self' => 'You cannot unassign roles from yourself',
    'cannot-assign-role-to-super-admin' => 'Roles cannot be assigned to Super Admins',
    'assigned' => 'assigned',

    // General purpose
    'no-items-found' => 'No :item found',
    'the-following-errors-were-encountered' => 'The following errors were encountered',
    'item-unpublished' => 'This :item is unpublished and only visible administrators',
    'oh-no' => 'Oh no!',
    'item-name-deleted' => ':Item ":name" deleted',

    /**
     * Resources
     */
    // Logs
    'minimum-level' => 'Minimum level',
    'mark-as-read' => 'Mark as Read',
    'log-entries-marked-as-read' => 'Log entries successfully marked as read',
    'paste-below-into-github-issue' => 'Paste the below into a GitHub issue',

    // Users
    'your-steam-game-details-are-private' => 'Your Steam game details are currently private',
    'please-consider-public-visibility' => 'This means games you play won\'t show in ' . config(
            'app.name'
        ) . ' with everyone else. Please consider making your game details public, even if it\'s just for the event. Thanks!',
    'edit-steam-profile' => 'Edit Steam Profile',
    'avatar-for-username' => 'Avatar for :username',
    'hours-played-total' => 'hours total',
    'hours-played-two-weeks' => 'hours in last 2 weeks',
    'sign-in-to-see-the-games-you-have-in-common-with-username' => 'Sign in to see the games you have in common with :username',
    'you-have-no-games-in-common-with-username' => 'You have no games in common with :username',
    'username-does-not-own-any-games' => ':username does not own any games',
    'usernames-game-details-are-private' => ':username\'s game details are private, so we can\'t show you the games they own or'
        . ' have in common with you',
    'viewing-user-from-another-lan' => 'This user is not attending the current LAN',

    // Steam Statuses
    'status-in-game' => 'In Game',
    'status-in-game-x' => 'In Game: :x',
    'status-unknown' => 'Status unknown',

    // Games
    'x-in-game' => ':x In Game',
    'x-played-recently' => ':x Played Recently',
    'x-owners' => ':x Owners',
    'view-game-in-steam-store' => 'View :game in the Steam Store',
    'logo-for-game' => 'Logo for :game',

    // Guides
    'markdown-formatting-help-link' => 'Markdown formatting help',
    'markdown-formatting-help-link-url' => 'https://en.wikipedia.org/wiki/Markdown#Example',
    'markdown-help' => 'Tip: use relative links, e.g. [Install Guide](/guides/3) to easily link to other pages',
    'viewing-guide-from-past-lan' => 'This guide is from a LAN that has ended, so information may be irrelevant and/or incorrect',

    // Navigation Links
    'navigation-links-can-only-be-nested-one-level-deep' => 'Navigation links can only be nested one level deep',
    'a-navigation-link-cannot-be-its-own-parent' => 'A navigation link cannot be its own parent',

    // LANs
    'lans-cannot-overlap' => 'LANs cannot overlap',

    // Events
    'you-must-create-a-lan-before-creating-events' => 'You must create a LAN before creating events',
    'event-times-must-be-within-lan-times' => 'Events must start and finish within the LAN\'s start and end time',
    'timespan-to' => 'to',
    'upcoming' => 'Upcoming',
    'happening-now' => 'Happening Now',
    'ended' => 'Ended',
    'starting' => 'Starting',
    'ending' => 'Ending',
    'ended' => 'Ended',
    'unknown' => 'Unknown',

    // Images
    'select-files' => 'Select files',
    'images-successfully-uploaded' => 'Image(s) successfully uploaded',
    'image-filename-successfully-deleted' => 'Image :filename successfully deleted',
    'submitted-file-was-invalid-image' => 'Submitted file was not a valid image',
    'submitted-file-exceeded-max-file-size-of-x' => 'Submitted file exceeded the maximum file size of :x',
    'image-already-exists' => 'An image of the same name already exists',

    /**
     * Commands & Services
     */
    // UpdateSteamUserService
    // UpdateSteamUserAppsService
    'one-or-more-steam-ids-must-be-provided' => 'One or more Steam IDs must be provided',
    'one-or-more-users-must-be-provided' => 'One or more users must be provided',
    'unable-to-update-data-for-user-x' => 'Unable to update data for user :x - :error',

    // lanager:update-steam-apps
    'requesting-details-of-all-apps-from-steam' => 'Requesting details of all apps from Steam',
    'adding-x-steam-apps-to-db' => 'Adding :x apps to the database',
    'updating-x-steam-apps-already-in-db-and-adding-y-new' => 'Updating :x existing apps, and adding :y new apps',
    'steam-app-update-complete-x-added' => 'Steam app update complete - :x apps added',
    'steam-app-update-complete-x-updates-y-new' => 'Steam app update complete - :x updates, of which :y were new apps',

    // lanager:import-steam-users
    'no-steam-users-to-import' => 'No Steam users to import',
    'importing-x-users-from-steam' => 'Importing :x users from Steam',
    'successfully-updated-x-of-y-users' => 'Successfully updated :x of :y users',

    // lanager:update-steam-users
    'no-steam-users-to-update' => 'No Steam users to update',
    'updating-profiles-and-online-status-for-x-users-from-steam' => 'Updating profiles and online status for :x users from Steam',
    'successfully-updated-profiles-and-online-status-for-x-of-y-users' => 'Successfully updated profiles and online status for :x of :y users',

    // lanager:update-steam-user-apps
    'requesting-app-ownership-data-for-x-users-from-steam' => 'Requesting app ownership data for :x users from Steam',
    'successfully-updated-app-ownership-data-for-x-of-y-users' => 'Successfully updated app ownership data for :x of :y users',

    // lanager:prune-steam-user-history
    'pruning-historical-steam-data' => 'Pruning historical Steam user status and gameplay data',
    'x-entries-deleted-and-y-entries-retained' => 'Deleted :x and retained :y historical entries of Steam user status and gameplay',

    // lanager:backup
    'output-directory-not-writable' => 'The specified output directory is not writable',
    'output-directory-not-empty' => 'The specified output directory is not empty',
    'backup-created-successfully' => 'Backup created successfully',
    'process-exit-code-x' => 'Process exit code: :x',

    // lanager:restore-backup
    'backup-file-not-found' => 'The specified backup file was not found',
    'this-will-delete-all-lanager-data' => 'This will delete all LANager data!',
    'are-you-sure' => 'Are you sure you want to continue?',
    'deleting-all-lanager-data' => 'Deleting all LANager data',
    'backup-restored-successfully' => 'Backup restored successfully',
];