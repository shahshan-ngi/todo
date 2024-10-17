<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'edit tasks']);
        Permission::create(['name' => 'delete tasks']);
        Permission::create(['name'=>'create tasks']);
        Permission::create(['name' => 'view tasks']);

        $admin = Role::where('name', 'admin')->first();
        $editor = Role::where('name', 'editor')->first();
        $viewer = Role::where('name', 'viewer')->first();
     
        $admin->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['create tasks', 'edit tasks', 'view tasks']);
        $viewer->givePermissionTo('view tasks');


    }
}
