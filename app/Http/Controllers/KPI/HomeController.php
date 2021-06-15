<?php

namespace App\Http\Controllers\KPI;

use App\Enum\KPIEnum;
use App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Models\KPI\Evaluate;
use App\Models\KPI\TargetPeriod;
use App\Models\User;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $targetPeriodService, $userService, $ruleService, $evaluateService;
    public function __construct(
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface
    ) {
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedYear = empty($request->year) ? date('Y') : $request->year;
        $evaluations =  Evaluate::with('user', 'evaluateDetail.rule', 'targetperiod')->where('status', KPIEnum::approved)->get();
        $this->calculation_summary($evaluations);
        // $ofSelf = $this->targetPeriodService->selfApprovedEvaluationOfyear($selectedYear);
        $ofDept = $this->targetPeriodService->deptApprovedEvaluationOfyear($selectedYear);
        $periods = $this->targetPeriodService->query()->where('year', $selectedYear)->get();
        $users = $this->userService->evaluationOfYearReport($selectedYear);
        $rules = $this->ruleService->rulesInEvaluationReport($selectedYear);

        return \view('kpi.home', \compact('ofDept', 'users', 'periods', 'rules', 'selectedYear'));
    }

    public function report_your_self($year)
    {
        try {
            $result = $this->targetPeriodService->selfApprovedEvaluationOfyear($year);
        } catch (\Exception $e) {
            throw $this->errorResponse($e->getMessage(),500);
        }
        return $this->successResponse($result,'get report your self',200);
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
            // if ($request->month) {
            $this->calculation_summary($evaluations);
            // }
            $result = EvaluateResource::collection($evaluations);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        return $this->successResponse($result, 200);
    }

    public function weigthconfig(Request $request)
    {
        try {
            if ($request->is_quarter === "true") {
                $config = config('kpi.weight')['quarter'];
            } else {
                $config = config('kpi.weight')['month'];
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        return $this->successResponse($config, 'Get weigth config', 200);
    }
}
