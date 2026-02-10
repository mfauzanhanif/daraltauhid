<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        $permissions = Permission::orderBy('name')->get();

        return Inertia::render('Yayasan/Permission/Index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Assign permissions to a role.
     */
    public function assignToRole(Request $request, $roleId)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = \App\Models\Role::findOrFail($roleId);
        $role->syncPermissions($request->permissions);

        return back()->with('success', 'Permissions have been assigned to the role.');
    }
}
