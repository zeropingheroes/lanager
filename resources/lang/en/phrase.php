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
    'no-steam-account' => "Don't have a Steam account? No problem!",
    'create-steam-account' => 'Create a Steam account for free',
    'provider-not-supported' => 'Unsupported authentication provider ":provider"',
    'user-successfully-logged-in' => 'User :username successfully logged in',
    'user-successfully-logged-out' => 'User :username successfully logged out',

    // Roles
    'user-already-has-role' => ':user is already assigned the :role role',
    'role-successfully-assigned' => 'You have assigned :user the role :role',
    'role-successfully-unassigned' => ':user is no longer assigned the role :role',
    'cannot-change-own-role-assignments' => 'You cannot change your own role assignments',
    'cannot-assign-role-to-super-admin' => 'Super Admins cannot be assigned additional roles',
    'assigned' => 'assigned',
    'admin-vs-superadmin' => "Admins are able to do everything a super admin can do, with the exception of changing other users' roles",

    // Achievements
    'achievement-successfully-awarded' => 'You have awarded :user the achievement ":achievement"',
    'achievement-successfully-revoked' => 'You have revoked the achievement ":achievement" from :user',
    'achievement-image-help' => 'Recommended image size is 128x128',
    'select-file' => 'Select file',

    // General purpose
    'no-items-found' => 'No :item found',
    'the-following-errors-were-encountered' => 'LANager has encountered the following errors:',
    'item-unpublished' => 'This :item is unpublished and only visible to administrators',
    'oh-no' => 'Oh no!',
    'item-name-deleted' => ':Item ":name" deleted',
    'are-you-sure' => 'Are you sure?',
    'are-you-sure-delete' => 'Are you sure you want to delete this?',
    'item-created-successfully' => ':Item created successfully',
    'item-updated-successfully' => ':Item updated successfully',
    'item-not-found' => ':item not found',
    'item-already-exists' => ':item already exists!',
    'fullscreen' => 'Fullscreen',

    /**
     * Resources.
     */

    // Users
    'your-steam-game-details-are-private' => 'Your Steam game details are currently private',
    'please-consider-public-visibility' => "This means the games you're playing won't appear in LANager with everyone else. Please consider making your game details public, even if it's just for the event. Thanks!",
    'edit-steam-profile' => 'Edit Steam Profile',
    'avatar-for-username' => 'Avatar for :username',
    'hours-played-total' => 'hours total',
    'hours-played-two-weeks' => 'hours in last 2 weeks',
    'sign-in-to-see-the-games-you-have-in-common-with-username' => 'Sign in to see the games you have in common with :username',
    'you-have-no-games-in-common-with-username' => 'You have no games in common with :username',
    'username-does-not-own-any-games' => ':username does not own any games',
    'usernames-game-details-are-private' => ":username's game details are private, so we can't show you the games they own or have in common with you",
    'viewing-user-from-another-lan' => 'This user is not attending the current LAN',
    'username-has-not-played-any-games-this-lan' => ':username has not played any games this LAN',
    'played-for-x' => 'Played for :x',
    'in-game-for-the-past-x' => 'In game for the past :x',

    // Steam Statuses
    'status-in-game' => 'In Game',
    'status-in-game-x' => 'In Game: :x',
    'status-unknown' => 'Status unknown',

    // Games
    'view-game-in-steam-store' => 'View :game in Steam Store',
    'x-in-game' => ':x In Game',
    'x-played-recently' => ':x Played recently',
    'x-owners' => ':x Owners',
    'logo-for-game' => 'Logo for :game',
    'unsupported-provider' => 'Unsupported provider',

    // Guides
    'markdown-formatting-help-link' => 'Markdown formatting help',
    'markdown-formatting-help-link-url' => 'https://en.wikipedia.org/wiki/Markdown#Examples',
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
    'event-times-must-be-within-lan-times' => "Events must start and finish within the LAN's start and end time",
    'event-is-not-open-for-signups' => 'This event is not open for signups',
    'you-can-only-sign-yourself-up-to-event' => 'You can only sign yourself up to an event',
    'timespan-to' => 'to',
    'upcoming' => 'Upcoming',
    'next' => 'Next',
    'happening-now' => 'Happening now',
    'now' => 'Now',
    'started' => 'Started',
    'ended' => 'Ended',
    'ended-x-ago' => 'Ended :x ago',
    'starting' => 'Starting',
    'starting-in-x' => 'Starting in :x',
    'ending' => 'Ending',
    'ending-in-x' => 'Ending in :x',
    'unknown' => 'Unknown',
    'signups' => 'Signups',
    'not-yet-open' => 'Not yet open',
    'open' => 'Open',
    'closed' => 'Closed',
    'opening' => 'Opening',
    'closing' => 'Closing',
    'today' => 'Today',
    'month' => 'Month',
    'week' => 'Week',
    'day' => 'Day',
    'list' => 'List',

    // Images
    'select-files' => 'Select files',
    'images-successfully-uploaded' => 'Image(s) successfully uploaded',
    'image-filename-successfully-deleted' => 'Image :filename successfully deleted',
    'submitted-file-was-invalid-image' => 'Submitted file was not a valid image',
    'submitted-file-exceeded-max-file-size-of-x' => 'Submitted file exceeded the maximum file size of :x',
    'image-already-exists' => 'An image of the same name already exists',

    /**
     * Commands & Services.
     */
    // General purpose
    'suppress-confirmations' => 'Run command without requesting confirmation',

    // UpdateSteamUserService
    // UpdateSteamUserAppsService
    'one-or-more-steam-ids-must-be-provided' => 'You must provide one or more steamID64s',
    'one-or-more-users-must-be-provided' => 'You must provide one or more LANager users',
    'unable-to-update-data-for-user-x' => 'Unable to update data for user :x - :error',

    // lanager:update-steam-apps
    'update-steam-apps' => 'Update the database with the latest list of apps from Steam',
    'requesting-list-of-all-apps-from-steam-api' => 'Requesting list of all apps from Steam API',
    'importing-x-steam-apps' => 'Importing :x Steam apps',

    // Used by:
    // lanager:update-steam-apps
    // lanager:import-steam-apps-csv
    // lanager:export-steam-apps-csv
    'database-empty-batch-import' => 'Database empty - performing batch import',
    'database-empty-aborting' => 'Database empty - aborting',
    'x-steam-apps-imported' => ':x Steam apps imported',
    'updating-x-steam-apps' => 'Updating :x Steam apps',
    'x-steam-apps-updated' => ':x Steam apps updated',

    // lanager:import-steam-users
    'steamids-to-import-list-or-file' => 'One or more SteamId64(s) for the user(s) to import, or a file containing a list of IDs',
    'import-users-from-steam-into-lanager' => 'Import users from Steam into LANager',
    'no-steam-users-to-import' => 'No Steam users to import',
    'importing-x-users-from-steam' => 'Importing :x users from Steam',
    'successfully-updated-x-of-y-users' => 'Successfully updated :x of :y users',

    // lanager:update-steam-users
    'update-existing-users-profiles-from-steam' => "Update existing LANager users' profiles with the latest information from their Steam profile",
    'update-all-users' => 'Update all users, not just those at the current LAN',
    'no-steam-users-to-update' => 'No Steam users to update',
    'updating-profiles-and-online-status-for-x-users-from-steam' => 'Updating profiles and online status for :x users from Steam',
    'successfully-updated-profiles-and-online-status-for-x-of-y-users' => 'Successfully updated profiles and online status for :x of :y users',

    // lanager:update-steam-user-apps
    'update-existing-user-app-ownership' => "Update existing LANager users' app ownership data with the latest information from their Steam profile",
    'requesting-app-ownership-data-for-x-users-from-steam' => 'Updating app ownership data for :x users from Steam',
    'successfully-updated-app-ownership-data-for-x-of-y-users' => 'Successfully updated app ownership data for :x of :y users',

    // lanager:update-steam-user-app-images
    'limit-number-of-apps-to-update' => 'Limit the number of apps to update',
    'update-app-images-for-apps-played-by-users' => 'Update app logo image URLs for apps played by users',
    'updating-x-apps' => 'Updating :x apps',
    'app-x-name-logo-urls-accessible-skipping' => 'App :id (:name): logo URLs from database are accessible. Skipping.',
    'app-x-name-no-logo-urls-checking-default' => 'App :id (:name): No logo URLs set. Checking default URL...',
    'app-x-name-default-logo-accessible-saving' => 'App :id (:name): Default logo URL accessible. Saving...',
    'app-x-name-default-logo-not-accessible-querying-api' => 'App :id (:name): Default logo URL not accessible. Querying API...',
    'app-x-name-got-logo-urls-from-api-saving' => 'App :id (:name): Got logo URLs from API. Saving...',
    'app-x-name-failed-to-get-logo-urls-from-api-skipping' => 'App :id (:name): Failed to get logo URLs from API. Skipping.',
    'successfully-updated-logo-urls-for-x-apps' => 'Successfully updated logo image URLs for :x apps.',
    'failed-to-update-logo-urls-for-x-apps' => 'Failed to update logo image URLs for :x apps:',
    'rate-limit-exceeded-waiting-x-seconds' => 'Rate limit exceeded. Waiting :seconds seconds before retrying (attempt :attempt of :maxAttempts)...',
    'rate-limit-exceeded-max-retries-reached' => 'Rate limit exceeded and max retry attempts reached.',

    // lanager:prune-steam-user-history
    'delete-steam-user-history-outside-lans' => 'Delete historical Steam user status and gameplay data that did not occur during any of the LANs in the database',
    'pruning-historical-steam-data' => 'Deleting historical Steam user status and gameplay data that did not occur during any of the LANs in the database',
    'x-entries-deleted-and-y-entries-retained' => 'Deleted :x and retained :y historical entries of Steam user status and gameplay',

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
    'confirm-get-app-ownership-data' => "Would you like to get each user's app ownership data? (~1 minute per 50 users)",
    'successfully-upgraded-database' => 'Successfully upgraded database from v0.5.x to v1.0.x',

    // make:feature
    'create-files-for-feature' => 'Create files required for a new feature',
    'name-of-feature' => 'The name of the feature, in singular StudlyCase, e.g. Venue',

    // Slides
    'slides-content-placeholder' => 'Markdown-formatted text, a single image, or a URL to embed',
    'slides-content-help' => 'Content will be horizontally centered, increased in size, and scaled to fit the screen',
    'slides-start-end-help' => 'Optionally set when the slide should be displayed',

    // LAN Games
    'create-lan-game' => 'Add a game you want to play at :lan',
    'which-games-would-you-like-to-play' => 'Which games would you like to play at :lan?',
    'game-already-submitted' => ':game has already been submitted',

    // LAN Game votes
    'you-have-already-voted-for-this-game' => 'You have already voted for this game',
    'log-in-to-submit-and-vote-on-games' => 'Log in to submit and vote on games you want to play at :lan',

    // Discord Channel Webhooks
    'discord-channel-webhooks-enable-lanager-to-send-messages' => 'Discord channel webhooks enable LANager to send messages to Discord channels, for example event notification messages.',
    'discord-test-message' => 'This is a test message for the :lan :purpose channel.',
    'discord-message-content-help' => 'Max 2000 characters, <a href=":url" target="_blank">Discord-flavoured Markdown</a> supported.',
    'discord-markdown-help-link-url' => 'https://support.discord.com/hc/en-us/articles/210298617',
    'discord-message-mentions-help' => 'Custom emoji: <:name:id>. Role mention: <@&role_id>. To get the raw text for either, prefix with \ in a Discord message: e.g. \:emoji: or \@role',
    'discord-message-placeholder' => 'Hi <@&1234567890123456789>! Counter-Strike 2 starts in 15 minutes. <:glhf:9876543212345678987>',
    'discord-message-sent-successfully' => 'Message sent to :purpose Discord channel successfully',
    'failed-to-send-discord-message' => 'Failed to send message to Discord. Check the webhook URL and try again.',
    'discord-channel-webhook-already-configured' => 'This LAN already has both webhooks configured.',
    'no-live-webhook-configured' => 'No "live" Discord channel webhook is configured for this LAN.',
    'no-test-webhook-configured' => 'No "test" Discord channel webhook is configured for this LAN.',
    'no-discord-channel-webhooks-yet' => 'No Discord channel webhooks have been configured for this LAN.',

    // Discord Event Notification Messages
    'no-event-discord-notification-message-configured' => 'No Discord notification message has been set up for this event.',
    'confirm-send-event-discord-notification-message-now' => 'Sending now will disable sending at the event\'s start time. Continue?',
    'event-discord-notification-preview-sent' => 'Sent message to "test" Discord channel successfully.',
    'event-discord-notification-preview-failed' => 'Failed to send preview message. Check the "test" channel webhook and try again.',
    'event-discord-notification-message-variables-help' => 'Variables: <code>{{event.name}}</code>, <code>{{event.url}}</code>. Replaced with the event\'s details when the message is sent.',
    'default-event-discord-notification-message' => "# 📢 Event Starting: {{event.name}} 📢\n## ➡️️ [Event information]({{event.url}}) ⬅️",
    'create-default-discord-notification-message-help' => 'Create a default message to automatically send to Discord at the event\'s start time. You can customise or delete the message at any time from the event\'s page.',
    'default-event-discord-notification-message-help' => 'Sent for any of this LAN\'s events that have no custom message of their own. Leave blank to use the default message shown as the placeholder above instead.',
    'event-discord-notification-message-lan-default-help' => 'Leave blank to use the LAN\'s default message (shown above), if one is set. <a href=":url">Edit the LAN\'s default message</a>.',
    'send-due-discord-event-notifications' => 'Send event Discord notification messages for events starting within the last minute',
    'automatically-send-discord-message-at-event-start-time' => 'Automatically send the message at the event\'s start time',
    'regardless-send-discord-message-any-time' => 'Regardless of this setting, you can send the message manually anytime from the event\'s page.',
    'discord-event-notification-message-sent' => 'Event Discord notification message sent',
    'failed-to-send-discord-event-notification-message' => 'Failed to send event Discord notification message',
    'unexpected-error-sending-discord-event-notification-message' => 'Unexpected error sending event Discord notification message',
    'discord-event-notification-messages-run-completed' => 'Sent :processed, skipped :skipped, failed :failed event Discord notification messages',
    'no-images-in-library' => 'There are no images in the image library.',
    'discord-image-not-in-library' => 'The selected image does not exist in the image library.',
    'discord-image-unsupported-extension' => 'The selected image has an unsupported file type. Permitted types: :types.',
    'discord-image-single-file-too-large' => 'The selected image must not exceed :max.',
    'discord-images-total-size-exceeded' => 'The total size of the selected images must not exceed :max.',
    'discord-no-images-added' => 'No images selected.',
    'discord-no-images-match-filter' => 'No images match your filter.',
    'discord-all-images-added' => 'All available images have been selected.',
    'discord-image-count-status' => ':count/:max images.',
    'discord-image-total-size' => ':size/:total total.',
    'discord-image-size-limits' => 'Single-image max: :perFile.',
    'discord-filter-by-filename' => 'Filter by filename…',
    'discord-image-count-limit-reached' => 'Maximum of :max images reached.',
    'discord-image-size-limit-reached' => ':limit limit reached.',
];
