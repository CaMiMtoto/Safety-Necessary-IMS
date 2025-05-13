<?php

namespace App\Http\Controllers;


use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()->withCount('roles')
            ->latest()
            ->get();
        return view('admin.permissions.index', [
            'permissions' => $permissions
        ]);
    }

    public function update(Permission $permission)
    {
        $data = request()
            ->validate([
                'description' => 'required|string|max:255',
            ]);
        $permission->update([
            'description' => $data['description']
        ]);

        if (request()->ajax()) {
            return response()->json([
                'message' => 'Permission updated successfully'
            ]);
        }

        return back()
            ->with('success', 'Permission updated successfully');
    }
}
