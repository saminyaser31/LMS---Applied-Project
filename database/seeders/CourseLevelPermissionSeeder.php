<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CourseLevelPermissionSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            [
                'title' => 'view_course_level',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'create_course_level',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'edit_course_level',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'delete_course_level',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
        ]);
    }
}
