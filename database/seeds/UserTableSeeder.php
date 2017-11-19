<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role_admin = \App\Role::where('name', 'admin')->first();
        $role_manager  = \App\Role::where('name', 'manager')->first();
        $admin = new \App\User();
        $admin->name = 'Admin';
        $admin->email = 'admin@unicredit.devs.mobi';
        $admin->password = bcrypt('unicredit696');
        $admin->save();
        $admin->roles()->attach($role_admin);
        $manager = new \App\User();
        $manager->name = 'Manager';
        $manager->email = 'manager@example.com';
        $manager->password = bcrypt('unicredit696');
        $manager->save();
        $manager->roles()->attach($role_manager);
    }
}
