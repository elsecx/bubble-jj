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
        $operatorRole = Role::where('name', 'operator')->first();

        User::create([
            'name' => 'Super User',
            'email' => 'super',
            'password' => bcrypt('super'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role_id' => $superRole->id
        ]);

        User::create([
            'name' => 'Operator User',
            'email' => 'operator',
            'password' => bcrypt('operator'),
            'email_verified_at' => now(),
            'is_active' => true,
            'role_id' => $operatorRole->id
        ]);
    }
}
