<?php

namespace Database\Seeders;

use App\Models\CourseLevel;
use Illuminate\Database\Seeder;

class CourseLevelTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        CourseLevel::insert([
            [
                'name' => 'Edexcel O level',
                'status' => 1,
            ],
            [
                'name' => 'IAL',
                'status' => 1,
            ],
            [
                'name' => 'CAIE O level',
                'status' => 1,
            ],
            [
                'name' => 'CAIE A level',
                'status' => 1,
            ],
        ]);
    }
}
