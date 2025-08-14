<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $svenAdmins = [
            ['email' => 'ldcorpuz@svengroup.com'],
            ['email' => 'mcapistrano@svengroup.com'],
            ['email' => 'mestella@svengroup.com'],
            ['email' => 'wtngo@svengroup.com'],
            ['email' => 'lvillacin@svengroup.com'],
            ['email' => 'jpchua@svengroup.com'],
            ['email' => 'kdpacunayen@svengroup.com'],
            ['email' => 'wtcapit@svengroup.com'],
            ['email' => 'dalincastre@svengroup.com']
        ];

        foreach( $svenAdmins as $admin) {
            User::factory($admin)->create();
        }

       // User::factory(9)->create();
       // User::factory(10, ['status' => UserStatus::Invited])->create();
       // User::factory(10, ['status' => UserStatus::Suspended])->create();
    }
}
