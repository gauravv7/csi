<?php

use App\Salutation;
use Illuminate\Database\Seeder;

class SalutationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salutations')->delete();

        Salutation::create(['name' => 'Mr']);
        Salutation::create(['name' => 'Miss']);
        Salutation::create(['name' => 'Mrs']);
        Salutation::create(['name' => 'Dr']);
        Salutation::create(['name' => 'Prof']);
    }
}
