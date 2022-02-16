<?php

namespace App\Http\Controllers\KPI;

use App\Http\Controllers\Controller,
    App\Enum\KPIEnum,
    App\Enum\UserEnum,
    App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait,
    App\Services\IT\Interfaces\UserServiceInterface,
    App\Services\IT\Service\DepartmentService,
    App\Services\KPI\Interfaces\EvaluateServiceInterface,
    App\Services\KPI\Interfaces\RuleServiceInterface,
    App\Services\KPI\Interfaces\TargetPeriodServiceInterface,
    Illuminate\Http\Request,
    Illuminate\Support\Facades\Gate,
    App\Services\KPI\Service\EvaluateDetailService,
    Illuminate\Support\Facades\DB,
    Symfony\Component\HttpFoundation\Response,
    Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $targetPeriodService,
        $userService,
        $ruleService,
        $evaluateService,
        $evaluateDetailService,
        $departmentService;
    public function __construct(
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        DepartmentService $departmentService,
        EvaluateDetailService $evaluateDetailService
    ) {
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->departmentService = $departmentService;
        $this->evaluateDetailService = $evaluateDetailService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = $this->departmentService->dropdown();
        $degree = \collect(KPIEnum::$degree);
        $show_rules = Gate::allows(UserEnum::ADMINKPI) || (\auth()->user()->degree === KPIEnum::one) ? true : false;

        return \view('kpi.home', \compact('departments', 'degree', 'show_rules'));
    }

    public function report_your_self($year)
    {
        try {
            $result = $this->targetPeriodService->selfApprovedEvaluationOfyear($year);
            // dd($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse($result, 'get report your self', Response::HTTP_OK);
    }

    // Controller ที่ใช้ url test http://127.0.0.1:8000/kpi/dashboard/rule-of-year/2021/report
    public function report_rule_of_year(Request $request, $year)
    {
        try {
            $periods = $this->targetPeriodService->query()->where('year', $year)->get();
            $rules = $this->ruleService->rulesInEvaluationReport($periods, $request);
            foreach ($rules as $rule) {
                $total = \collect();
                foreach ($periods as $period) {
                    $data_for_sum = [];
                    for ($i = 0; $i < $rule->evaluatesDetail->count(); $i++) {
                        $item = $rule->evaluatesDetail[$i];
                        if ($item->evaluate->status === KPIEnum::approved && $period->id === $item->evaluate->period_id) {
                            $data_for_sum[] = $item;
                        }
                    }
                    $total->push($data_for_sum);
                }
                $rule->total = $total;
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse(['rules' => $rules, 'periods' => $periods], 'get report rules of year', Response::HTTP_OK);
    }

    // Controller ที่ใช้ url test http://127.0.0.1:8000/kpi/dashboard/rule-of-year/2021/report
    public function report_staff_evaluate_year($year)
    {
        try {
            $periods = $this->targetPeriodService->query()->where('year', $year)->get();
            $users = $this->userService->evaluationOfYearReport($periods);
            for ($i = 0; $i < $users->count(); $i++) {
                $user = $users[$i];
                $this->calculation_summary($user->evaluates);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse(['users' => $users, 'periods' => $periods], 'get report staff evaluate of year', Response::HTTP_OK);
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
            $this->calculation_summary($evaluations, $request);
            $result = $evaluations; //EvaluateResource::collection($evaluations);
        } catch (\Exception $e) {
            return $this->errorResponse($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse($result, Response::HTTP_OK);
    }

    public function weigthconfig(Request $request)
    {

        try {
            if ($request->is_quarter === "true" && $request->degree === KPIEnum::one) {
                $config = config('kpi.weight')['quarter'];
            } else {
                $config = config('kpi.weight')['month'];
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse($config, 'Get weigth config', Response::HTTP_OK);
    }

    public function changetargetactual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target' => 'required|numeric',
            'actual' => 'required|numeric',
            'evaluates' => 'required|array',
            'rule' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(),Response::HTTP_BAD_REQUEST);
        }
        DB::beginTransaction();
        try {
            $rule = $this->ruleService->find($request->rule);
            $this->evaluateDetailService->updateTargetActual($request->target,$request->actual,$rule,$request->evaluates);
            DB::commit();
            return $this->successResponse(null, 'Update target actual success...', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
