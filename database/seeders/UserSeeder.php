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
                'us_fname' => 'Pk',
                'us_lname' => 'Naja',
                'us_email' => 'Pakkapon2547@gmail.com',
                'us_role' => 'Sales Supervisor',
            ],
            [
                'us_fname' => 'Pk2',
                'us_lname' => 'Naja',
                'us_email' => 'Pokemonforb@gmail.com',
                'us_role' => 'Sales',
            ],
            [
                'us_fname' => 'Nontapat',
                'us_lname' => 'Sinthum',
                'us_email' => '66160104@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
            [
                'us_fname' => 'Yothin',
                'us_lname' => 'Sisaitham',
                'us_email' => '66160088@go.buu.ac.th',
                'us_role' => 'Sales',
            ],
            [
                'us_fname' => 'Chanwit',
                'us_lname' => 'Muangma',
                'us_email' => '66160224@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
            [
                'us_fname' => 'Thanapat',
                'us_lname' => 'Channgam',
                'us_email' => '66160226@go.buu.ac.th',
                'us_role' => 'CEO',
            ],
            [
                'us_fname' => 'Thakdanai',
                'us_lname' => 'Makmi',
                'us_email' => '66160355@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
            [
                'us_fname' => 'Worrawat',
                'us_lname' => 'Namwat',
                'us_email' => '66160372@go.buu.ac.th',
                'us_role' => 'CEO',
            ],
            [
                'us_fname' => 'Samitanan',
                'us_lname' => 'Taenil',
                'us_email' => '66160376@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
            [
                'us_fname' => 'Suthasinee',
                'us_lname' => 'Wongphatklang',
                'us_email' => '66160379@go.buu.ac.th',
                'us_role' => 'CEO',
            ],
            [
                'us_fname' => 'Aninthita',
                'us_lname' => 'Prasoetsang',
                'us_email' => '66160381@go.buu.ac.th',
                'us_role' => 'Sales Supervisor',
            ],
        ]);
    }
}
