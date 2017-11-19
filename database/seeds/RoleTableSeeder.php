<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new \App\Role();
        $role_employee->name = 'Admin';
        $role_employee->description = 'Admin';
        $role_employee->save();
        $role_manager = new \App\Role();
        $role_manager->name = 'Manager';
        $role_manager->description = 'A Manager User';
        $role_manager->save();
    }
}
