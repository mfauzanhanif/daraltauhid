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
            ['email' => 'admin@daraltauhid.com'],
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

        // // Assign additional institution-scoped roles to Super Admin
        // // (The Two Hats Rule: Global Admin can also have specific roles in institutions)
        // $pondok = Institution::where('code', 'PPDT')->orWhere('code', 'PONDOK')->first();
        // $madrasah = Institution::where('code', 'MDDT')->orWhere('code', 'MADRASAH')->first();
        // $mi = Institution::where('code', 'MISDT')->orWhere('code', 'MI')->orWhere('code', 'MIS')->first();

        // // 1. Kepala Lembaga di Pondok
        // if ($pondok) {
        //     $kepalaPondokRole = Role::where('name', 'Kepala Lembaga')
        //         ->where('institution_id', $pondok->id)
        //         ->first();
        //     if ($kepalaPondokRole && !$superAdmin->hasRole($kepalaPondokRole)) {
        //         $superAdmin->assignRole($kepalaPondokRole);
        //     }
        // }

        // // 2. Guru di Madrasah
        // if ($madrasah) {
        //     $guruMadrasahRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $madrasah->id)
        //         ->first();
        //     if ($guruMadrasahRole && !$superAdmin->hasRole($guruMadrasahRole)) {
        //         $superAdmin->assignRole($guruMadrasahRole);
        //     }
        // }

        // // 3 & 4. Admin + Guru di MI
        // if ($mi) {
        //     // Admin di MI
        //     $adminMiRole = Role::where('name', 'Admin')
        //         ->where('institution_id', $mi->id)
        //         ->first();
        //     if ($adminMiRole && !$superAdmin->hasRole($adminMiRole)) {
        //         $superAdmin->assignRole($adminMiRole);
        //     }

        //     // Guru di MI
        //     $guruMiRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $mi->id)
        //         ->first();
        //     if ($guruMiRole && !$superAdmin->hasRole($guruMiRole)) {
        //         $superAdmin->assignRole($guruMiRole);
        //     }
        // }

        // // 2. Institution Admins
        // $institutions = Institution::all();

        // foreach ($institutions as $institution) {
        //     // Create Admin User for this institution
        //     $email = 'admin.' . strtolower($institution->code) . '@daraltauhid.com';

        //     $adminUser = User::firstOrCreate(
        //         ['email' => $email],
        //         [
        //             'name' => 'Admin ' . $institution->nickname ?? $institution->name,
        //             'password' => Hash::make('password'),
        //             'email_verified_at' => now(),
        //         ]
        //     );

        //     // Assign Admin Role for this specific institution
        //     $adminRole = Role::where('name', 'Admin')
        //         ->where('institution_id', $institution->id)
        //         ->first();

        //     if ($adminRole) {
        //         $adminUser->assignRole($adminRole);
        //     }
        // }

        // // 3. Guru with Multiple Institutions (Pondok, Madrasah, SMP)
        // $pondok = Institution::where('code', 'PPDT')->first();    // Pondok Pesantren
        // $madrasah = Institution::where('code', 'MISDT')->first(); // Madrasah Ibtidaiyah
        // $smp = Institution::where('code', 'SMPDT')->first();      // SMP Plus

        // // Fallback jika code berbeda
        // if (!$pondok) {
        //     $pondok = Institution::where('code', 'PONDOK')->first();
        // }
        // if (!$madrasah) {
        //     $madrasah = Institution::where('code', 'MI')->orWhere('code', 'MIS')->first();
        // }
        // if (!$smp) {
        //     $smp = Institution::where('code', 'SMP')->first();
        // }

        // // Guru Multi-Institusi: Mengajar di Pondok, Madrasah, dan SMP
        // $guruMulti = User::firstOrCreate(
        //     ['email' => 'guru.multi@daraltauhid.com'],
        //     [
        //         'name' => 'Ustadz Ahmad (Guru Multi Lembaga)',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]
        // );

        // // Assign role Guru di setiap lembaga
        // $institutionsForGuru = collect([$pondok, $madrasah, $smp])->filter();

        // foreach ($institutionsForGuru as $institution) {
        //     $guruRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $institution->id)
        //         ->first();

        //     if ($guruRole && !$guruMulti->hasRole($guruRole)) {
        //         $guruMulti->assignRole($guruRole);
        //     }
        // }

        // // 4. Guru khusus Pondok saja
        // if ($pondok) {
        //     $guruPondok = User::firstOrCreate(
        //         ['email' => 'guru.pondok@daraltauhid.com'],
        //         [
        //             'name' => 'Ustadz Hasan (Guru Pondok)',
        //             'password' => Hash::make('password'),
        //             'email_verified_at' => now(),
        //         ]
        //     );

        //     $guruPondokRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $pondok->id)
        //         ->first();

        //     if ($guruPondokRole && !$guruPondok->hasRole($guruPondokRole)) {
        //         $guruPondok->assignRole($guruPondokRole);
        //     }
        // }

        // // 5. Guru khusus Madrasah saja
        // if ($madrasah) {
        //     $guruMadrasah = User::firstOrCreate(
        //         ['email' => 'guru.madrasah@daraltauhid.com'],
        //         [
        //             'name' => 'Ustadzah Fatimah (Guru Madrasah)',
        //             'password' => Hash::make('password'),
        //             'email_verified_at' => now(),
        //         ]
        //     );

        //     $guruMadrasahRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $madrasah->id)
        //         ->first();

        //     if ($guruMadrasahRole && !$guruMadrasah->hasRole($guruMadrasahRole)) {
        //         $guruMadrasah->assignRole($guruMadrasahRole);
        //     }
        // }

        // // 6. Guru khusus SMP saja
        // if ($smp) {
        //     $guruSmp = User::firstOrCreate(
        //         ['email' => 'guru.smp@daraltauhid.com'],
        //         [
        //             'name' => 'Pak Budi (Guru SMP)',
        //             'password' => Hash::make('password'),
        //             'email_verified_at' => now(),
        //         ]
        //     );

        //     $guruSmpRole = Role::where('name', 'Guru')
        //         ->where('institution_id', $smp->id)
        //         ->first();

        //     if ($guruSmpRole && !$guruSmp->hasRole($guruSmpRole)) {
        //         $guruSmp->assignRole($guruSmpRole);
        //     }
        // }
    }
}
