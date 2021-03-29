<?php

namespace App\Http\Controllers\KPI\SelfEvaluation;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Models\KPI\Evaluate;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class SelfEvaluationController extends Controller
{
    protected $evaluateService, $evaluateDetailService, $userService, $categoryService;
    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface

    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->userService = $userServiceInterface;
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
        $selectedYear = collect(empty($request->year) ? date('Y') : $request->year);
        $start_year = date('Y', strtotime('-10 years'));
        try {
            $user = Auth::user();
            $evaluates = $this->evaluateService->selfFilter($request);
        } catch (\Throwable $th) {
            throw $th;
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
        $status = \collect([KPIEnum::draft, KPIEnum::ready]);
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
        return \view('kpi.SelfEvaluation.evaluate', \compact('evaluate', 'kpi', 'key_task', 'omg', 'mainRule', 'summary', 'status'));
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
            $this->evaluateService->update(['status' => $request->next ? KPIEnum::submit : $request->form['status']], $id);
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
