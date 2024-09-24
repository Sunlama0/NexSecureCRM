<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création des rôles
        Role::create(['name' => 'Directeur']);
        Role::create(['name' => 'DSI']);
        Role::create(['name' => 'Technicien']);
    }
}
