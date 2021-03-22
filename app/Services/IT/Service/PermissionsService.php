<?php

namespace App\Services\IT\Service;

use App\Models\Permission;
use App\Services\BaseService;
use App\Services\IT\Interfaces\PermissionsServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class PermissionsService extends BaseService implements PermissionsServiceInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Permission::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id): bool
    {
        try {
            $permission = Permission::find($id);
            $permission->roles()->detach();
            return $permission->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Permission::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function systemIn(...$system): Collection
    {
        try {
            return Permission::whereIn('system_id',...$system)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
