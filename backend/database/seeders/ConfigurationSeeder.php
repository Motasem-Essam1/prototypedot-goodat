<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //default currency
        Configuration::query()->create(
            [
                'key' => 'currency_symbol',
                'value' => '€ '
            ]);

        Configuration::query()->create(
            [
                'key' => 'currency_code',
                'value' => 'EUR '
            ]);
    }
}
