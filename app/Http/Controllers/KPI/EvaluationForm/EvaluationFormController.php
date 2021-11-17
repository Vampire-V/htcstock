<?php

namespace App\Http\Controllers\KPI\EvaluationForm;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationSelfMail;
use App\Models\KPI\RuleCategory;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use App\Services\KPI\Service\UserApproveService;
// use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluationFormController extends Controller
{

    protected $departmentService, $positionService, $userService, $targetPeriodService, $ruleTemplateService,
        $templateService, $categoryService, $ruleService, $evaluateService, $evaluateDetailService,
        $settingActionService, $userApproveService;
    public function __construct(
        DepartmentServiceInterface $departmentServiceInterface,
        PositionServiceInterface $positionServiceInterface,
        UserServiceInterface $userServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        TemplateServiceInterface $templateServiceInterface,
        RuleCategory $categoryServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        SettingActionService $settingActionService,
        UserApproveService $userApproveService
    ) {
        $this->departmentService = $departmentServiceInterface;
        $this->positionService = $positionServiceInterface;
        $this->userService = $userServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
        $this->templateService = $templateServiceInterface;
        $this->categoryService = $categoryServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->settingActionService = $settingActionService;
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
        // 'selectDepartment', 'selectPosition',
        $selectDepartment = \collect([$request->division]);
        $selectPosition = \collect([$request->position]);
        $selectUser = \collect([$request->users]);
        try {
            $users = $this->userService->filter($request);
            $departments = $this->departmentService->dropdown();
            $positions = $this->positionService->dropdown();
            $dropdown_users = $this->userService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationForm.index', \compact('users', 'departments', 'positions', 'query', 'selectDepartment', 'selectPosition', 'dropdown_users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($staff, $period)
    {
        try {
            $user = $this->userService->find($staff);
            $period = $this->targetPeriodService->find($period);
            $category = $this->categoryService->all();
            $templates = $this->templateService->forCreated(\auth()->id());
            $rules = $this->ruleService->dropdown($category->first(function ($value, $key) {
                return $value->name === "key-task";
            })->id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationForm.create', \compact('user', 'period', 'templates', 'category', 'rules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $staff, $period)
    {
        DB::beginTransaction();
        try {
            if ($request->next && !$this->settingActionService->isNextStep(KPIEnum::assign)) {
                return $this->errorResponse('เลยเวลาที่กำหนด', 500);
            }

            $evaluate = $this->evaluateService->isDuplicate($staff, $period);
            if (!$evaluate) {
                $evaluate = $this->evaluateService->create(
                    [
                        'user_id' => $staff,
                        'period_id' => $period,
                        'head_id' => $staff,
                        'status' => $request->next ? KPIEnum::ready : KPIEnum::new,
                        'next_level' => 1,
                        'template_id' => $request->template,
                        'total_weight_kpi' => $request->total_weight_kpi,
                        'total_weight_key_task' => $request->total_weight_key_task,
                        'total_weight_omg' => $request->total_weight_omg
                    ]
                );

                $detail = [];
                foreach ($request->detail as $key => $value) {
                    // $rule = $this->evaluateDetailService->findLastRule($value['rule_id']);
                    $rule_id = $value['rule_id'];
                    $target = $value['target'];
                    $actual = 0.00;
                    $weight = $value['weight'];
                    $weight_category = $value['weight_category'];
                    $base_line = $value['base_line'];
                    $max_result = $value['max'];
                    \array_push($detail, compact("rule_id", "target", "weight", "weight_category", "base_line", "max_result", "actual"));
                }
                if (count($detail) > 0) {
                    $evaluate->evaluateDetail()->createMany($detail);
                    Log::notice("User : " . \auth()->user()->name . " = Create evaluate form : id = " . $evaluate->id);
                }

                if ($request->next) {
                    $user_approve = $this->userApproveService->findNextLevel($evaluate);
                    if (!$user_approve->exists) {
                        DB::rollBack();
                        Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                        return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", 500);
                    }
                    $evaluate->next_level = $user_approve->level;
                    $evaluate->save();
                    # send mail to staff
                    // Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Send evaluate mail : id = " . $evaluate->id);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), 'Evaluate created : ' . $evaluate->targetperiod->name . $evaluate->targetperiod->year, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($staff, $period, $evaluate)
    {
        try {
            $evaluate = $this->evaluateService->find($evaluate);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(new EvaluateResource($evaluate), 'Evaluate show', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $staff, $period, $evaluate)
    {
        try {
            $user = $this->userService->find($staff);
            $period = $this->targetPeriodService->find($period);
            $category = $this->categoryService->all();
            $templates = $this->templateService->dropdown();

            $evaluate = $this->evaluateService->find($evaluate);
            // $isView = $evaluate->status === KPIEnum::ready ? true : false;
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationForm.edit', \compact('user', 'period', 'templates', 'category', 'evaluate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $staff, $period, $evaluate)
    {
        DB::beginTransaction();
        try {
            if ($request->next && !$this->settingActionService->isNextStep(KPIEnum::assign)) {
                return $this->errorResponse('เลยเวลาที่กำหนด', 500);
            }

            $evaluate = $this->evaluateService->findKeyEvaluate($staff, $period, $evaluate);

            if ($evaluate) {
                $evaluate->next_level = 1;
                $user_approve = $this->userApproveService->findNextLevel($evaluate);
                if (!$user_approve->exists) {
                    DB::rollBack();
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", 500);
                }

                // Update Header
                $evaluate->template_id = $request->template;
                $evaluate->status = $request->next ? KPIEnum::ready : KPIEnum::new;
                $evaluate->next_level = 1;
                $evaluate->total_weight_kpi = $request->total_weight_kpi;
                $evaluate->total_weight_key_task = $request->total_weight_key_task;
                $evaluate->total_weight_omg = $request->total_weight_omg;
                $evaluate->save();

                $evaluate->evaluateDetail()->delete();
                $detail = [];
                // Insert new Detail
                foreach ($request->detail as $key => $value) {
                    // $rule = $this->evaluateDetailService->findLastRule($value['rule_id']);
                    $rule_id = $value['rule_id'];
                    $target = $value['target'];
                    $actual = 0.00;
                    $weight = $value['weight'];
                    $weight_category = $value['weight_category'];
                    $base_line = $value['base_line'];
                    $max_result = $value['max'];
                    \array_push($detail, compact("rule_id", "target", "actual", "weight", "weight_category", "base_line", "max_result"));
                }

                if (count($detail) > 0) {
                    $evaluate->evaluateDetail()->createMany($detail);
                    Log::notice("User : " . \auth()->user()->name . " = Created evaluate form : id = " . $evaluate->id);
                }

                if ($request->next) {
                    # send mail to staff
                    Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Send mail evaluate form  : id = " . $evaluate->id);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), 'Evaluate update : ' . $evaluate->targetperiod->name . $evaluate->targetperiod->year, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $staff, $period, $evaluate)
    {
        DB::beginTransaction();
        try {
            $item = $this->evaluateService->find($evaluate);
            if ($item->status === KPIEnum::new) {
                $this->evaluateService->destroy($evaluate);
                $request->session()->flash('success',  ' has been remove');
            } else {
                return \redirect()->back()->with('error', "Error : This status cannot be deleted.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
    }

    public function pullback(Request $request, $staff, $period, $evaluate)
    {
        DB::beginTransaction();
        $list = \collect([KPIEnum::ready, KPIEnum::draft]);
        try {
            $item = $this->evaluateService->find($evaluate);
            if ($list->contains($item->status)) {
                $item->status = KPIEnum::new;
                $item->current_level = null;
                $item->next_level = null;
                $item->save();
                $request->session()->flash('success',  ' has been Pull Back Evaluate..');
                DB::commit();
                return \redirect()->back();
            } else {
                return \redirect()->back()->with('error', "Error : This status cannot be pull back.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
