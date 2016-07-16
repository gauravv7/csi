<?php

use App\ServicePeriod;
use Illuminate\Database\Seeder;

class ServicePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_periods')->delete();


        $data = [
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 1, 'name' => '01 year'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 2, 'name' => '02 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 3, 'name' => '03 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 4, 'name' => '04 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 5, 'name' => '05 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 10, 'name' => '10 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 15, 'name' => '15 years'],
			['membership_type_id' => 1, 'service_id' => 1, 'years' => 20, 'name' => '20 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 1, 'name' => '01 year'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 2, 'name' => '02 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 3, 'name' => '03 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 4, 'name' => '04 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 5, 'name' => '05 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 10, 'name' => '10 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 15, 'name' => '15 years'],
			['membership_type_id' => 2, 'service_id' => 1, 'years' => 20, 'name' => '20 years'],
			['membership_type_id' => 3, 'service_id' => 1, 'years' => 1, 'name' => '01 year'],
			['membership_type_id' => 3, 'service_id' => 1, 'years' => 2, 'name' => '02 years'],
			['membership_type_id' => 3, 'service_id' => 1, 'years' => 3, 'name' => '03 years'],
			['membership_type_id' => 3, 'service_id' => 1, 'years' => 4, 'name' => '04 years'],
			['membership_type_id' => 4, 'service_id' => 1, 'years' => 1, 'name' => '01 year'],
			['membership_type_id' => 4, 'service_id' => 1, 'years' => 2, 'name' => '02 years'],
			['membership_type_id' => 4, 'service_id' => 1, 'years' => 3, 'name' => '03 years'],
			['membership_type_id' => 4, 'service_id' => 1, 'years' => 4, 'name' => '04 years'],
			['membership_type_id' => 4, 'service_id' => 1, 'years' => 80, 'name' => 'life membership']
		];

        foreach ($data as $value) {
        	ServicePeriod::create($value);
        }
    }
}



