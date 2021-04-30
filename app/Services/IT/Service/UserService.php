<?php

namespace App\Services\IT\Service;

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
        return User::withTranslation()->with(['department', 'positions', 'roles', 'divisions', 'permissions'])->filter($request)->orderBy('divisions_id', 'desc')->paginate(10);
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

    public function listOfTeamsOfEvaluateReport($department, $period): Collection
    {
        try {
            return User::with(['evaluate' => fn ($query) => $query->where('period_id', $period)])->where('department_id', $department)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function evaluationOfYearReport(string $year): Collection
    {
        try {
            $data = User::with(['evaluates.evaluateDetail'])->orderBy('department_id')->get();
            // \dd($data->where('user_id',51));
            $periods = $this->periodService->dropdown();
            foreach ($data as $value) {
                $total = \collect();
                foreach ($periods as $period) {
                    $evaluates = $value->evaluates->whereIn('period_id', [$period->id]);
                    $total_target = $evaluates ? $evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target')) : 0.00;
                    $total_actual = $evaluates ? $evaluates->sum(fn ($t) => $t->evaluateDetail->sum('actual')) : 0.00;
                    $total->push((object)['target' => $total_target, 'actual' => $total_actual]);
                }
                $value->total = $total;
            }

            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
