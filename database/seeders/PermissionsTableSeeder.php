<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        DB::table('permissions')->insert(array(
            0 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 1,
                'title' => 'user_management_access',
                'updated_at' => NULL,
            ),
            1 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 2,
                'title' => 'permission_create',
                'updated_at' => NULL,
            ),
            2 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 3,
                'title' => 'permission_edit',
                'updated_at' => NULL,
            ),
            3 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 4,
                'title' => 'permission_show',
                'updated_at' => NULL,
            ),
            4 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 5,
                'title' => 'permission_delete',
                'updated_at' => NULL,
            ),
            5 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 6,
                'title' => 'permission_access',
                'updated_at' => NULL,
            ),
            6 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 7,
                'title' => 'role_create',
                'updated_at' => NULL,
            ),
            7 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 8,
                'title' => 'role_edit',
                'updated_at' => NULL,
            ),
            8 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 9,
                'title' => 'role_show',
                'updated_at' => NULL,
            ),
            9 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 10,
                'title' => 'role_delete',
                'updated_at' => NULL,
            ),
            10 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 11,
                'title' => 'role_access',
                'updated_at' => NULL,
            ),
            11 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 12,
                'title' => 'user_create',
                'updated_at' => NULL,
            ),
            12 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 13,
                'title' => 'user_edit',
                'updated_at' => NULL,
            ),
            13 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 14,
                'title' => 'user_show',
                'updated_at' => NULL,
            ),
            14 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 15,
                'title' => 'user_delete',
                'updated_at' => NULL,
            ),
            15 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 16,
                'title' => 'user_access',
                'updated_at' => NULL,
            ),
            16 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 17,
                'title' => 'audit_log_show',
                'updated_at' => NULL,
            ),
            17 =>
            array(
                'created_at' => NULL,
                'deleted_at' => NULL,
                'id' => 18,
                'title' => 'audit_log_access',
                'updated_at' => NULL,
            ),
        ));
    }
}
