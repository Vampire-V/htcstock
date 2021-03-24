<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService, $evaluateDetailService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
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
        $calMainRule = 0;
        try {
            $evaluate = $this->evaluateService->find($id);
            // $evaluate->mainRul;

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
        return \view('kpi.EvaluationReview.evaluate', \compact('evaluate', 'kpi', 'key_task', 'omg', 'calMainRule'));
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
