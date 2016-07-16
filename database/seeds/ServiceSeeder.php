<?php

use App\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->delete();

        Service::create([
            'name' => 'membership'
        ]);
        Service::create([
            'name' => 'student branch'
        ]);
    }
}
