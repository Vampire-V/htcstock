<?php

namespace App\Services\IT\Service;

use App\Models\Role;
use App\Services\BaseService;
use App\Services\IT\Interfaces\RoleServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RoleService extends BaseService implements RoleServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function delete($id): bool
    {
        try {
            $user = Role::find($id);
            $user->roles()->detach();
            return $user->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
    public function dropdown(...$slug): Collection
    {
        try {
            if ($slug) {
                return Role::whereNotIn('slug',...$slug)->get();
            } else {
                return Role::all();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return Role::with('users')->filter($request)->paginate(10);
    }

    public function roleIn(...$slug): Collection
    {
        try {
            return Role::with('users')->whereIn('slug',...$slug)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
