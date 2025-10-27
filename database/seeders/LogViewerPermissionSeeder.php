<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LogViewerPermissionSeeder extends Seeder
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
                'title' => 'access_log_viewer',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'download_log_viewer_file',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'download_log_viewer_folder',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
            [
                'title' => 'delete_log_viewer',
                'created_at' => Carbon::now(),
                'updated_at' => NULL,
            ],
        ]);
    }
}
