<?php

use App\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('memberships')->delete();

        Membership::create(['type' => 'institutional']);
        Membership::create(['type' => 'individual']);
    }
}
