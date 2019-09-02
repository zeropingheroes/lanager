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
    'toggle-navigation' => 'Zobrazit odkazy',
    'is-copyright' => 'je ©',
    'and-licensed-under' => 'distribuován s licencí',
    'powered-by-steam' => 'Poháněno Steamem',

    // Account Authentication
    'profile-update-required' => 'Prosím aktualizujte svůj profil, abyste mohl pokračovat',
    'please-sign-in' => 'Prosím přihlašte se svým Steam účtem',
    'no-steam-account' => 'Nemáte Steam účet? Žádný problém!',
    'create-steam-account' => 'Vytvořit Steam účet zdarma',
    'provider-not-supported' => 'Nepodporovaný poskytovatel ":provider"',
    'user-successfully-logged-in' => 'Uživatel :username přihlášen úspěšně',
    'user-successfully-logged-out' => 'Uživatel :username odhlášen úspěšně',

    // Roles
    'user-already-has-role' => ':user už má :role roli',
    'role-successfully-assigned' => 'Přiřadil jste uživateli :user roli :role',
    'role-successfully-unassigned' => ':user už nemá přiřazenu roli :role',
    'cannot-assign-role-to-self' => 'Nemůžete sám sobě přiřazovat role',
    'cannot-unassign-role-from-self' => 'Nemůžete sám sobě odebírat role',
    'cannot-assign-role-to-super-admin' => 'Super Admins nemůžou měnit role',
    'assigned' => 'assigned',

    // Achievements
    'achievement-successfully-awarded' => 'Udělil jste uživateli :user achievement ":achievement"',
    'achievement-successfully-revoked' => 'Odebrali jste achievement ":achievement" uživateli :user',

    // General purpose
    'no-items-found' => 'Žádný :item nebyl nalezen',
    'the-following-errors-were-encountered' => 'LANager narazil na následující chyby:',
    'item-unpublished' => 'Tento :item není veřejný a vidí ho jen administrátoři',
    'oh-no' => 'Ale ne!',
    'item-name-deleted' => ':Item ":name" odstraněn',
    'are-you-sure-delete' => 'Jste si jistí, že to chcete odstranit?',
    'item-created-successfully' => ':Item vytvořen úspěšně',
    'item-not-found' => ':item nenalezen!',
    'item-already-exists' => ':item již existuje!',

    /**
     * Resources
     */
    // Logs
    'minimum-level' => 'Minimální level',
    'mark-as-read' => 'Označit jako přečtené',
    'log-entries-marked-as-read' => 'Logy úspěšně označeny jako přečtené',
    'paste-below-into-github-issue' => 'Vložte obsah níže do GitHub issue',

    // Users
    'your-steam-game-details-are-private' => 'Informace o Vašich Steam hrách jsou momentálně soukromé',
    'please-consider-public-visibility' => 'To znamená, že hry které hrajete se nezobrazí v rozhraní LANager se všemi ostatními. Prosím zvažte zveřejnění informací o Vašich Steam hrách, i kdyby to mělo být jenom pro tuto akci. Díky!',
    'edit-steam-profile' => 'Upravit Steam profil',
    'avatar-for-username' => 'Avatar uživatele :username',
    'hours-played-total' => 'hodin celkem',
    'hours-played-two-weeks' => 'hodin za posledních 14 dní',
    'sign-in-to-see-the-games-you-have-in-common-with-username' => 'Přihlšte se, abyste viděli hry, které máte společné s uživatelem :username',
    'you-have-no-games-in-common-with-username' => 'Nemáte žádné společné hry s uživatelem :username',
    'username-does-not-own-any-games' => 'Uživatel :username nevlastní žádné hry',
    'usernames-game-details-are-private' => 'Informace o Steam hrách uživatele :username jsou soukromé, takže Vám nemůžeme ukázat hry, které vlastní nebo ty, které máte společné',
    'viewing-user-from-another-lan' => 'Tento uživatel není účastníkem aktuální LANky',
    'username-has-not-played-any-games-this-lan' => ':username zatím nehrál žýdné hry na této LANce',
    'played-for-x' => 'Hrál :x',
    'in-game-for-the-past-x' => 'Ve hře posledních :x',

    // Steam Statuses
    'status-in-game' => 'Ve hře',
    'status-in-game-x' => 'Ve hře: :x',
    'status-unknown' => 'Neznámý stav',

    // Games
    'x-in-game' => ':x ve hře',
    'x-played-recently' => ':x hrál nedávno',
    'x-owners' => ':x majitelé',
    'view-game-in-steam-store' => 'Ukázat :game na Steamu',
    'logo-for-game' => 'Logo hry :game',

    // Guides
    'markdown-formatting-help-link' => 'Pomoc s Markdown formátováním',
    'markdown-formatting-help-link-url' => 'https://en.wikipedia.org/wiki/Markdown#Example',
    'markdown-help' => 'Tip: používejte relativní odkazy, např.: [Install Guide](/guides/3), abyste mohli jednoduše odkazovat na jiné stránky',
    'viewing-guide-from-past-lan' => 'Tento návod je z LANky, která už skončila, takže informace v něm nemusí být aktuální',

    // Navigation Links
    'navigation-links-can-only-be-nested-one-level-deep' => 'Můžete slučovat odkazy v navigačním panelu pouze jednu úroveň hluboko',
    'a-navigation-link-cannot-be-its-own-parent' => 'Odkaz v navigačním panelu nemůže být nadřazen sám sobě',

    // LANs
    'lans-cannot-overlap' => 'LANky se nemohou časově překrývat',

    // Events
    'you-must-create-a-lan-before-creating-events' => 'Před tvorbou události musíte nejprve vytvořit LANku',
    'event-times-must-be-within-lan-times' => 'Události musí začínat a končit v čase vymezeném LANkou',
    'event-is-not-open-for-signups' => 'Tato událost nemá otevřené přihlášky',
    'you-can-only-sign-yourself-up-to-event' => 'K události můžete přihlásit pouze sebe',
    'timespan-to' => 'do',
    'upcoming' => 'Nadcházející',
    'happening-now' => 'Právě probíhající',
    'ended' => 'Skončilo',
    'starting' => 'Začíná',
    'ending' => 'Končí',
    'ended' => 'Skončilo',
    'unknown' => 'Neznámý',
    'signups' => 'Přihlášky',
    'not-yet-open' => 'Zatím uzavřeno',
    'open' => 'Otevřeno',
    'closed' => 'Uzavřeno',
    'opening' => 'Otevírající',
    'closing' => 'Uzavírající',
    'closed' => 'Uzavřeno',

    // Images
    'select-files' => 'Vyberte soubory',
    'images-successfully-uploaded' => 'Obrázek/obrázky úspěšně nahrány',
    'image-filename-successfully-deleted' => 'Obrázek :filename úspěšně odstraněn',
    'submitted-file-was-invalid-image' => 'Nahraný soubor není obrázek',
    'submitted-file-exceeded-max-file-size-of-x' => 'Nahráaný soubor překročil maximání povolenou velikost :x',
    'image-already-exists' => 'Obrázek se stejným názvem již existuje',

    /**
     * Commands & Services
     */
    // General purpose
    'suppress-confirmations' => 'Spustit příkaz bez žádání o povolení',

    // UpdateSteamUserService
    // UpdateSteamUserAppsService
    'one-or-more-steam-ids-must-be-provided' => 'Jedno nebo více Steam ID musí být dodáno',
    'one-or-more-users-must-be-provided' => 'Musíte dodat jednoho nebo více LANager uživatelů',
    'unable-to-update-data-for-user-x' => 'Aktualizovat data uživatele :x nebylo možné - :error',

    // lanager:update-steam-apps
    'update-database-with-apps-from-steam' => 'Aktualizovat databázi nejnovějším seznamem aplikací ze Steamu',
    'requesting-details-of-all-apps-from-steam' => 'Vyžadování informací o všech aplikací ze Steamu',
    'adding-x-steam-apps-to-db' => 'Přidávání :x aplikací do databáze',
    'updating-x-steam-apps-already-in-db-and-adding-y-new' => 'Aktualizování :x existujících aplikací, a přidávání :y nových aplikací',
    'steam-app-update-complete-x-added' => 'Aktualizace Steam aplikací kompletní - :x aplikací přidáno',
    'steam-app-update-complete-x-updates-y-new' => 'Aktualizace Steam aplikací kompletní - :x aktualizací, ze kterých :y byly nové aplikace',

    // lanager:import-steam-users
    'steamids-to-import-list-or-file' => 'Jedno nebo více Steam ID uživatelů pro import nebo soubor obsahující seznam Steam ID',
    'import-users-from-steam-into-lanager' => 'Importovat uživatele ze Steamu do rozhraní LANager',
    'no-steam-users-to-import' => 'Žádní Steam uživatelé nebudou importování',
    'importing-x-users-from-steam' => 'Importování :x uživatelů ze Steamu',
    'successfully-updated-x-of-y-users' => 'Úspěšně aktualizováno :x ze :y uživatelů',

    // lanager:update-steam-users
    'update-existing-users-profiles-from-steam' => 'Aktualizovat existující profily uživatelů v rozhraní LANager těmi nejnovějšími informacemi ze Steamu',
    'update-all-users' => 'Aktualizovat všechny uživatele, nejen ty na aktuální LANce',
    'no-steam-users-to-update' => 'Žádné Steam profily nebudou aktualizovány',
    'updating-profiles-and-online-status-for-x-users-from-steam' => 'Aktualizace profilů a online stavů :x uživatelů ze Steamu',
    'successfully-updated-profiles-and-online-status-for-x-of-y-users' => 'Úspěšně aktualizovány profily a online stavy pro :x of :y uživatelů',

    // lanager:update-steam-user-apps
    'update-existing-user-app-ownership' => 'Aktualizovat informace o vlastněných aplikacích existujících uživatelů v rozhraní LANager ze Steamu',
    'requesting-app-ownership-data-for-x-users-from-steam' => 'Aktualizace informací o vlastněných aplikacích pro :x uživatelů ze Steamu',
    'successfully-updated-app-ownership-data-for-x-of-y-users' => 'Úspěšně aktualizovány informace o vlastněných aplikacích pro :x ze :y uživatelů',

    // lanager:prune-steam-user-history
    'delete-steam-user-history-outside-lans' => 'Odstranit minulou aktivitu a stavy Steam uživatele, která neproběhla v rámci žádné LANky v databázi',
    'pruning-historical-steam-data' => 'Odstraňování minulé aktivity a stavů Steam uživatele, která neproběhla v rámci žádné LANky v databázi',
    'x-entries-deleted-and-y-entries-retained' => 'Odstraněno :x a zachováno :y záznamů minulé aktivity Steam uživatele a jeho stavů',

    // lanager:backup
    'output-dir' => 'Kam uložit soubor zálohy',
    'backup-lanager-to-file' => 'Zálohovat data z rozhraní LANager do souboru',
    'output-directory-not-writable' => 'LANager nemá povolení zapisovat do zvoleného adresáře',
    'output-directory-not-empty' => 'Zvolený adresář není prázdný',
    'backup-created-successfully' => 'Záloha vytvořena úspěšně',
    'process-exit-code-x' => 'Kód ukončení procesu: :x',

    // lanager:restore-backup
    'restore-lanager-backup-from-file' => 'Obnovit zálohu rozhraní LANager ze souboru',
    'backup-file' => 'Cesta k souboru se zálohou',
    'backup-file-not-found' => 'Zvolený soubor se zálohou nebyl nalezen na dané adrese',
    'this-will-delete-all-lanager-data' => 'Tento krok odstaní všechna data z rozhraní LANager!',
    'are-you-sure' => 'Opravdu chcete pokračovat?',
    'deleting-all-lanager-data' => 'Odstaňování všech dat z rozhraní LANager',
    'backup-restored-successfully' => 'Záloha obnovena úspěšně',

    // lanager:upgrade-database
    'upgrade-database' => 'Aktualizovat databázi z verze 0.5.x při zachování dat',
    'manually-backup-before-continuing' => 'Ručně zálohovat databázi před pokračováním',
    'database-structure-already-up-to-date' => 'Struktura databáze je již aktuální',
    'database-structure-does-not-match-table-x-missing' => 'Struktura databáze neodpovídá verzi 0.5.x - tabulka :x chybí',
    'deleting-x' => 'Odstraňování :x',
    'upgrading-x' => 'Aktualizace :x',
    'fixing-timestamp-columns' => 'Opravování timestamp sloupců',
    'creating-new-tables' => 'Vytváření nových tabulek',
    'spoofing-initial-migration' => 'Předstírání počáteční migrace',
    'confirm-get-app-ownership-data' => 'Chcete získat informace o vlastnictví všech uživatelů? (zhruba 1 minuta na 50 uživatelů)',
    'successfully-upgraded-database' => 'Databáze verze 0.5.x úspěšně aktualizována na verzi 1.0.x',

    // make:feature
    'create-files-for-feature' => 'Vytvořit soubory nutné pro využití nové funkce',
    'name-of-feature' => 'Název nové funkce v jednotném čísle',

    // Slides
    'slides-content-placeholder' => 'Text ve formátu Markdown, jeden obrázek nebo URL adresa k vložení',
    'slides-content-help' => 'Obsah bude horizontálně vycentrován, bude zvětšen a škálován, aby se vešel na obrazovku'
];
