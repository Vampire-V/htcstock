<?php

namespace App\Http\Controllers\KPI;

use App\Enum\KPIEnum;
use App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ALL\UserEvaluateResource;
use App\Http\Resources\KPI\EvaluateResource;
use App\Models\KPI\Evaluate;
use App\Models\KPI\TargetPeriod;
use App\Models\User;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\IT\Service\DepartmentService;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $targetPeriodService, $userService, $ruleService, $evaluateService, $departmentService;
    public function __construct(
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        DepartmentService $departmentService
    ) {
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->departmentService = $departmentService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $selectedYear = empty($request->year) ? date('Y') : $request->year;
        // $evaluations =  Evaluate::with('user', 'evaluateDetail.rule', 'targetperiod')->where('status', KPIEnum::approved)->get();
        // $this->calculation_summary($evaluations);
        // $ofSelf = $this->targetPeriodService->selfApprovedEvaluationOfyear($selectedYear);
        // $ofDept = $this->targetPeriodService->deptApprovedEvaluationOfyear($selectedYear);
        // $periods = $this->targetPeriodService->query()->where('year', $selectedYear)->get();
        // $users = $this->userService->evaluationOfYearReport($selectedYear);
        // $rules = $this->ruleService->rulesInEvaluationReport($selectedYear);
        $departments = $this->departmentService->dropdown();
        $degree = \collect([KPIEnum::one, KPIEnum::two, KPIEnum::tree]);


        return \view('kpi.home', \compact('departments', 'degree'));
    }

    public function report_your_self($year)
    {
        try {
            $result = $this->targetPeriodService->selfApprovedEvaluationOfyear($year);
            // dd($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($result, 'get report your self', 200);
    }

    // Controller ที่ใช้ url test http://127.0.0.1:8000/kpi/dashboard/rule-of-year/2021/report
    public function report_rule_of_year($year)
    {
        try {
            $rules = $this->ruleService->rulesInEvaluationReport($year);
            $periods = $this->targetPeriodService->query()->where('year', $year)->get();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(['rules' => $rules, 'periods' => $periods], 'get report rules of year', 200);
    }

    // Controller ที่ใช้ url test http://127.0.0.1:8000/kpi/dashboard/rule-of-year/2021/report
    public function report_staff_evaluate_year($year)
    {
        try {
            $users = $this->userService->evaluationOfYearReport($year);
            $is_last = \collect(['03', '06', '09', '12', 'March', 'June', 'September', 'Depcember']);

            for ($i = 0; $i < $users->count(); $i++) {
                $user = $users[$i];
                $user->evaluates->each(function ($item) use ($is_last) {
                    if ($is_last->contains($item->targetperiod->name)) {
                        $item->weigth = config('kpi.weight')['quarter'];
                    } else {
                        $item->weigth = config('kpi.weight')['month'];
                    }
                });
                $this->calculation_summary($user->evaluates);
                EvaluateResource::collection($user->evaluates);
            }
            $periods = $this->targetPeriodService->query()->where('year', $year)->get();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(['users' => $users, 'periods' => $periods], 'get report staff evaluate of year', 200);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reportscore(Request $request)
    {
        try {
            $evaluations = $this->evaluateService->scoreFilter($request);
            $is_last = \collect(['03', '06', '09', '12', 'March', 'June', 'September', 'Depcember']);
            $evaluations->each(function ($item) use ($is_last) {
                if ($is_last->contains($item->targetperiod->name) || $item->user->degree === KPIEnum::one) {
                    $item->weigth = config('kpi.weight')['quarter'];
                } else {
                    $item->weigth = config('kpi.weight')['month'];
                }
            });
            $this->calculation_summary($evaluations);
            $result = EvaluateResource::collection($evaluations);
        } catch (\Exception $e) {
            return $this->errorResponse($e, 500);
        }
        return $this->successResponse($result, 200);
    }

    public function weigthconfig(Request $request)
    {
        $is_last = \collect(['03', '06', '09', '12', 'March', 'June', 'September', 'Depcember']);
        try {
            if ($request->is_quarter === "true" || $is_last->contains($request->period)) {
                $config = config('kpi.weight')['quarter'];
            } 
            if ($request->degree !== KPIEnum::one){
                $config = config('kpi.weight')['month'];
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($config, 'Get weigth config', 200);
    }
}
