<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function getAll(): Collection|array
    {
        return Permission::query()
            ->orderBy('name')
            ->get();
    }

    public function getById($id): Model|Collection|Builder|array|null
    {
        return Permission::query()->findOrFail($id);
    }

    public function create($data)
    {
        // Code to create a new permission in the database
        return Permission::query()->create($data);
    }

    public function update($id, $data): Model|Collection|Builder|array|null
    {
        // Code to update a permission in the database

        $permission = Permission::query()->findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public function delete($id): void
    {
        // Code to delete a permission from the database
        $permission = Permission::query()->findOrFail($id);
        $permission->delete();
    }
}
