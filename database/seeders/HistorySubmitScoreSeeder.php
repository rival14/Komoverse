<?php

namespace Database\Seeders;

use App\Models\HistorySubmitScore;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistorySubmitScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++) {
            HistorySubmitScore::create([
                'user_id' => rand(1, 1000),
                'level' => rand(1, 100),
                'score' => rand(0, 10000),
            ]);
        }
    }
}
