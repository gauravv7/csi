<?php

use App\PaymentMode;
use Illuminate\Database\Seeder;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_modes')->delete();

        $data = [
            ['name' => 'cheque'],
            ['name' => 'demand draft'],
            ['name' => 'cash']
        ];

        foreach ($data as $value) {
            PaymentMode::create($value);
        }
    }
}



