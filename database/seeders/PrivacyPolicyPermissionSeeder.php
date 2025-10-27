<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PrivacyPolicyPermissionSeeder extends Seeder
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
                'title' => 'access_privacy_policy',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'create_privacy_policy',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'edit_privacy_policy',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'show_privacy_policy',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'delete_privacy_policy',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
        ]);
    }
}
