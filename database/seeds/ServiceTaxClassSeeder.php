<?php

use App\ServiceTaxClass;
use Illuminate\Database\Seeder;

class ServiceTaxClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_tax_classes')->delete();

        ServiceTaxClass::create(['tax_rate' => '14']);
        ServiceTaxClass::create(['tax_rate' => '0']);
    }
}
