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
    'user-already-has-role' => ':user is already assigned the :role role',
    'role-successfully-assigned' => 'You have assigned :user the role :role',
    'role-successfully-unassigned' => ':user is no longer assigned the role :role',
    'cannot-assign-role-to-self' => 'You cannot assign roles to yourself',
    'cannot-unassign-role-from-self' => 'You cannot unassign roles from yourself',
    'cannot-assign-role-to-super-admin' => 'Super Admins cannot change roles',
    'assigned' => 'assigned',

    // Achievements
    'achievement-successfully-awarded' => 'You have awarded :user the achievement ":achievement"',
    'achievement-successfully-revoked' => 'You have revoked the achievement ":achievement" from :user',
    'select-file' => 'Select file',
    'image-help' => 'Recommended image size is 128x128',

    // General purpose
    'no-items-found' => 'No :item found',
    'the-following-errors-were-encountered' => 'LANager has encountered the following errors:',
    'item-unpublished' => 'This :item is unpublished and only visible to administrators',
    'oh-no' => 'Oh no!',
    'item-name-deleted' => ':Item ":name" deleted',
    'are-you-sure-delete' => 'Are you sure you want to delete this?',
    'item-created-successfully' => ':Item created successfully',
    'item-not-found' => ':item not found!',
    'item-already-exists' => ':item already exists!',

    /**
     * Resources
     */
    // Logs
    'minimum-level' => 'Minimum level',
    'mark-as-read' => 'Mark as read',
    'log-entries-marked-as-read' => 'Log entries successfully marked as read',
    'paste-below-into-github-issue' => 'Paste the content below into a GitHub issue',

    // Users
    'your-steam-game-details-are-private' => 'Your Steam game details are currently private',
    'please-consider-public-visibility' => 'This means the games you\’re playing won\'t appear in LANager with everyone else. Please consider making your game details public, even if it\'s just for the event. Thanks!',
    'edit-steam-profile' => 'Edit Steam Profile',
    'avatar-for-username' => 'Avatar for :username',
    'hours-played-total' => 'hours total',
    'hours-played-two-weeks' => 'hours in last 2 weeks',
    'sign-in-to-see-the-games-you-have-in-common-with-username' => 'Sign in to see the games you have in common with :username',
    'you-have-no-games-in-common-with-username' => 'You have no games in common with :username',
    'username-does-not-own-any-games' => ':username does not own any games',
    'usernames-game-details-are-private' => ':username\'s game details are private, so we can\'t show you the games they own or have in common with you',
    'viewing-user-from-another-lan' => 'This user is not attending the current LAN',
    'username-has-not-played-any-games-this-lan' => ':username has not played any games this LAN',
    'played-for-x' => 'Played for :x',
    'in-game-for-the-past-x' => 'In game for the past :x',

    // Steam Statuses
    'status-in-game' => 'In Game',
    'status-in-game-x' => 'In Game: :x',
    'status-unknown' => 'Status unknown',

    // Games
    'x-in-game' => ':x In Game',
    'x-played-recently' => ':x Played recently',
    'x-owners' => ':x Owners',
    'view-game-in-steam-store' => 'View :game in the Steam Store',
    'logo-for-game' => 'Logo for :game',

    // Guides
    'markdown-formatting-help-link' => 'Markdown formatting help',
    'markdown-formatting-help-link-url' => 'https://en.wikipedia.org/wiki/Markdown#Example',
    'markdown-help' => 'Tip: use relative links, e.g. [Install Guide](/guides/3), to easily link to other pages',
    'viewing-guide-from-past-lan' => 'This guide is from a LAN that has ended, so information might be irrelevant and/or incorrect',

    // Navigation Links
    'navigation-links-can-only-be-nested-one-level-deep' => 'You can only nest navigation links one level deep',
    'a-navigation-link-cannot-be-its-own-parent' => 'A navigation link cannot be its own parent',

    // LANs
    'lans-cannot-overlap' => 'LANs cannot overlap',
    'lan-achievement-help' => 'The achievement to award to attendees when they sign in at the LAN',

    // Events
    'you-must-create-a-lan-before-creating-events' => 'You must create a LAN before creating events',
    'event-times-must-be-within-lan-times' => 'Events must start and finish within the LAN\'s start and end time',
    'event-is-not-open-for-signups' => 'This event is not open for signups',
    'you-can-only-sign-yourself-up-to-event' => 'You can only sign yourself up to an event',
    'timespan-to' => 'to',
    'upcoming' => 'Upcoming',
    'happening-now' => 'Happening now',
    'ended' => 'Ended',
    'starting' => 'Starting',
    'ending' => 'Ending',
    'ended' => 'Ended',
    'unknown' => 'Unknown',
    'signups' => 'Signups',
    'not-yet-open' => 'Not yet open',
    'open' => 'Open',
    'closed' => 'Closed',
    'opening' => 'Opening',
    'closing' => 'Closing',
    'closed' => 'Closed',

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
    // General purpose
    'suppress-confirmations' => 'Run command without requesting confirmation',

    // UpdateSteamUserService
    // UpdateSteamUserAppsService
    'one-or-more-steam-ids-must-be-provided' => 'You must provide one or more steamID64s',
    'one-or-more-users-must-be-provided' => 'You must provide one or more LANager users',
    'unable-to-update-data-for-user-x' => 'Unable to update data for user :x - :error',

    // lanager:update-steam-apps
    'update-database-with-apps-from-steam' => 'Update the database with the latest list of apps from Steam',
    'requesting-details-of-all-apps-from-steam' => 'Requesting details of all apps from Steam',
    'adding-x-steam-apps-to-db' => 'Adding :x apps to the database',
    'updating-x-steam-apps-already-in-db-and-adding-y-new' => 'Updating :x existing apps, and adding :y new apps',
    'steam-app-update-complete-x-added' => 'Steam app update complete - :x apps added',
    'steam-app-update-complete-x-updates-y-new' => 'Steam app update complete - :x updates, of which :y were new apps',

    // lanager:import-steam-users
    'steamids-to-import-list-or-file' => 'One or more SteamId64(s) for the user(s) to import, or a file containing a list of IDs',
    'import-users-from-steam-into-lanager' => 'Import users from Steam into LANager',
    'no-steam-users-to-import' => 'No Steam users to import',
    'importing-x-users-from-steam' => 'Importing :x users from Steam',
    'successfully-updated-x-of-y-users' => 'Successfully updated :x of :y users',

    // lanager:update-steam-users
    'update-existing-users-profiles-from-steam' => 'Update existing LANager users\' profiles with the latest information from their Steam profile',
    'update-all-users' => 'Update all users, not just those at the current LAN',
    'no-steam-users-to-update' => 'No Steam users to update',
    'updating-profiles-and-online-status-for-x-users-from-steam' => 'Updating profiles and online status for :x users from Steam',
    'successfully-updated-profiles-and-online-status-for-x-of-y-users' => 'Successfully updated profiles and online status for :x of :y users',

    // lanager:update-steam-user-apps
    'update-existing-user-app-ownership' => 'Update existing LANager users\' app ownership data with the latest information from their Steam profile',
    'requesting-app-ownership-data-for-x-users-from-steam' => 'Updating app ownership data for :x users from Steam',
    'successfully-updated-app-ownership-data-for-x-of-y-users' => 'Successfully updated app ownership data for :x of :y users',

    // lanager:prune-steam-user-history
    'delete-steam-user-history-outside-lans' => 'Delete historical Steam user status and gameplay data that did not occur during any of the LANs in the database',
    'pruning-historical-steam-data' => 'Deleting historical Steam user status and gameplay data that did not occur during any of the LANs in the database',
    'x-entries-deleted-and-y-entries-retained' => 'Deleted :x and retained :y historical entries of Steam user status and gameplay',

    // lanager:backup
    'output-dir' => 'Where to store the backup file',
    'backup-lanager-to-file' => 'Back up LANager data to a file',
    'output-directory-not-writable' => 'The specified output directory is not writable',
    'output-directory-not-empty' => 'The specified output directory is not empty',
    'backup-created-successfully' => 'Backup created successfully',
    'process-exit-code-x' => 'Process exit code: :x',

    // lanager:restore-backup
    'restore-lanager-backup-from-file' => 'Restore a LANager backup from a file',
    'backup-file' => 'Path to the backup file',
    'backup-file-not-found' => 'The specified backup file was not found',
    'this-will-delete-all-lanager-data' => 'This will delete all LANager data!',
    'are-you-sure' => 'Are you sure you want to continue?',
    'deleting-all-lanager-data' => 'Deleting all LANager data',
    'backup-restored-successfully' => 'Backup restored successfully',

    // lanager:upgrade-database
    'upgrade-database' => 'Upgrade the LANager database from v0.5.x, retaining existing data',
    'manually-backup-before-continuing' => 'Manually backup your LANager database before continuing',
    'database-structure-already-up-to-date' => 'Database structure is already up to date',
    'database-structure-does-not-match-table-x-missing' => 'Database structure does not match v0.5.x - table :x missing',
    'deleting-x' => 'Deleting :x',
    'upgrading-x' => 'Upgrading :x',
    'fixing-timestamp-columns' => 'Fixing timestamp columns',
    'creating-new-tables' => 'Creating new tables',
    'spoofing-initial-migration' => 'Spoofing initial migration',
    'confirm-get-app-ownership-data' => 'Would you like to get each user\'s app ownership data? (~1 minute per 50 users)',
    'successfully-upgraded-database' => 'Successfully upgraded database from v0.5.x to v1.0.x',

    // make:feature
    'create-files-for-feature' => 'Create files required for a new feature',
    'name-of-feature' => 'The name of the feature, in singular StudlyCase, e.g. Venue',

    // Slides
    'slides-content-placeholder' => 'Markdown-formatted text, a single image, or a URL to embed',
    'slides-content-help' => 'Content will be horizontally centered, increased in size, and scaled to fit the screen',
    'slides-start-end-help' => 'Optionally set when the slide should be displayed'
];

