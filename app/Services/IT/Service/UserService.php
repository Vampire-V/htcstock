<?php

namespace App\Services\IT\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\TargetPeriod;
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
            return User::whereNotIn('username', ...$username)->notResigned()->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return User::notResigned()->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return User::withTranslation()->with(['department', 'positions', 'roles', 'divisions', 'permissions', 'systems'])->filter($request)->notResigned()->orderBy('divisions_id', 'desc')->paginate(10);
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
                return User::whereIn('divisions_id', [...$division_id])->notResigned()->get();
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

    public function evaluationOfYearReport(string $year): Collection
    {
        try {
            $users = User::select('id','degree','department_id')->notResigned()
            ->with([
                'department:id,name',
                'evaluates' => fn ($query) => $query->select('id','user_id','period_id','status')->with('evaluateDetail.rule.category:id,name')->with('targetperiod')->where('status', KPIEnum::approved)->orderBy('period_id'),
            ])->orderBy('department_id', 'desc')->get();
            return $users;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getManager(User $user): User
    {
        try {
            return User::where('username', $user->head_id)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function reportStaffEvaluate(Request $request)
    {
        try {
            $period = TargetPeriod::where('name',$request->month ?? date('m'))->where('year',$request->year ?? date('Y'))->first();
            return User::NotResigned()
            ->filter($request)
            ->with(['evaluates' => fn ($query) => $query->where('period_id', $period->id)])
            ->orderBy('department_id','desc')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
