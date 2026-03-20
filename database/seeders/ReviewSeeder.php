<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Government;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $govs = Government::all();

        foreach ($govs as $gov) {
            Review::create([
                'user_id' => $user->id,
                'government_id' => $gov->id,
                'rating' => rand(3,5),
                'comment' => 'خدمة ممتازة',
            ]);
        }
    }
}
