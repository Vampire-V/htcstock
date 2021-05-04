<?php

namespace App\Http\Controllers\KPI;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Models\KPI\TargetPeriod;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $targetPeriodService, $userService, $ruleService;
    public function __construct(
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleServiceInterface $ruleServiceInterface
    ) {
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
        $this->ruleService = $ruleServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedYear = empty($request->year) ? date('Y') : $request->year;
        $ofSelf = $this->targetPeriodService->selfApprovedEvaluationOfyear($selectedYear);
        $ofDept = $this->targetPeriodService->deptApprovedEvaluationOfyear($selectedYear);
        $periods = $this->targetPeriodService->query()->where('year',$selectedYear)->get();
        $users = $this->userService->evaluationOfYearReport($selectedYear);
        $rules = $this->ruleService->rulesInEvaluationReport($selectedYear);

        return \view('kpi.home', \compact('ofSelf', 'ofDept', 'users', 'periods', 'rules', 'selectedYear'));
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
