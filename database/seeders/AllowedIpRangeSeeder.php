<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\AllowedIpRange;

class AllowedIpRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (AllowedIpRange::count()) {
            return;
        }

        $allowedIpRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ];

        foreach ($allowedIpRanges as $allowedIpRange) {
            AllowedIpRange::create(['ip_range' => $allowedIpRange, 'description' => 'LAN']);
        }
    }
}
