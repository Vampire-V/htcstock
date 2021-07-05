<?php

namespace App\Services\IT\Service;

use App\Enum\KPIEnum;
use App\Services\BaseService;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserService extends BaseService implements UserServiceInterface
{
    protected $periodService;
    /**
     * UserService constructor.
     *
     * @param User $model
     */
    public function __construct(User $model, TargetPeriodServiceInterface $targetPeriodServiceInterface)
    {
        parent::__construct($model);
        $this->periodService = $targetPeriodServiceInterface;
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
        return User::withTranslation()->with(['department', 'positions', 'roles', 'divisions', 'permissions','systems'])->filter($request)->notResigned()->orderBy('divisions_id', 'desc')->paginate(10);
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
            $users = User::with(['evaluates.evaluateDetail.rule.category',
            'evaluates' => fn($query) => $query->where('status',KPIEnum::approved)->orderBy('period_id'),
            'evaluates.targetperiod'])->notResigned()->orderBy('department_id','desc')->get();
            return $users;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getManager(User $user): User
    {
        try {
            return User::where('username',$user->head_id)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
