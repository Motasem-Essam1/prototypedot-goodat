<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => "Ezz",
            'email' => "mohammedezzaldinzohry@gmail.com",
            'password' => bcrypt("12345678"),
            'role' => 'supper admin'
        ]);
    }
}
