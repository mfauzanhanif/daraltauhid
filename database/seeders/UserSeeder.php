<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@superapp.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('name', 'Super Admin')->whereNull('institution_id')->first();
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        }

        // 2. Institution Admins
        $institutions = Institution::all();

        foreach ($institutions as $institution) {
            // Create Admin User for this institution
            $email = 'admin.'.strtolower($institution->code).'@daraltauhid.com';

            $adminUser = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin '.$institution->nickname ?? $institution->name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign Admin Role for this specific institution
            $adminRole = Role::where('name', 'Admin')
                ->where('institution_id', $institution->id)
                ->first();

            if ($adminRole) {
                $adminUser->assignRole($adminRole);
            }
        }

        // 3. Guru with Multiple Institutions (Example: Guru at SMP and Pondok)
        $smp = Institution::where('code', 'SMP')->first();
        $pondok = Institution::where('code', 'PONDOK')->first();

        if ($smp && $pondok) {
            $guruMulti = User::firstOrCreate(
                ['email' => 'guru.multi@daraltauhid.com'],
                [
                    'name' => 'Guru Multi Institusi',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $guruSmpRole = Role::where('name', 'Guru')->where('institution_id', $smp->id)->first();
            $guruPondokRole = Role::where('name', 'Guru')->where('institution_id', $pondok->id)->first();

            if ($guruSmpRole) {
                $guruMulti->assignRole($guruSmpRole);
            }
            if ($guruPondokRole) {
                $guruMulti->assignRole($guruPondokRole);
            }
        }
    }
}
