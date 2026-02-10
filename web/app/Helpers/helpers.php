<?php

use App\Models\Institution;

if (!function_exists('current_institution')) {
    /**
     * Mengambil institusi yang sedang aktif (berdasarkan URL path).
     */
    function current_institution(): ?Institution
    {
        // Cek apakah sudah di-bind oleh Middleware SetCurrentInstitution
        if (app()->bound('current_institution')) {
            return app('current_institution');
        }

        return null;
    }
}

if (!function_exists('current_student')) {
    /**
     * Mengambil siswa yang sedang aktif (untuk Wali Santri context).
     * @return mixed|null Student model atau null
     */
    function current_student()
    {
        // Cek apakah sudah di-bind oleh Middleware CheckStudentAccess
        if (app()->bound('current_student')) {
            return app('current_student');
        }

        return null;
    }
}

if (!function_exists('is_institution_context')) {
    /**
     * Cek apakah request saat ini dalam konteks lembaga.
     */
    function is_institution_context(): bool
    {
        return current_institution() !== null;
    }
}

if (!function_exists('is_wali_context')) {
    /**
     * Cek apakah request saat ini dalam konteks wali santri.
     */
    function is_wali_context(): bool
    {
        return current_student() !== null;
    }
}

if (!function_exists('root_institution_code')) {
    /**
     * Mengambil kode institution induk (Yayasan) dari config.
     */
    function root_institution_code(): string
    {
        return config('app.root_institution_code', 'YDTP');
    }
}

if (!function_exists('root_dashboard_url')) {
    /**
     * Mengambil URL dashboard institution induk (Yayasan).
     */
    function root_dashboard_url(): string
    {
        return url('/' . root_institution_code() . '/dashboard');
    }
}
