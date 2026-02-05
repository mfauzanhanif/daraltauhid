<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Yayasan Dar Al Tauhid Pusat (Root)
        $yayasan = Institution::updateOrCreate(
            ['code' => 'YDTP'],
            [
                'slug' => 'yayasan-dar-al-tauhid-pusat',
                'name' => 'Yayasan Dar Al Tauhid Pusat',
                'category' => 'YAYASAN',
                'type' => 'YAYASAN',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => null,
                'domain' => 'yayasan.daraltauhid.com',
                'email' => 'yayasan@daraltauhid.com',
            ]
        );

        // 2. Pondok Pesantren Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'PONDOK'],
            [
                'slug' => 'pondok-pesantren-dar-al-tauhid',
                'name' => 'Pondok Pesantren Dar Al Tauhid',
                'category' => 'PONDOK',
                'type' => 'PONDOK',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'pondok.daraltauhid.com',
                'email' => 'pondok@daraltauhid.com',
            ]
        );

        // 3. SMP Plus Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'SMP'],
            [
                'slug' => 'smp-plus-dar-al-tauhid',
                'name' => 'SMP Plus Dar Al Tauhid',
                'category' => 'FORMAL',
                'type' => 'SMP',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'smp.daraltauhid.com',
                'email' => 'smp@daraltauhid.com',
            ]
        );

        // 4. MA Nusantara
        Institution::updateOrCreate(
            ['code' => 'MA'],
            [
                'slug' => 'mas-nusantara-arjawinangun',
                'name' => 'MAS Nusantara Arjawinangun',
                'category' => 'FORMAL',
                'type' => 'MA',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'ma.daraltauhid.com',
                'email' => 'ma@daraltauhid.com',
            ]
        );

        // 5. Madrasah Dar Al Tauhid (Asumsi MI)
        Institution::updateOrCreate(
            ['code' => 'Madrasah'],
            [
                'slug' => 'madrasah-dar-al-tauhid',
                'name' => 'Madrasah Dar Al Tauhid',
                'category' => 'FORMAL',
                'type' => 'MI',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'mi.daraltauhid.com',
                'email' => 'mi@daraltauhid.com',
            ]
        );

        // 6. MtsN 3 Cirebon
        Institution::updateOrCreate(
            ['code' => 'MTSN3'],
            [
                'slug' => 'mtsn-3-cirebon',
                'name' => 'MtsN 3 Cirebon',
                'category' => 'FORMAL',
                'type' => 'MTs',
                'is_internal' => false,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'mtsn3cirebon.com',
                'email' => 'mtsn3cirebon@gmail.com',
            ]
        );
    }
}
