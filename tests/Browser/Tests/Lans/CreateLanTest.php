<?php

namespace Tests\Browser\Tests\Lans;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Lans\LanIndex;
use Tests\DuskTestCase;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class CreateLanTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testCreatingLan()
    {
        // TODO: Move superadmin creation to somewhere reusable
        $user = User::factory()
            ->has(
                UserOAuthAccount::factory()->count(1),
                'accounts'
            )
            ->create();

        $role = Role::where('name', 'super-admin')->first();
        $user->roles()->attach($role->id, ['assigned_by' => $user->id]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(new LanIndex())
                ->click('@create')
                ->waitForRoute('lans.create')
                ->type('name', 'My Great LAN')
                ->type('start', '2022-09-23 18:00')
                ->type('end', '2022-09-25 18:00')
                ->press('@submit')
                ->assertRouteIs('lans.events.index', '*')
                ->assertSee('My Great LAN');
        });
    }
}
