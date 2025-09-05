<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superRole = Role::where('name', 'super')->first();
        $adminRole = Role::where('name', 'admin')->first();

        User::create([
            'name' => 'Super User',
            'email' => 'super',
            'password' => bcrypt('super'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role_id' => $superRole->id
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin',
            'password' => bcrypt('admin'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role_id' => $adminRole->id
        ]);
    }
}
