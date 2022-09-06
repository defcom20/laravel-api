<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         //\App\Models\User::factory(2)->create();

        \App\Models\User::factory()->create([
            'name' => 'erika melgarejo',
            'email' => 'erika@gmail.com',
            'usuario' => 'erika@gmail.com',
            'password' => bcrypt("123456"),
            'type_user' => 2
        ]);
    }
}
