<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\EleveSeeder;
use Database\Seeders\ProfesseurSeeder;
use Database\Seeders\PersonnelSeeder;
use Database\Seeders\NotificationSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProfesseurSeeder::class,
            EleveSeeder::class,
            PersonnelSeeder::class,
            NotificationSeeder::class,
            ParentSeeder::class,
        ]);
    }
}
