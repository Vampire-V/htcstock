<?php

namespace App\Http\Controllers\KPI\SelfEvaluation;

use App\Http\Controllers\Controller;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfEvaluationController extends Controller
{
    protected $evaluateService,$evaluateDetailService;
    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface
    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedYear = collect(empty($request->year) ? date('Y') : $request->year );
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
        $calMainRule = 0;
        try {
            $evaluate = $this->evaluateService->find($id);

            $kpi = $evaluate->evaluateDetail->filter(function ($value, $key) {
                return $this->evaluateDetailService->formulaKeyTask($value)->rule->category->name === "kpi";
            });
            $key_task = $evaluate->evaluateDetail->filter(function ($value, $key) {
                return $this->evaluateDetailService->formulaKeyTask($value)->rule->category->name === "key-task";
            });
            $omg = $evaluate->evaluateDetail->filter(function ($value, $key) {
                return $this->evaluateDetailService->formulaKeyTask($value)->rule->category->name === "omg";
            });
            $indexMainRule = $evaluate->evaluateDetail->search(function ($row, $key) use ($evaluate) {
                return $row->rule_id === $evaluate->main_rule_id;
            });

            if ($indexMainRule) {
                $calMainRule = $evaluate->evaluateDetail[$indexMainRule]->cal;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.SelfEvaluation.evaluate', \compact('evaluate', 'kpi', 'key_task', 'omg', 'calMainRule'));
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
}
