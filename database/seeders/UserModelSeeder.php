<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Setup',
            'email' => 'admin@setup.com',
            'password' => bcrypt('password'),
            'phone_number' => '20001234567',
            'role_id' => 1,
            'is_writer' => 1
        ]);
    }
}
