<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService, $evaluateDetailService, $categoryService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedStatus = collect($request->status);
        $selectedYear = collect($request->year);
        $selectedPeriod = collect($request->period);
        $start_year = date('Y', strtotime('-10 years'));
        $status_list = [KPIEnum::submit, KPIEnum::approved];
        try {
            $user = Auth::user();
            $period = $this->targetPeriodService->dropdown();
            $evaluates = $this->evaluateService->reviewfilter($request);
        } catch (\Throwable $th) {
            throw $th;
        }

        return \view(
            'kpi.EvaluationReview.index',
            \compact('start_year', 'user', 'status_list', 'period', 'evaluates', 'query', 'selectedStatus', 'selectedYear', 'selectedPeriod')
        );
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
        $status = \collect([KPIEnum::submit, KPIEnum::approved]);
        try {
            $evaluate = $this->evaluateService->findId($id);
            $evaluate->evaluateDetail->each(fn ($item) => $this->evaluateDetailService->formulaKeyTask($item));

            $kpi = $evaluate->evaluateDetail->filter(fn ($value) => $value->rule->category->name === "kpi");
            $key_task = $evaluate->evaluateDetail->filter(fn ($value) => $value->rule->category->name === "key-task");
            $omg = $evaluate->evaluateDetail->filter(fn ($value) => $value->rule->category->name === "omg");

            $mainRule = $evaluate->evaluateDetail->filter(fn ($row) => $row->rule_id === $evaluate->main_rule_id)->first();

            $category = $this->categoryService->dropdown();
            $summary = collect([]);
            foreach ($category as $key => $cat) {
                $weight = $evaluate->template->ruleTemplate->filter(fn ($value) => $value->rule->category->name === $cat->name)->first()->weight_category;
                $ach = $evaluate->evaluateDetail->filter(fn ($value) => $value->rule->category->name === $cat->name)->sum('ach');
                $calsummary = new stdClass;
                $calsummary->name = $cat->name;
                $calsummary->weight = $weight;
                $calsummary->ach = $ach;
                $calsummary->total =  ($ach * $weight) / 100;

                $summary->push($calsummary);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.EvaluationReview.evaluate', \compact('evaluate', 'kpi', 'key_task', 'omg', 'mainRule', 'summary','status'));
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
        DB::beginTransaction();
        try {
            $this->evaluateService->update(['comment' => $request->form['comment'] ,'status' => $request->next ? KPIEnum::approved : KPIEnum::draft], $id);
            foreach ($request->form['evaluate_detail'] as $value) {
                $this->evaluateDetailService->update(['actual' => $value['actual']], $value['id']);
            }
            $evaluate = $this->evaluateService->find($id);
        } catch (\Throwable $th) {
            DB::rollBack();
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
