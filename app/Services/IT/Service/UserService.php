<?php

namespace App\Services\IT\Service;

use App\Services\BaseService;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserService extends BaseService implements UserServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return User::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($id): bool
    {
        try {
            $user = User::find($id);
            $user->roles()->detach();
            return $user->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdownNotIn(array $username): Collection
    {
        try {
            return User::whereNotIn('username', ...$username)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return User::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return User::with(['department', 'positions', 'roles', 'divisions', 'permissions'])->filter($request)->orderBy('divisions_id', 'desc')->paginate(10);
    }

    public function email(string $email)
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function division(...$division_id): Collection
    {
        try {
            if ($division_id) {
                return User::whereIn('divisions_id', [...$division_id])->get();
            } else {
                return User::all();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function user($id): User
    {
        try {
            return User::with(['department', 'divisions', 'positions', 'roles'])->find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listOfTeamsOfEvaluate($department, $period): Collection
    {
        try {
            return User::with(['evaluate' => fn ($query) => $query->where('period_id', $period)])->where('department_id', $department)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
