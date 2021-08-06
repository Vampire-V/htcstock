<?php

namespace App\Services\IT\Service;

use App\Models\Department;
use App\Models\User;
use App\Services\BaseService;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService extends BaseService implements DepartmentServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Department $model
     */
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Department::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Department::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdownForUser(): Collection
    {
        try {
            $users = User::select('department_id')->notResigned()->where('divisions_id', \auth()->user()->divisions_id)->groupBy('department_id')->get();
            return Department::find($users->pluck('department_id'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
