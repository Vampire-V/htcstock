<?php

namespace App\Http\Controllers\KPI\EvaluationForm;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationFormMail;
use App\Models\KPI\EvaluateDetail;
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
// use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluationFormController extends Controller
{
    protected $departmentService;
    protected $positionService;
    protected $userService;
    protected $targetPeriodService;
    protected $ruleTemplateService;
    protected $templateService;
    protected $categoryService;
    protected $ruleService;
    protected $evaluateService;
    protected $evaluateDetailService;
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
        RuleServiceInterface $ruleServiceInterface
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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        try {
            $users = $this->userService->filter($request);
            $departments = $this->departmentService->dropdown();
            $positions = $this->positionService->dropdown();
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.EvaluationForm.index', \compact('users', 'departments', 'positions', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($staff, $period)
    {
        // \dd("Staff : " . $staff, "period form : " . $period);
        try {
            $user = $this->userService->find($staff);
            $period = $this->targetPeriodService->find($period);
            $category = $this->categoryService->all();
            $templates = $this->templateService->dropdown();
            $rules = $this->ruleService->dropdown($category->first(function ($value, $key) {
                return $value->name === "key-task";
            })->id);
        } catch (\Throwable $th) {
            throw $th;
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
            // check Duplicate
            $evaluate = $this->evaluateService->isDuplicate($staff, $period);
            if (!$evaluate) {
                $evaluate = $this->evaluateService->create(
                    [
                        'user_id' => $staff,
                        'period_id' => $period,
                        'head_id' => $staff,
                        'status' => $request->next ? KPIEnum::ready : KPIEnum::new,
                        'template_id' => $request->template,
                        'main_rule_id' => $request->mainRule,
                        'main_rule_condition_1_min' => $request->minone,
                        'main_rule_condition_1_max' => $request->maxone,
                        'main_rule_condition_2_min' => $request->mintwo,
                        'main_rule_condition_2_max' => $request->maxtwo,
                        'main_rule_condition_3_min' => $request->mintree,
                        'main_rule_condition_3_max' => $request->maxtree,
                        'total_weight_kpi' => $request->total_weight_kpi,
                        'total_weight_key_task' => $request->total_weight_key_task,
                        'total_weight_omg' => $request->total_weight_omg
                    ]
                );

                foreach ($request->detail as $key => $value) {
                    $evaluate_id = $evaluate->id;
                    $rule_id = $value['rule_id'];
                    $target = $value['target_config'];
                    $actual = 0;
                    $weight = $value['weight'];
                    if ($value['rules']['categorys']['name'] === 'kpi') {
                        $weight_category = $request->total_weight_kpi;
                    }
                    if ($value['rules']['categorys']['name'] === 'key-task') {
                        $weight_category = $request->total_weight_key_task;
                    }
                    if ($value['rules']['categorys']['name'] === 'omg') {
                        $weight_category = $request->total_weight_omg;
                    }
                    $base_line = $value['base_line'];
                    $max_result = $value['max_result'];
                    $attr = compact("evaluate_id", "rule_id", "target", "actual", "weight", "weight_category", "base_line", "max_result");
                    $this->evaluateDetailService->create($attr);
                }
                
                if ($request->next) {
                    # send mail to staff
                    Mail::to($evaluate->user->email)->send(new EvaluationFormMail($evaluate));
                    // \dd(Mail::failures());
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency("Exception Message: ".$th->getMessage()." File: " . $th->getFile() . " Line: " . $th->getLine());
            throw $th;
        }
        DB::commit();
        return new EvaluateResource($evaluate);
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
        } catch (\Throwable $th) {
            throw $th;
        }
        return new EvaluateResource($evaluate);
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
            $isView = $evaluate->status === KPIEnum::ready ? true : false;
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.EvaluationForm.edit', \compact('user', 'period', 'templates', 'category', 'evaluate', 'isView'));
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
            $evaluate = $this->evaluateService->findKeyEvaluate($staff, $period, $evaluate);
            if ($evaluate) {
                // Update Header
                $evaluate->template_id = $request->template;
                $evaluate->main_rule_id = $request->mainRule;
                $evaluate->status = $request->next ? KPIEnum::ready : KPIEnum::new;
                $evaluate->main_rule_condition_1_min = $request->minone;
                $evaluate->main_rule_condition_1_max = $request->maxone;
                $evaluate->main_rule_condition_2_min = $request->mintwo;
                $evaluate->main_rule_condition_2_max = $request->maxtwo;
                $evaluate->main_rule_condition_3_min = $request->mintree;
                $evaluate->main_rule_condition_3_max = $request->maxtree;
                $evaluate->total_weight_kpi = $request->total_weight_kpi;
                $evaluate->total_weight_key_task = $request->total_weight_key_task;
                $evaluate->total_weight_omg = $request->total_weight_omg;
                $evaluate->save();

                // Insert or Update Detail
                foreach ($request->detail as $key => $value) {
                    $rule_id = $value['rule_id'];
                    $target = $value['target'];
                    $actual = 0;
                    $weight = $value['weight'];
                    if ($value['rules']['categorys']['name'] === 'kpi') {
                        $weight_category = $request->total_weight_kpi;
                    }
                    if ($value['rules']['categorys']['name'] === 'key-task') {
                        $weight_category = $request->total_weight_key_task;
                    }
                    if ($value['rules']['categorys']['name'] === 'omg') {
                        $weight_category = $request->total_weight_omg;
                    }
                    $base_line = $value['base_line'];
                    $max_result = $value['max_result'];

                    // success return 1
                    if (!isset($value['id'])) {
                        $evaluate_id = $evaluate->id;
                        $attr = compact("evaluate_id", "rule_id", "target", "actual", "weight", "weight_category", "base_line", "max_result");
                        $this->evaluateDetailService->create($attr);
                    } else {
                        $attr = compact("rule_id", "target", "actual", "weight", "weight_category", "base_line", "max_result");
                        $this->evaluateDetailService->updateForEvaluate($attr, $evaluate->id, $rule_id);
                    }
                }

                // Remove row
                if (isset($request->remove)) {
                    foreach ($request->remove as $key => $value) {
                        if (isset($value['id'])) {
                            $this->evaluateDetailService->destroy($value['id']);
                        }
                    }
                }

                if ($request->next) {
                    # send mail to staff
                    Mail::to($evaluate->user->email)->send(new EvaluationFormMail($evaluate));
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::emergency("Exception Message: ".$th->getMessage()." File: " . $th->getFile() . " Line: " . $th->getLine());
            throw $th;
        }
        DB::commit();
        return new EvaluateResource($evaluate);
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
