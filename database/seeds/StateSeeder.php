<?php

use App\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->delete();

				$json = File::get(storage_path().'/json/states.json');
		$data = json_decode($json);
		foreach($data as $k => $v){
			if($k == 'RestResponse'){
				foreach($v as $key => $value){
					if($key == 'result'){
						foreach($value as $object){
							State::create(['country_code' => $object->country, 'state_code' => $object->abbr, 'name' => $object->name, 'capital' => $object->capital]);
						}
					}
				}
			}
		}
    }
}
