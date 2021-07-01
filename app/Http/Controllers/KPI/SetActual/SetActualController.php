<?php

namespace App\Http\Controllers\KPI\SetActual;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetActualController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $evaluateDetailService, $departmentService, $targetPeriodService, 
    $categoryService, $ruleService, $userService, $setting_action_service;
    public function __construct(
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        UserServiceInterface $userServiceInterface,
        SettingActionService $settingActionService
    ) {
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->userService = $userServiceInterface;
        $this->setting_action_service = $settingActionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedYear = empty($request->year) ? date('Y') : $request->year;
        $selectedDept = $request->department;
        $selectedPeriod = $request->period;
        $selectedCategory = $request->category;
        $selectedRule = $request->rule;
        $selectedUser = $request->user;
        $start_year = date('Y', strtotime('-10 years'));

        $months = $this->targetPeriodService->dropdown()->unique('name');
        $users = $this->userService->dropdown();
        $departments = $this->departmentService->dropdown();
        $categorys = $this->categoryService->dropdown();
        $rules = $this->ruleService->dropdown();
        // DB::enableQueryLog();
        $result = $this->evaluateDetailService->setActualFilter($request);
        // dd(DB::getQueryLog());
        $this->calculation_detail($result);
        $evaluateDetail = EvaluateDetailResource::collection($result);
        return \view('kpi.SetActual.index', \compact(
            'start_year',
            'evaluateDetail',
            'selectedYear',
            'selectedDept',
            'selectedPeriod',
            'months',
            'departments',
            'categorys',
            'selectedCategory',
            'rules',
            'selectedRule',
            'selectedUser',
            'users'
        ));
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
        $body = $request->all();
        $status_contain = collect([KPIEnum::draft, KPIEnum::ready]);
        DB::beginTransaction();
        try {
            $check = $this->setting_action_service->isNextStep(KPIEnum::set_value);
            if (!$check) {
                return $this->errorResponse("เลยเวลาที่กำหนด", 500);
            }
            for ($i = 0; $i < count($body); $i++) {
                $element = $body[$i];
                $detail = $this->evaluateDetailService->find($element['id']);

                if ($detail && $status_contain->contains($detail->evaluate->status)) {
                    $detail->actual = floatval($element['actual']);
                    $detail->target = floatval($element['target']);
                    $detail->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(null, "Updated actual", 201);
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
}
