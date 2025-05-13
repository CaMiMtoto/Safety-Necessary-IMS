<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Exceptions\Exception;

class RoleService
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles(): Collection
    {
        // Additional business logic if needed
        return $this->roleRepository->getAll();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function getRoleDataTables(): \Illuminate\Http\JsonResponse
    {
        $source = $this->roleRepository->getRoleBuilder();
        return DataTables::of($source)
            ->addColumn('permissions', function ($role) {
                return "<span class='badge badge-info'>" . $role->permissions_count . "</span>";
            })
            ->addColumn('action', function (Role $role) {
                return view('admin.roles.partials.actions', compact('role'));
            })
            ->rawColumns(['action', 'permissions'])
            ->make(true);

    }

    public function getRoleById($id): Model|Collection|Builder|array|null
    {
        // Additional business logic if needed
        return $this->roleRepository->getById($id);
    }

    public function createRole($data):Role
    {
        // Additional business logic if needed
        return $this->roleRepository->create($data);
    }

    public function updateRole($id, $data): Model|Collection|Builder|array|null
    {
        // Additional business logic if needed
        return $this->roleRepository->update($id, $data);
    }

    public function deleteRole($id): Model|Collection|Builder|array|null
    {
        // Additional business logic if needed
        return $this->roleRepository->delete($id);
    }

}
