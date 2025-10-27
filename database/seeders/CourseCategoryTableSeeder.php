<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategoryTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        CourseCategory::insert([
            [
                'name' => 'PRE-O LEVEL',
                'status' => 1,
            ],
            [
                'name' => 'O LEVEL',
                'status' => 1,
            ],
            [
                'name' => 'A LEVEL',
                'status' => 1,
            ],
            [
                'name' => 'GED',
                'status' => 1,
            ],
            [
                'name' => 'IELTS',
                'status' => 1,
            ],
            [
                'name' => 'SAT',
                'status' => 1,
            ],
            [
                'name' => 'TRAINING',
                'status' => 1,
            ],
            [
                'name' => 'STUDY ABROAD',
                'status' => 1,
            ],
            [
                'name' => 'EXTRA CURRICULAR ACTIVITIES',
                'status' => 1,
            ],
        ]);
    }
}
