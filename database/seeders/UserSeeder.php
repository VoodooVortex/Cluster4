<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('users')->insert([
            [
                'us_fname' => 'Pakkapon',
                'us_lname' => 'Chomchoey',
                'us_email' => '66160080@go.buu.ac.th',
                'us_role' => 'CEO',
            ],
            [
                'us_fname' => 'Nontapat',
                'us_lname' => 'Sinthum',
                'us_email' => '66160104@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
        ]);
    }
}
