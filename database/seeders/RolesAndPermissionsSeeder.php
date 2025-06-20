<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Создание ролей
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $teamMemberRole = Role::firstOrCreate(['name' => 'team_member']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Создание прав
        $createProjects = Permission::firstOrCreate(['name' => 'create projects']);
        $editProjects = Permission::firstOrCreate(['name' => 'edit projects']);
        $deleteProjects = Permission::firstOrCreate(['name' => 'delete projects']);
        $viewProjects = Permission::firstOrCreate(['name' => 'view projects']);
        $moveTasks = Permission::firstOrCreate(['name' => 'move tasks']);

        // Назначение прав ролям
        $managerRole->givePermissionTo([$createProjects, $editProjects, $deleteProjects, $viewProjects]);
        $teamMemberRole->givePermissionTo([$viewProjects, $moveTasks]);
        $adminRole->givePermissionTo([$createProjects, $editProjects, $deleteProjects, $viewProjects]); // Назначаем все права администратору
        $userRole->givePermissionTo($viewProjects);

        echo "Roles and permissions seeded successfully.\n";
    }
}
