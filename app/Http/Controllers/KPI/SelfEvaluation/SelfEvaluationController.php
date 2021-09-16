<?php

namespace App\Http\Controllers\KPI\SelfEvaluation;

use App\Enum\KPIEnum;
use App\Enum\UserEnum;
use App\Exports\KPI\EvaluateExport;
use App\Exports\KPI\EvaluateQuarterExport;
use App\Exports\KPI\EvaluateYearExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KPI\Traits\CalculatorEvaluateTrait;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationSelfMail;
use App\Models\KPI\Evaluate;
use App\Models\KPI\RuleTemplate;
use App\Models\User;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use App\Services\KPI\Service\UserApproveService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SelfEvaluationController extends Controller
{
    use CalculatorEvaluateTrait;
    protected $evaluateService, $evaluateDetailService, $userService,
        $categoryService, $templateService, $ruleService, $periodService,
        $ruleTemplateService, $setting_action_service, $userApproveService;


    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        TemplateServiceInterface $templateServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        SettingActionService $settingActionService,
        UserApproveService $userApproveService

    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->userService = $userServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->templateService = $templateServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->periodService = $targetPeriodServiceInterface;
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
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
        $selectedYear = collect($request->year ?? date('Y'));
        $selectedUser = collect($request->user ?? auth()->id());
        $start_year = date('Y', strtotime('-10 years'));
        try {
            $users = $this->userService->dropdown();
            // $users = Gate::allows(UserEnum::SUPERADMIN) ? $this->userService->dropdown() : $this->userService->dropdownEvaluationForm();
            $evaluates = $this->evaluateService->selfFilter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \view('kpi.SelfEvaluation.index', \compact('start_year', 'users', 'selectedYear', 'selectedUser', 'evaluates'));
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
        try {
            $category = $this->categoryService->dropdown();
            $f_evaluate = $this->evaluateService->find($id);
            $weight_group = config('kpi.weight')['month'];
            $current = $this->userApproveService->findCurrentLevel($f_evaluate);
            $evaluate  = new EvaluateResource($f_evaluate);
            $canOperation = Gate::allows(UserEnum::OPERATIONKPI);
            $isAdmin = Gate::allows(UserEnum::ADMINKPI);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.evaluate', \compact('evaluate', 'category', 'weight_group', 'current', 'canOperation','isAdmin'));
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
        $status_list = collect([KPIEnum::new, KPIEnum::ready, KPIEnum::draft]);
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);
            $check_set = $this->setting_action_service->isNextStep(KPIEnum::set_value);
            $check_app = $this->setting_action_service->isNextStep(KPIEnum::approve);

            if ($status_list->contains($evaluate->status)) {
                if ($request->next) {
                    if (!$check_app) {
                        return $this->errorResponse("เลยเวลาที่กำหนด", 500);
                    }
                } else {
                    if (!$check_set) {
                        return $this->errorResponse("เลยเวลาที่กำหนด", 500);
                    }
                }
            }
            // New version รออัพเดท ข้อมูล
            $detail = collect($request->detail);
            $g = $detail->groupBy(fn ($item) => $item['rules']['category_id']);
            $total = [];
            foreach ($g as $value) {
                $total[] = $value->reduce(function ($a, $b) {
                    return $b['cal'] + $a;
                }, 0);
            }
            $evaluate->cal_kpi = $total[0] ?? 0.00;
            $evaluate->cal_key_task = $total[1] ?? 0.00;
            $evaluate->cal_omg = $total[2] ?? 0.00;

            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(
                        [
                            'target' => $value['target'],
                            'actual' => $value['actual'],
                            'weight' => $value['weight'],
                            'weight_category' => $value['weight_category'],
                            'base_line' => $value['base_line'],
                            'max_result' => $value['max']
                        ]
                    );
            }

            if ($request->next && (\auth()->id() !== $evaluate->user_id)) {
                return $this->errorResponse("ไม่มีสิทธิ์", 500);
            }
            if ($request->next) {
                # send mail to Manger
                if (!$evaluate->next_level) {
                    DB::rollBack();
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", 500);
                }
                $evaluate->next_level = $evaluate->next_level + 1;
                $user_approve = $this->userApproveService->findNextLevel($evaluate);

                $evaluate->status = KPIEnum::on_process;
                $evaluate->current_level = $evaluate->getOriginal('next_level');
                $evaluate->next_level = $user_approve->level;

                $user_cur = $this->userApproveService->findCurrentLevel($evaluate);
                // dd($user_approve->user_approve,$user_cur->user_approve);
                Mail::to($user_cur->approveBy->email)->send(new EvaluationSelfMail($evaluate));
                $message = "send mail to " . $user_cur->approveBy->name;
            } else {
                if (\auth()->id() !== $evaluate->user_id && Gate::allows(UserEnum::OPERATIONKPI)) {
                    $message = "Change by Operation... ";
                } else {
                    $evaluate->status = KPIEnum::draft;
                    $message = "Draft evaluate of " . $evaluate->user->name;
                }
            }
            $evaluate->save();
            DB::commit();
            return $this->successResponse(new EvaluateResource($evaluate->fresh()), $message, 201);
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_new()
    {
        try {
            // $user = $this->userService->find($staff);
            // $period = $this->targetPeriodService->find($period);
            $months = $this->periodService->dropdown()->unique('name');
            $years = $months->unique('year');
            $category = $this->categoryService->dropdown();
            $templates = $this->templateService->forCreated(\auth()->id());
            $rules = $this->ruleService->dropdown($category->first(fn ($value, $key) => $value->name === "key-task")->id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.create', \compact('templates', 'category', 'rules', 'months', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_new(Request $request)
    {
        DB::beginTransaction();
        try {
            $form = \json_decode(json_encode($request->form));
            $period = $this->periodService->query()->where('name', $request->period)->where('year', $request->year)->first();

            RuleTemplate::with('rule')->where('template_id', $form->template)->delete();

            RuleTemplate::insert($this->new_rule_template($form));
            $status = $form->next ? KPIEnum::submit : KPIEnum::ready;
            $manager = User::where('username', \auth()->user()->head_id)->first();

            $evaluate = new Evaluate();
            $evaluate->user_id = \auth()->id();
            $evaluate->period_id = $period->id;
            $evaluate->head_id = $manager->id ?? null;
            $evaluate->status = $status;
            $evaluate->template_id = $form->template;
            $evaluate->total_weight_kpi = $form->total_weight_kpi;
            $evaluate->total_weight_key_task = $form->total_weight_key_task;
            $evaluate->total_weight_omg = $form->total_weight_omg;
            $evaluate->save();
            $evaluate->evaluateDetail()->createMany($this->new_evaluate_detail($form));
            $evaluate->save();

            // if ($form->next) {
            # send mail to Manger
            if ($evaluate->user->head_id && $form->next) {
                $evaluate->status = KPIEnum::submit;
                $evaluate->save();
                $message = KPIEnum::submit;
                $manager = $this->userService->getManager($evaluate->user);
                Mail::to($manager->email)->send(new EvaluationSelfMail($evaluate));
            } else {
                $evaluate->status = KPIEnum::draft;
                $evaluate->save();
                $message = KPIEnum::draft . " You don't have a manager!";
                // $evaluate->user->head_id is null
            }
            // }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), "Created evaluate status: " . $message, 200);
    }

    private function new_rule_template($value)
    {
        $result = [];
        try {
            for ($i = 0; $i < count($value->detail); $i++) {
                $element = $value->detail[$i];
                $template_id = $value->template;
                $rule_id = $element->rule_id;
                $weight = $element->weight;
                $weight_category = $element->weight_category;
                // $parent_rule_template_id = $element->weight;
                $target_config = $element->target;
                $base_line = $element->base_line;
                $max_result = $element->max;
                $created_at = \now();
                $updated_at = \now();
                $result[] = \compact('template_id', 'rule_id', 'weight', 'weight_category', 'target_config', 'base_line', 'max_result', 'created_at', 'updated_at');
            }
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function new_evaluate_detail($value)
    {
        $result = [];
        try {
            for ($i = 0; $i < count($value->detail); $i++) {
                $element = $value->detail[$i];

                $rule_id = $element->rule_id;
                $target = $element->target;
                $actual = $element->actual;
                $weight = $element->weight;
                $weight_category = $element->weight_category;
                $base_line = $element->base_line;
                $max_result = $element->max;
                $result[] = \compact('rule_id', 'target', 'actual', 'weight', 'weight_category', 'base_line', 'max_result');
            }
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user
     * @param  int  $quarter
     * @param  int  $year
     * @return \Illuminate\Http\Response
     */
    public function display_quarter($user, $quarter, $year)
    {
        $kpi_reduce = 0.0;
        $key_task_reduce = 0.0;
        $omg_reduce = 0.0;
        try {
            $category = $this->categoryService->dropdown();
            $detail = new Collection();
            $evaluate_quarter = $this->evaluateService->forQuarterYear($user, $quarter, $year);

            foreach ($evaluate_quarter as $key => $item) {
                $kpi_reduce += $item->kpi_reduce;
                $key_task_reduce += $item->key_task_reduce;
                $omg_reduce += $item->omg_reduce;
                foreach ($item->evaluateDetail as $key => $value) {
                    $detail->push($value);
                }
            }

            $evaluate = $evaluate_quarter->first();
            $evaluate->kpi_reduce = $kpi_reduce;
            $evaluate->key_task_reduce = $key_task_reduce;
            $evaluate->omg_reduce = $omg_reduce;
            $evaluate->evaluateDetail = $detail;
            $quarter_weight = $evaluate->user->degree === KPIEnum::one ? config('kpi.weight')['quarter'] : config('kpi.weight')['month'];
            // $evaluate  = new EvaluateResource($evaluate);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.quarter', \compact('evaluate', 'category', 'quarter_weight'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user
     * @param  int  $year
     * @return \Illuminate\Http\Response
     */
    public function display_all_quarter($user, $year)
    {
        $kpi_reduce = 0.0;
        $key_task_reduce = 0.0;
        $omg_reduce = 0.0;
        try {
            $category = $this->categoryService->dropdown();
            $detail = new Collection();
            $evaluate_quarter = $this->evaluateService->forYear($user, $year);

            foreach ($evaluate_quarter as $key => $item) {
                $kpi_reduce += $item->kpi_reduce;
                $key_task_reduce += $item->key_task_reduce;
                $omg_reduce += $item->omg_reduce;
                foreach ($item->evaluateDetail as $key => $value) {
                    $detail->push($value);
                }
            }
            $evaluate = $evaluate_quarter->first();
            $evaluate->kpi_reduce = $kpi_reduce;
            $evaluate->key_task_reduce = $key_task_reduce;
            $evaluate->omg_reduce = $omg_reduce;

            $evaluate->evaluateDetail = $detail;
            $quarter_weight = $evaluate->user->degree === KPIEnum::one ? config('kpi.weight')['quarter'] : config('kpi.weight')['month'];
            return \view('kpi.SelfEvaluation.allquarter', \compact('evaluate', 'category', 'quarter_weight'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    private function quarter_summary(Collection $detail): Collection
    {
        $length = count($detail);
        $temp = \collect();
        for ($i = 0; $i < $length; $i++) {
            $item = $detail[$i];
            if (count($temp) === 0) {
                $temp[] = $detail[$i];
            } else {
                $have_rule = $temp->search(function ($query) use ($item) {
                    return $query->rule->id === $item->rule->id;
                });
                if ($have_rule) {
                    $temp[$have_rule]->target += $item->target;
                    $temp[$have_rule]->actual += $item->actual;
                    $temp[$have_rule]->weight += $item->weight;
                    $temp[$have_rule]->base_line += $item->base_line;
                    $temp[$have_rule]->max_result += $item->max_result;
                    // dd($temp[$have_rule],$item);
                } else {
                    $temp[] = $detail[$i];
                }
            }
        }
        return $temp;
    }

    public function evaluateExcel($id)
    {
        try {
            $evaluate = Evaluate::with(['evaluateDetail.rule.category'])->find($id);
            $this->calculation_detail($evaluate->evaluateDetail);
            $evaluate_detail = $evaluate->evaluateDetail->groupBy(fn ($item) => $item->rules->category_id);
            return Excel::download(new EvaluateExport($evaluate, $evaluate_detail), "Evaluate" . $evaluate->user->name . ".xlsx");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function evaluateQuarterExcel($user, $quarter, $year)
    {
        $kpi_reduce = 0;
        $key_task_reduce = 0;
        $omg_reduce = 0;
        try {
            $detail = \collect();
            $evaluate_quarter = $this->evaluateService->forQuarterYear($user, $quarter, $year);

            foreach ($evaluate_quarter as $item) {
                $kpi_reduce += $item->kpi_reduce;
                $key_task_reduce += $item->key_task_reduce;
                $omg_reduce += $item->omg_reduce;
                $item->evaluateDetail->each(fn ($value) => $detail->add($value));
            }

            $new = $detail->groupBy('rule_id')->each(function ($item) {
                $max = $item->last()->max_result;
                $weight = $item->last()->rule->category->name === 'omg' ? $item->sum('weight') : $item->sum('weight') / 3;
                $target = $this->quarter_cal_target($item);
                $actual = $this->quarter_cal_actual($item);
                $item->each(function ($value) use ($max, $weight, $target, $actual) {
                    $value->max_result = $max;
                    $value->weight = $weight;
                    $value->target = $target;
                    $value->actual = $actual;
                });
            });
            $detail = \collect();
            $new->each(function ($item) use ($detail) {
                $detail->add($item->unique('rule_id')->first());
            });

            $evaluate = $evaluate_quarter->first();
            $evaluate->kpi_reduce = $kpi_reduce;
            $evaluate->key_task_reduce = $key_task_reduce;
            $evaluate->omg_reduce = $omg_reduce;

            $this->calculation_detail($detail);
            $user = $this->userService->find($user);
            return Excel::download(new EvaluateQuarterExport($user, $evaluate, $detail), "Evaluate-quarter-" . $user->name . ".xlsx");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function evaluateYearExcel($user, $year)
    {
        $kpi_reduce = 0;
        $key_task_reduce = 0;
        $omg_reduce = 0;
        try {
            $detail = \collect();
            $evaluate_quarter = $this->evaluateService->forYear($user, $year);

            // $evaluate_quarter->each(function ($item) use ($detail) {
            //     $item->evaluateDetail->each(fn ($value) => $detail->add($value));
            // });
            foreach ($evaluate_quarter as $item) {
                $kpi_reduce += $item->kpi_reduce;
                $key_task_reduce += $item->key_task_reduce;
                $omg_reduce += $item->omg_reduce;
                $item->evaluateDetail->each(fn ($value) => $detail->add($value));
            }
            $new = $detail->groupBy('rule_id')->each(function ($item) use ($year) {
                $quarter = 4;
                $month = 12;
                if ($year === date('Y')) {
                    $quarter = $this->check_quarter($this->get_month_haier());
                    $month = $this->get_month_haier();
                }
                $max = $item->last()->max_result;
                $weight = $item->last()->rule->category->name === 'omg' ? $item->sum('weight') / $quarter : $item->sum('weight') / $month;
                $target = $this->quarter_cal_target($item);
                $actual = $this->quarter_cal_actual($item);
                $item->each(function ($value) use ($max, $weight, $target, $actual) {
                    $value->max_result = $max;
                    $value->weight = $weight;
                    $value->target = $target;
                    $value->actual = $actual;
                });
            });
            $detail = \collect();
            $new->each(function ($item) use ($detail) {
                $detail->add($item->unique('rule_id')->first());
            });

            $this->calculation_detail($detail);
            $evaluate = $evaluate_quarter->first();
            $evaluate->kpi_reduce = $kpi_reduce;
            $evaluate->key_task_reduce = $key_task_reduce;
            $evaluate->omg_reduce = $omg_reduce;
            $user = $this->userService->find($user);
            return Excel::download(new EvaluateYearExport($user, $evaluate, $detail), "Evaluate-year-" . $user->name . ".xlsx");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function quarter_cal_target(Collection $items)
    {
        if ($items->first()->rule->quarter_cal === KPIEnum::average) {
            return $items->avg('target');
        }
        if ($items->first()->rule->quarter_cal === KPIEnum::last_month) {
            return $items->last()->target;
        }
        if ($items->first()->rule->quarter_cal === KPIEnum::sum) {
            return $items->sum('target');
        }
    }

    private function quarter_cal_actual(Collection $items)
    {
        if ($items->first()->rule->quarter_cal === KPIEnum::average) {
            return $items->avg('actual');
        }
        if ($items->first()->rule->quarter_cal === KPIEnum::last_month) {
            return $items->last()->actual;
        }
        if ($items->first()->rule->quarter_cal === KPIEnum::sum) {
            return $items->sum('actual');
        }
    }

    private function check_quarter($date)
    {
        $month_q1 = \collect([3, 4, 5]);
        $month_q2 = \collect([6, 7, 8]);
        $month_q3 = \collect([9, 10, 11]);
        $month_q4 = \collect([12, 1, 2]);
        if ($month_q1->contains($date)) {
            return 1;
        }
        if ($month_q2->contains($date)) {
            return 2;
        }
        if ($month_q3->contains($date)) {
            return 3;
        }
        if ($month_q4->contains($date)) {
            return 4;
        }
    }

    private function get_month_haier()
    {
        return date('d') > 12 ? intval(date('n', strtotime('-1 month'))) : intval(date('n', strtotime('-2 month')));
        //    intval(date('n', strtotime('-1 month')));
    }
}
