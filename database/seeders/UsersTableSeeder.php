<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->delete();
        User::create([
            'approved' => 1,
            'created_at' => Carbon::now(),
            'email' => 'admin@lms.com',
            'email_verified_at' => Carbon::now(),
            'name' => 'LMS Admin',
            'password' => Hash::make('btteam2k19'), //btteam2k19
            'remember_token' => 'bQmUJrxGKc6oMXYfcR5HOMVgHlGnLhZQP4Q86dKqfX6xMTo62nA2kmjXrTpU',
            'user_type' => User::ADMIN,
        ]);
    }
}
