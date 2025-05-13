<?php

namespace App\Http\Controllers;

use App\Services\PermissionService;
use App\Services\RoleService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RolesController extends Controller
{
    private RoleService $roleService;
    private PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }


    public function index()
    {
        if (\request()->ajax()) {
            return $this->roleService->getRoleDataTables();
        }
        $permissions = $this->permissionService->getAllPermissions();
        return view('admin.roles.index', [
            'permissions' => $permissions
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Validate and save role with permissions
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $request->input('id')],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'numeric', 'exists:permissions,id'],
        ]);
        $roleData = [
            'name' => $data['name'],
            'guard_name' => 'web'
        ];
        $id = $request->input('id');
        try {
            DB::transaction(function () use ($id, $roleData, $data) {
                $role = $id > 0 ? $this->roleService->updateRole($id, $roleData) : $this->roleService->createRole($roleData);
                $role->permissions()->sync($data['permissions']);
            });
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Role saved successfully']);
    }

    /**
     * @param $id
     * @return array|Builder|Collection|Model
     */
    public function show($id)
    {
        return $this->roleService->getRoleById($id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */

    public function destroy($id)
    {
        $this->roleService->deleteRole($id);
        return response()->json(['message' => 'Role deleted successfully']);
    }

}
