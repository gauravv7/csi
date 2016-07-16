<?php

use App\MembershipType;
use Illuminate\Database\Seeder;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_types')->delete();

        $data = [
            ['membership_id' => 1, 'type' => 'academic', 'prefix' => 'IM'],
            ['membership_id' => 1, 'type' => 'non-academic', 'prefix' => 'IM'],
            ['membership_id' => 2, 'type' => 'student', 'prefix' => 'SM'],
            ['membership_id' => 2, 'type' => 'professional', 'prefix' => 'CM']
        ];
        
        foreach ($data as $value) {
            MembershipType::create($value);
        }
    }
}




