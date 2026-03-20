<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Government;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $govs = Government::all();

        foreach ($govs as $gov) {
            Favorite::create([
                'user_id' => $user->id,
                'government_id' => $gov->id,
            ]);
        }
    }
}
