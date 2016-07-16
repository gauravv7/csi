<?php

use App\AddressType;
use Illuminate\Database\Seeder;

class AddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('address_types')->delete();

        AddressType::create(['type' => 'registered address']);
        AddressType::create(['type' => 'permanent address']);
        AddressType::create(['type' => 'billing address']);
        AddressType::create(['type' => 'mailing address']);
    }
}
