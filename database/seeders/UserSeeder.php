<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Cesar Osgual',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'created_at' => now() 
            ],
            [
                'name' => 'Armado Alarcon',
                'email' => 'user@gmail.com',
                'password' => Hash::make('user123'),
                'created_at' => now()
            ] 
        ];
        
        DB::table('users')->insert($users);
    }
}
