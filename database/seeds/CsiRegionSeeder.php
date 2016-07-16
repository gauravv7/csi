<?php

use App\CsiRegion;
use Illuminate\Database\Seeder;

class CsiRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('csi_regions')->delete();

        $data = [
            ['country_code' => 'IND', 'name' => 'Region 1'],
            ['country_code' => 'IND', 'name' => 'Region 2'],
            ['country_code' => 'IND', 'name' => 'Region 3'],
            ['country_code' => 'IND', 'name' => 'Region 4'],
            ['country_code' => 'IND', 'name' => 'Region 5'],
            ['country_code' => 'IND', 'name' => 'Region 6'],
            ['country_code' => 'IND', 'name' => 'Region 7']
        ];

        foreach ($data as $value) {
            CsiRegion::create($value);
        }
	}
}
