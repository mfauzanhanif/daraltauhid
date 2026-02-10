<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Institution;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $institutionId = $request->query('institution_id');

        $query = Role::with('institution');

        if ($institutionId) {
            $query->where('institution_id', $institutionId);
        }

        $roles = $query->orderBy('created_at', 'desc')->get();
        $institutions = Institution::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Yayasan/Role/Index', [
            'roles' => $roles,
            'institutions' => $institutions,
            'filters' => [
                'institution_id' => $institutionId,
            ],
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $institutions = Institution::orderBy('name')->get(['id', 'name']);
        $permissions = Permission::all()->groupBy('group_name'); // Assuming permissions have group_name or similar

        return Inertia::render('Yayasan/Role/Create', [
            'institutions' => $institutions,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('institution.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        $role->load(['institution', 'permissions']);

        return Inertia::render('Yayasan/Role/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $role->load('permissions');
        $institutions = Institution::orderBy('name')->get(['id', 'name']);
        $permissions = Permission::all(); // Simplified for now

        return Inertia::render('Yayasan/Role/Edit', [
            'role' => $role,
            'institutions' => $institutions,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()
            ->route('institution.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus role yang sedang digunakan oleh user.');
        }

        $role->delete();

        return redirect()
            ->route('institution.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
