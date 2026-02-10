<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $address = 'Jl. KH. A. Syathori, RT/RW 02/06, Desa Arjawinangun, Kec. Arjawinangun, Kab. Cirebon, Jawa Barat - 45162';

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
                'address' => $address,
            ]
        );

        // 2. Pondok Pesantren Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'PPDT'],
            [
                'slug' => 'pondok-pesantren-dar-al-tauhid',
                'name' => 'Pondok Pesantren Dar Al Tauhid',
                'category' => 'PONDOK',
                'type' => 'PONDOK',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'daraltauhid.com',
                'email' => 'pondok@daraltauhid.com',
                'address' => $address,
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
                'domain' => 'smp-plus.daraltauhid.com',
                'email' => 'smp@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 4. MA Nusantara Cirebon
        Institution::updateOrCreate(
            ['code' => 'MA'],
            [
                'slug' => 'ma-nusantara-cirebon',
                'name' => 'MA Nusantara Cirebon',
                'category' => 'FORMAL',
                'type' => 'MA',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'manuscirebon.com',
                'email' => 'ma@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 5. MI Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'MI'],
            [
                'slug' => 'mi-dar-al-tauhid',
                'name' => 'MI Dar Al Tauhid',
                'category' => 'FORMAL',
                'type' => 'MI',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'mis.daraltauhid.com',
                'email' => 'mi@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 6. Madrasah Dar Al Tauhid (Madrasah Diniyah)
        Institution::updateOrCreate(
            ['code' => 'MDT'],
            [
                'slug' => 'madrasah-dar-al-tauhid',
                'name' => 'Madrasah Dar Al Tauhid',
                'category' => 'NON_FORMAL',
                'type' => 'Madrasah',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'madrasah.daraltauhid.com',
                'email' => 'madrasah@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 7. TK Islam Wathaniyah
        Institution::updateOrCreate(
            ['code' => 'TK'],
            [
                'slug' => 'tk-islam-wathaniyah',
                'name' => 'TK Islam Wathaniyah',
                'category' => 'FORMAL',
                'type' => 'TK',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'tk-islam.wathaniyah.sch.id',
                'email' => 'tk@wathaniyah.sch.id',
                'address' => $address,
            ]
        );

        // 8. SLB ABC Wathaniyah
        Institution::updateOrCreate(
            ['code' => 'SLB'],
            [
                'slug' => 'slb-abc-wathaniyah',
                'name' => 'SLB ABC Wathaniyah',
                'category' => 'FORMAL',
                'type' => 'SLB',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'slb-abc.wathaniyah.sch.id',
                'email' => 'slb@wathaniyah.sch.id',
                'address' => $address,
            ]
        );

        // 9. TPQ Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'TPQ'],
            [
                'slug' => 'tpq-dar-al-tauhid',
                'name' => 'TPQ Dar Al Tauhid',
                'category' => 'NON_FORMAL',
                'type' => 'TPQ',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'tpq.daraltauhid.com',
                'email' => 'tpq@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 10. MDTA Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'MDTA'],
            [
                'slug' => 'mdta-dar-al-tauhid',
                'name' => 'MDTA Dar Al Tauhid',
                'category' => 'NON_FORMAL',
                'type' => 'MDTA',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'mdta.daraltauhid.com',
                'email' => 'mdta@daraltauhid.com',
                'address' => $address,
            ]
        );

        // 11. LKSA Dar Al Tauhid
        Institution::updateOrCreate(
            ['code' => 'LKSA'],
            [
                'slug' => 'lksa-dar-al-tauhid',
                'name' => 'LKSA Dar Al Tauhid',
                'category' => 'SOSIAL',
                'type' => 'LKSA',
                'is_internal' => true,
                'is_active' => true,
                'parent_id' => $yayasan->id,
                'domain' => 'lksa.daraltauhid.com',
                'email' => 'lksa@daraltauhid.com',
                'address' => $address,
            ]
        );
    }
}
