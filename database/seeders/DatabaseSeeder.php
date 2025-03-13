<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            [
                'us_fname' => 'Pakkapon',
                'us_lname' => 'Chomchoey',
                'us_email' => '66160080@go.buu.ac.th',
                'us_role' => 'CEO',
            ],
            [
                'us_fname' => 'Nontaphat',
                'us_lname' => 'Naja',
                'us_email' => '66160104@go.buu.ac.th',
                'us_role' => 'SalesSupervisor',
            ],
        ]);
    }
}
