<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run(): void
{
    $this->call([
        RolePermissionSeeder::class,
        UserSeeder::class,
        GovernmentCategorySeeder::class,
        GovernmentSeeder::class,
        OfferServiceSeeder::class,
        GovernmentOfferServiceSeeder::class,
        FavoriteSeeder::class,
        ReviewSeeder::class,

    ]);
}

}
