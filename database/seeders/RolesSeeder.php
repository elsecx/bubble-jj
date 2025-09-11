<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super',
                'label' => 'Super Admin',
                'direct' => 'admin.dashboard'
            ],
            [
                'name' => 'operator',
                'label' => 'Operator',
                'direct' => 'admin.dashboard'
            ],
            [
                'name' => 'user',
                'label' => 'Pengguna',
                'direct' => 'user.dashboard'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
