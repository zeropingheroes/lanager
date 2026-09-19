<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\Guides;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;

class CloneGuideTest extends DuskTestCase
{
    public function test_cloning_a_guide_to_a_different_lan_from_the_actions_dropdown(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a LAN with a guide
            $lan = Lan::create([
                'name' => 'My Great LAN',
                'start' => '2025-06-01 18:00',
                'end' => '2025-06-03 18:00',
            ]);
            $guide = Guide::create([
                'title' => 'Food and drink',
                'content' => 'Doritos and Mountain Dew',
                'lan_id' => $lan->id,
                'published' => true,
            ]);

            // And there is another LAN
            $destinationLan = Lan::create([
                'name' => 'My Next LAN',
                'start' => '2026-06-01 18:00',
                'end' => '2026-06-03 18:00',
            ]);

            // And there is a user with the role "super admin"
            $user = $this->createSuperAdmin();

            // And the super admin user is logged in
            $browser->loginAs($user);

            // When the super admin navigates to the show guide page
            $browser->visitRoute('lans.guides.show', ['lan' => $lan, 'guide' => $guide]);

            // And clicks the options dropdown button
            $browser->clickAtXPath('//button[@title="Options"]');

            // And clicks the "Clone" link
            $browser->clickLink('Clone');

            // And waits for the clone form to load
            $browser->waitForRoute('lans.guides.clone.create', ['lan' => $lan->id, 'guide' => $guide->id]);

            // Then the form is pre-filled with the source guide's values
            $browser->assertInputValue('title', 'Food and drink');
            $browser->assertSeeIn('textarea[name=content]', 'Doritos and Mountain Dew');
            $browser->assertSelected('lan_id', (string) $lan->id);

            // When the super admin edits the title and picks the other LAN
            $browser->type('title', 'Food and drink (next year)');
            $browser->select('lan_id', (string) $destinationLan->id);

            // And submits the form
            $browser->waitForReload(function (Browser $browser): void {
                $browser->script('document.querySelector("button[type=submit]").click();');
            });

            // Then they are redirected to the new guide's show page on the destination LAN
            $newGuide = Guide::where('title', 'Food and drink (next year)')->firstOrFail();
            $browser->assertRouteIs('lans.guides.show', ['lan' => $destinationLan->id, 'guide' => $newGuide->id, 'slug' => '*']);

            // And sees the new guide
            $browser->assertSee('Food and drink (next year)');
            $browser->assertSee('Doritos and Mountain Dew');

            // And the source guide is unchanged
            $this->assertSame('Food and drink', $guide->fresh()->title);
            $this->assertSame($lan->id, $guide->fresh()->lan_id);
        });
    }
}
