<?php

declare(strict_types=1);

namespace Tests\Browser\Tests\ApiTokens;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\User;

class ManageApiTokensTest extends DuskTestCase
{
    public function test_creating_and_revoking_an_api_token(): void
    {
        $this->browse(function (Browser $browser): void {
            // Given there is a regular (non-admin) user
            $user = User::factory()->create();

            // And the user is logged in
            $browser->loginAs($user);

            // When the user navigates to the API tokens page
            $browser->visitRoute('api-tokens.index');

            // And types a name for a new token
            $browser->type('name', 'My Script');

            // And submits the creation form
            $browser->waitForReload(function (Browser $browser): void {
                $browser->press('Create API Token');
            });

            // Then the newly created token's value is shown once, in its own monospace alert
            $browser->assertVisible('.alert-warning code');
            $plainTextToken = $browser->text('.alert-warning code');
            Assert::assertNotEmpty($plainTextToken, 'Expected the token alert to contain the token value');

            // And a separate success message confirms the action, without the token in it
            $browser->assertVisible('.alert-success');
            $browser->assertDontSeeIn('.alert-success', $plainTextToken);

            // And a copy-to-clipboard button next to the token is wired to copy its exact value
            $browser->assertAttribute('.alert-warning .copy-to-clipboard', 'data-clipboard-text', $plainTextToken);

            // And the token's name appears in the list
            $browser->assertSeeIn('table', 'My Script');

            // When the user reloads the page
            $browser->visitRoute('api-tokens.index');

            // Then the raw token value is no longer shown anywhere on the page
            $browser->assertDontSee($plainTextToken);

            // But the token's name is still listed
            $browser->assertSeeIn('table', 'My Script');

            // When the user revokes the token
            $browser->press('Delete');
            $browser->acceptDialog();

            // Then the token is removed from the list
            $browser->assertDontSeeIn('table', 'My Script');
        });
    }
}
