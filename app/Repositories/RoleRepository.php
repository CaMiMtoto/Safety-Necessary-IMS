<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getAll(): Collection
    {
        // Code to retrieve all roles from the database
        return Role::all();
    }

    public function getRoleBuilder(): Builder
    {
        return Role::query()
            ->withCount('permissions');
    }

    public function getById($id): Model|Collection|Builder|array|null
    {
        // Code to retrieve a role by ID from the database
        return Role::query()->with('permissions')->findOrFail($id);
    }

    public function create($data)
    {
        // Code to create a new role in the database
        return Role::query()->create($data);
    }

    public function update($id, $data): Model|Collection|Builder|array|null
    {
        // Code to update a role in the database
        $role = Role::query()->findOrFail($id);
        $role->update($data);

        return $role;
    }

    public function delete($id): Model|Collection|Builder|array|null
    {
        // Code to delete a role from the database
        $role = Role::query()->findOrFail($id);
        $role->delete();
        return $role;
    }
}
