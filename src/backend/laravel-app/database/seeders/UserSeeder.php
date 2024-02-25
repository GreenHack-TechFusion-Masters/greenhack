<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {


        $user = User::create([
            'email' => 'dvlmonster@gmail.com',
            'username' => 'dvlmonster',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::create([
            'email' => 'destructeurkratos@gmail.com',
            'username' => 'kratos',
            'password' => bcrypt('kratos'),
        ]);

        // recupere le role admin
        $adminRole = Role::where('name', ADMIN_ROLE['name'])->first();

        // assigner le role
        if ($adminRole) {
            $user->roles()->attach($adminRole);
            $user2->roles()->attach($adminRole);
        }

        // assigner les permission
        foreach (ADMINITRATEUR_PERMISSIONS as $permission) {
            $adminPerm = Permission::where('name', $permission['name'])->first();
            if ($adminPerm) {
                $user->permissions()->attach($adminPerm);
                $user2->permissions()->attach($adminPerm);
            }
        }
    }
}
