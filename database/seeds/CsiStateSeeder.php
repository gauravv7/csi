<?php

use App\CsiState;
use Illuminate\Database\Seeder;

class CsiStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('csi_states')->delete();


		$data = [
			['csi_region_id' => 1, 'state_code' => 'DL'],
			['csi_region_id' => 1, 'state_code' => 'HR'],
			['csi_region_id' => 1, 'state_code' => 'UP'],
			['csi_region_id' => 1, 'state_code' => 'UT'],
			['csi_region_id' => 2, 'state_code' => 'AS'],
			['csi_region_id' => 2, 'state_code' => 'BR'],
			['csi_region_id' => 2, 'state_code' => 'WB'],
			['csi_region_id' => 3, 'state_code' => 'GJ'],
			['csi_region_id' => 3, 'state_code' => 'MP'],
			['csi_region_id' => 3, 'state_code' => 'RJ'],
			['csi_region_id' => 4, 'state_code' => 'CT'],
			['csi_region_id' => 4, 'state_code' => 'JH'],
			['csi_region_id' => 4, 'state_code' => 'OR'],
			['csi_region_id' => 5, 'state_code' => 'AP'],
			['csi_region_id' => 5, 'state_code' => 'KA'],
			['csi_region_id' => 6, 'state_code' => 'GA'],
			['csi_region_id' => 6, 'state_code' => 'MH'],
			['csi_region_id' => 7, 'state_code' => 'KL'],
			['csi_region_id' => 7, 'state_code' => 'PY'],
			['csi_region_id' => 7, 'state_code' => 'TN']
		];

		foreach ($data as $value) {
	        CsiState::create($value);
		}

    }
}

