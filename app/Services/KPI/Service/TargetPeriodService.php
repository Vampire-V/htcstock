<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\TargetPeriod;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TargetPeriodService extends BaseService implements TargetPeriodServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param TargetPeriod $model
     */
    public function __construct(TargetPeriod $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        try {
            return TargetPeriod::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byYear(string $year)
    {
        try {
            return TargetPeriod::where('year', $year)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byYearForEvaluate($year, $user): Collection
    {
        try {
            return TargetPeriod::with(['evaluate' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->where('year', $year)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return TargetPeriod::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function selfApprovedEvaluationOfyear(string $year): Collection
    {
        try {
            return TargetPeriod::with([
                'evaluates' => function ($query) {
                    $query->select('id', 'user_id', 'period_id', 'status')
                        ->with('evaluateDetail')
                        ->where(['status' => KPIEnum::draft, 'user_id' => \auth()->id()]);
                }
            ])->where('year', $year)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deptApprovedEvaluationOfyear(string $year): Collection
    {
        try {
            return TargetPeriod::with([
                'evaluates' => function ($query) {
                    $query->select('id', 'user_id', 'period_id', 'status')
                        ->whereHas('user', fn ($user) => $user->where('department_id', \auth()->user()->department_id))
                        ->with(['evaluateDetail'])
                        ->where('status', KPIEnum::approved);
                }
            ])->where('year', $year)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filterIndex(Request $request)
    {
        try {
            return TargetPeriod::filter($request)->orderBy('year', 'desc')->orderBy('name', 'asc')->paginate(12);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
