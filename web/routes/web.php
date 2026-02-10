<?php

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\Auth\LoginController;
// Middleware
use App\Http\Controllers\FiscalPeriodController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PermissionController;
// Controllers - Auth & Core
use App\Http\Controllers\PortalController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WaliController;
use App\Http\Middleware\CheckInstitutionAccess;
use App\Http\Middleware\CheckStudentAccess;
use App\Http\Middleware\SetCurrentInstitution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| SUPER APP (app.daraltauhid.com)
|--------------------------------------------------------------------------
| Sistem Path-Based Multi-Tenancy
| - Global Routes: /admin/*, /select-portal, dll
| - Institution Routes: /{institution}/dashboard, /{institution}/employees, dll
| - Wali Routes: /wali/{student}/dashboard
*/

Route::domain(env('APP_DOMAIN'))->group(function () {

    // ============================================================
    // ZONA 1: PUBLIC / UNAUTHENTICATED ROUTES
    // ============================================================

    Route::get('/', function () {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return redirect()->to(Auth::user()->getDefaultPortalUrl());
    })->name('home');

    // ============================================================
    // ZONA 2: AUTHENTICATED GLOBAL ROUTES (No Institution Context)
    // ============================================================

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {

        // --- Institution & Student Selection ---
        Route::get('/select-institution', [LoginController::class, 'selectInstitution'])
            ->name('institution.select');
        Route::get('/select-student', [LoginController::class, 'selectStudent'])
            ->name('student.select');

        // --- Context Switching (Portal/Student Switcher) ---
        Route::get('/switch-institution/{code}', [LoginController::class, 'switchInstitution'])
            ->name('institution.switch');
        Route::get('/switch-student/{publicId}', [LoginController::class, 'switchStudent'])
            ->name('student.switch');

        // --- Settings Routes (Profile, Password, 2FA) ---
        require __DIR__.'/settings.php';
    });

    // ============================================================
    // ZONA 3: WALI SANTRI ROUTES (Student Context)
    // ============================================================

    Route::prefix('wali/{student}')
        ->middleware([
            'auth:sanctum',
            'verified',
            CheckStudentAccess::class,
        ])
        ->name('wali.')
        ->group(function () {

            Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('dashboard');
            Route::get('/academic', [WaliController::class, 'academic'])->name('academic');
            Route::get('/finance', [WaliController::class, 'finance'])->name('finance');

            // @todo: Add more wali santri routes
            // Route::get('/attendance', [WaliController::class, 'attendance'])->name('attendance');
            // Route::get('/reports', [WaliController::class, 'reports'])->name('reports');
        });

    // ============================================================
    // ZONA 4: INSTITUTION-SCOPED ROUTES (Path-Based Tenancy)
    // ============================================================
    // Pattern: /{institution}/...
    // {institution} = Institution Code (MI, SMP, PONDOK, dll)
    // Note: Using where() to prevent matching reserved paths

    Route::prefix('{institution}')
        ->where(['institution' => '^(?!select-|switch-|wali|logout|login|register|forgot-password|reset-password|verify-email|confirm-password|settings)[A-Za-z0-9_-]+$'])
        ->middleware([
            'auth:sanctum',
            'verified',
            SetCurrentInstitution::class,
            CheckInstitutionAccess::class,
        ])
        ->group(function () {

            // --- Dashboard ---
            Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('portal.dashboard');

            // --- Switch Institution (halaman pilih lembaga dalam dashboard) ---
            Route::get('/switch-institution', [LoginController::class, 'switchInstitutionPage'])
                ->name('portal.switch');

            // --- Settings Lembaga ---
            Route::get('/settings', [PortalController::class, 'settings'])->name('portal.settings');

            // --- MASTER DATA (Yayasan/Universal) ---
            // Accessible via logic permission, e.g. only YDTP users with permission can manage these
            // Note: Using 'inst' parameter to avoid conflict with {institution} prefix
            Route::resource('institutions', InstitutionController::class)
                ->parameters(['institutions' => 'inst']);
            Route::resource('academic-years', AcademicYearController::class);
            Route::resource('semesters', AcademicPeriodController::class);
            Route::resource('fiscal-periods', FiscalPeriodController::class);
            Route::resource('permissions', PermissionController::class);
            Route::post('roles/{role}/permissions', [PermissionController::class, 'assignToRole'])
                ->name('roles.permissions.assign');

            // --- MODUL AKADEMIK ---
            Route::prefix('academic')->name('academic.')->group(function () {
                // @todo: Load from Modules/Academic
                // Route::resource('students', StudentController::class);
                // Route::resource('classes', ClassController::class);
            });

            // --- MODUL KEUANGAN ---
            Route::prefix('finance')->name('finance.')->group(function () {
                // @todo: Load from Modules/Finance
                // Route::resource('invoices', InvoiceController::class);
                // Route::resource('payments', PaymentController::class);
            });

            // --- MODUL KEPEGAWAIAN ---
            Route::prefix('employees')->name('employees.')->group(function () {
                // @todo: Load from Modules/Employee
            });

            // --- MODUL ASET ---
            Route::prefix('assets')->name('assets.')->group(function () {
                // @todo: Load from Modules/Asset
            });

            // --- USER MANAGEMENT LOKAL (Institution-Scoped) ---
            Route::get('/users', function () {
                $institution = current_institution();

                return Inertia::render('Lembaga/User/Index', [
                    'users' => [],
                    'institutionCode' => $institution->code,
                ]);
            })->name('institution.users.index');

            // --- ROLE MANAGEMENT LOKAL (Institution-Scoped) ---
            // Route names: institution.roles.index, institution.roles.create, dst.
            Route::resource('roles', RoleController::class)
                ->names('institution.roles')
                ->except(['show']); // Show tidak diperlukan di level lembaga
        });
});

/*
|--------------------------------------------------------------------------
| ZONA PORTAL KHUSUS (PSB & Berita) - Placeholder
|--------------------------------------------------------------------------
*/

// Route::domain('psb.' . env('MAIN_DOMAIN', 'daraltauhid.com'))->group(function () {
//     // PSB Routes
// });

// Route::domain('sarung.' . env('MAIN_DOMAIN', 'daraltauhid.com'))->group(function () {
//     // News/Berita Routes
// });
