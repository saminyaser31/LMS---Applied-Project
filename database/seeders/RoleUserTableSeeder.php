<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->delete();

        DB::table('role_user')->insert(array (
            0 =>
            array (
                'role_id' => 1,
                'user_id' => 1,
            ),
        ));
    }
}
