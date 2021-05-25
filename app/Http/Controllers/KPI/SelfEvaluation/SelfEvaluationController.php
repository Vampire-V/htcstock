<?php

namespace App\Http\Controllers\KPI\SelfEvaluation;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SelfEvaluationController extends Controller
{
    protected $evaluateService, $evaluateDetailService, $userService,
        $categoryService, $templateService, $ruleService, $periodService,
        $ruleTemplateService;
    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        TemplateServiceInterface $templateServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        RuleTemplateServiceInterface $ruleTemplateServiceInterface

    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->userService = $userServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->templateService = $templateServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->periodService = $targetPeriodServiceInterface;
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedYear = empty($request->year) ? collect(date('Y')) : collect($request->year);
        $start_year = date('Y', strtotime('-10 years'));
        try {
            $user = Auth::user();
            $evaluates = $this->evaluateService->selfFilter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.index', \compact('start_year', 'user', 'selectedYear', 'evaluates'));
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
            $evaluate  = new EvaluateResource($f_evaluate);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.evaluate', \compact('evaluate', 'category'));
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
        $message = KPIEnum::draft;
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);
            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual']]);
            }

            if ($request->next) {
                # send mail to Manger
                if ($evaluate->user->head_id) {
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
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), "evaluate self status to : " . $message, 201);
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
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), "Created evaluate", 200);
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
}
