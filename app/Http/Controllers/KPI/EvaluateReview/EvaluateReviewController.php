<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
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
        $status_list = [KPIEnum::new, KPIEnum::ready, KPIEnum::draft, KPIEnum::submit, KPIEnum::approved];
        try {
            $user = Auth::user();
            $period = $this->targetPeriodService->dropdown();
            $evaluates = $this->evaluateService->filter($request);
        } catch (\Throwable $th) {
            throw $th;
        }

        // \dd($evaluates[0]->user,\auth()->user()->department->id);
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
        return \view('kpi.EvaluationReview.evaluate');
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
