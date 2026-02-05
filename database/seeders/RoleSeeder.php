<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Global Roles (No Institution)
        Role::firstOrCreate(
            ['name' => 'Super Admin', 'institution_id' => null, 'guard_name' => 'web']
        );

        // 2. Institution Roles
        $institutions = Institution::all();

        foreach ($institutions as $institution) {
            $roles = ['Admin', 'Staff', 'Guru', 'Siswa', 'Wali Santri'];

            foreach ($roles as $roleName) {
                Role::firstOrCreate(
                    [
                        'name' => $roleName,
                        'institution_id' => $institution->id,
                        'guard_name' => 'web'
                    ]
                );
            }
        }
    }
}
