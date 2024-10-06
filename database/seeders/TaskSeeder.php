<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            [
                'user_id' => 1,
                'name_task' => 'Solucionar bug en sistema',
                'description_task' => 'El sistema uno presenta errores en la pantalla de perfil de usuario',
                'status' => 'Pendiente',
                'available' => 1,
                'created_at' => now()
            ],
            [
                'user_id' => 2,
                'name_task' => 'Normalizar la base de datos',
                'description_task' => 'Hacer la normalización de la base de datos, tablas, campos',
                'status' => 'Pendiente',
                'available' => 1,
                'created_at' => now()
            ]
        ];
        DB::table("tasks")->insert($tasks);
    }
}
