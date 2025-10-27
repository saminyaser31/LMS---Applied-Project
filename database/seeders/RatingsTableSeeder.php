<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Rating::insert([
            [
                'star' => 1,
            ],
            [
                'star' => 2,
            ],
            [
                'star' => 3,
            ],
            [
                'star' => 4,
            ],
            [
                'star' => 5,
            ],
        ]);
    }
}
