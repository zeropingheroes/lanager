<?php

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\WhitelistedIpRange;

class WhitelistedIpRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (WhitelistedIpRange::count()) {
            return;
        }

        $whitelistedIps = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ];

        foreach ($whitelistedIps as $whitelistedIp) {
            WhitelistedIpRange::create(['ip_range' => $whitelistedIp, 'description' => 'LAN']);
        }
    }
}
