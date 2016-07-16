<?php

use App\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->delete();

        Currency::create(['currency_code' => 'INR', 'name' => 'indian rupee']);
        Currency::create(['currency_code' => 'USD', 'name' => 'US dollar']);
    }
}
