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
        $role_manager  = \App\Role::where('name', 'user')->first();
        $admin = new \App\User();
        $admin->name = 'Admin';
        $admin->email = 'admin@hazina.devs.mobi';
        $admin->password = bcrypt('password');
        $admin->save();
        $admin->roles()->attach($role_admin);
        $manager = new \App\User();
        $manager->name = 'user';
        $manager->email = 'user@hazina.devs.mobi';
        $manager->password = bcrypt('password');
        $manager->save();
        $manager->roles()->attach($role_manager);
    }
}
