<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Reset Cached Roles/Permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Permissions dengan Group (diperluas untuk semua modul)
        $permissions = [
            // === CORE ===
            'User Management' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            'Role Management' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
                'assign roles',
            ],
            'Institution Management' => [
                'view institutions',
                'create institutions',
                'edit institutions',
                'delete institutions',
                'manage institution settings',
            ],
            
            // === AKADEMIK ===
            'Academic Management' => [
                'view academic years',
                'manage academic years',
                'view academic periods',
                'manage academic periods',
            ],
            'Curriculum' => [
                'view curriculum',
                'manage curriculum',
                'view subjects',
                'manage subjects',
            ],
            'Class Management' => [
                'view classes',
                'create classes',
                'edit classes',
                'delete classes',
                'assign homeroom',
            ],
            'Student Management' => [
                'view students',
                'create students',
                'edit students',
                'delete students',
                'enroll students',
                'graduate students',
            ],
            'Teacher Management' => [
                'view teachers',
                'assign teaching schedule',
            ],
            'Attendance' => [
                'view attendance',
                'record attendance',
                'report attendance',
            ],
            'Grading' => [
                'view grades',
                'input grades',
                'manage grade components',
                'print report cards',
            ],
            
            // === KEUANGAN ===
            'Finance Management' => [
                'view finance dashboard',
                'manage fee components',
                'manage billing',
            ],
            'Payment' => [
                'view payments',
                'record payments',
                'void payments',
            ],
            'Financial Report' => [
                'view financial reports',
                'export financial reports',
            ],
            
            // === EMPLOYEE ===
            'Employee Management' => [
                'view employees',
                'create employees',
                'edit employees',
                'delete employees',
                'manage employee assignments',
            ],
            
            // === ASSET ===
            'Asset Management' => [
                'view assets',
                'create assets',
                'edit assets',
                'delete assets',
                'dispose assets',
            ],
            'Asset Lending' => [
                'view asset lendings',
                'request asset lending',
                'approve asset lending',
            ],
            'Asset Maintenance' => [
                'view asset maintenances',
                'report asset damage',
                'manage asset repairs',
            ],
            'Building & Room' => [
                'view buildings',
                'manage buildings',
                'view rooms',
                'manage rooms',
            ],
            'Stock Opname' => [
                'view stock opnames',
                'conduct stock opname',
                'approve stock opname',
            ],
        ];

        foreach ($permissions as $group => $perms) {
            foreach ($perms as $permName) {
                Permission::firstOrCreate(
                    ['name' => $permName, 'guard_name' => 'web'],
                    ['group_name' => $group]
                );
            }
        }

        // 2. Global Roles (No Institution) - Untuk Central Admin
        $superAdmin = Role::firstOrCreate(
            ['name' => 'Super Admin', 'institution_id' => null, 'guard_name' => 'web']
        );
        // Super Admin gets all permissions
        $superAdmin->givePermissionTo(Permission::all());

        // 3. Institution-Scoped Roles
        $institutions = Institution::all();

        foreach ($institutions as $institution) {
            // Role Templates per Institution
            $roleTemplates = [
                'Admin' => [
                    'User Management',
                    'Role Management',
                    'Academic Management',
                    'Curriculum',
                    'Class Management',
                    'Student Management',
                    'Teacher Management',
                    'Finance Management',
                    'Employee Management',
                    'Asset Management',
                    'Building & Room',
                ],
                'Kepala Lembaga' => [
                    'Academic Management',
                    'Student Management',
                    'Teacher Management',
                    'Finance Management',
                    'Financial Report',
                    'Employee Management',
                    'Asset Management',
                ],
                'Tata Usaha' => [
                    'Student Management',
                    'Finance Management',
                    'Payment',
                    'Employee Management',
                    'Asset Lending',
                ],
                'Guru' => [
                    'Attendance',
                    'Grading',
                    'Asset Lending',
                ],
                'Wali Kelas' => [
                    'Attendance',
                    'Grading',
                    'Student Management',
                ],
                'Bendahara' => [
                    'Finance Management',
                    'Payment',
                    'Financial Report',
                ],
                'Sarpras' => [
                    'Asset Management',
                    'Asset Lending',
                    'Asset Maintenance',
                    'Building & Room',
                    'Stock Opname',
                ],
                'Siswa' => [],
                'Wali Santri' => [],
            ];

            foreach ($roleTemplates as $roleName => $allowedGroups) {
                $role = Role::firstOrCreate(
                    [
                        'name' => $roleName,
                        'institution_id' => $institution->id,
                        'guard_name' => 'web',
                    ]
                );

                // Assign Permissions based on groups
                if (!empty($allowedGroups)) {
                    $permsToAssign = Permission::whereIn('group_name', $allowedGroups)->get();
                    $role->syncPermissions($permsToAssign);
                }
            }
        }

        // 4. Clear cache after seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
