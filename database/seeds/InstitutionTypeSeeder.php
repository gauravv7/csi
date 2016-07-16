<?php

use App\InstitutionType;
use Illuminate\Database\Seeder;

class InstitutionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('institution_types')->delete();

        $data = [
            ['membership_type_id' => 1, 'name' => 'school'],
            ['membership_type_id' => 1, 'name' => 'college'],
            ['membership_type_id' => 1, 'name' => 'polytechnic']
        ];

        foreach ($data as $value) {
            InstitutionType::create($value);
        }
    }
}


