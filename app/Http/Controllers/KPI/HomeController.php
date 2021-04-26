<?php

namespace App\Http\Controllers\KPI;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Models\KPI\TargetPeriod;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $targetPeriodService, $userService;
    public function __construct(
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface
    ) {
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ofSelf = $this->targetPeriodService->selfApprovedEvaluationOfyear(date('Y'));
        $ofDept = $this->targetPeriodService->deptApprovedEvaluationOfyear(date('Y'));
        $periods = $this->targetPeriodService->dropdown();
        $users = $this->userService->evaluationOfYear(date('Y'));

        return \view('kpi.home', \compact('ofSelf', 'ofDept', 'users','periods'));
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
