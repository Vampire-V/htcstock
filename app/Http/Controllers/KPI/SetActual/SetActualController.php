<?php

namespace App\Http\Controllers\KPI\SetActual;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Mail\KPI\EvaluationSelfMail;
use App\Models\KPI\Evaluate;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use App\Services\KPI\Service\UserApproveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class SetActualController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $evaluateDetailService, $departmentService, $targetPeriodService,
        $categoryService, $ruleService, $userService, $setting_action_service, $userApproveService;
    public function __construct(
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        UserServiceInterface $userServiceInterface,
        SettingActionService $settingActionService,
        UserApproveService $userApproveService
    ) {
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->userService = $userServiceInterface;
        $this->setting_action_service = $settingActionService;
        $this->userApproveService = $userApproveService;
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
        $start_year = date('Y', strtotime('-5 years'));

        $months = $this->targetPeriodService->dropdown()->unique('name');
        $users = $this->userService->dropdownKpi();
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
        $errors = [];
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
                } else {
                    $errors[] = ["name" => $detail->evaluate->user->name, "rule" => $detail->rules->name];
                }
            }
            DB::commit();
            return $this->successResponse($errors, "Updated actual", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
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

    public function sendemail(Request $request)
    {
        try {
            $evaluates = Evaluate::find($request->evaluates);
            foreach ($evaluates as $key => $evaluate) {
                $user_approve = $this->userApproveService->findNextLevel($evaluate);
                if (!$user_approve->exists) {
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                // $evaluate->next_level = $user_approve->level;
                // $evaluate->save();
                // # send mail to staff
                Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                Log::notice("User : " . \auth()->user()->name . " = Send evaluate mail : id = " . $evaluate->id);
            }

            return $this->successResponse(null, 'Send email success....' , Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
