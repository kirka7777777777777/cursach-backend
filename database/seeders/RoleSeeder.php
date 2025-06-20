<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Убедитесь, что модель Role существует и импортирована

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создать роль 'user', если она еще не существует
        Role::firstOrCreate(['name' => 'user']);
        // Можно добавить другие роли, например 'admin'
        Role::firstOrCreate(['name' => 'admin']);
    }
}
