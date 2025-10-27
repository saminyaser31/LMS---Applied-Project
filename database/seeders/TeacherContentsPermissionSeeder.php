<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeacherContentsPermissionSeeder extends Seeder
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
                'title' => 'create_teacher_content',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'edit_teacher_content',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'delete_teacher_content',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'view_teacher_content',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
        ]);
    }
}
