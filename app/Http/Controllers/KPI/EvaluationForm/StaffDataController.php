<?php

namespace App\Http\Controllers\KPI\EvaluationForm;

use App\Http\Controllers\Controller;
use App\Models\KPI\Evaluate;
use App\Models\KPI\TargetPeriod;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;

class StaffDataController extends Controller
{
    protected $departmentService;
    protected $positionService;
    protected $userService;
    protected $evaluateService;
    protected $targetPeriodService;
    public function __construct(
        DepartmentServiceInterface $departmentServiceInterface,
        PositionServiceInterface $positionServiceInterface,
        UserServiceInterface $userServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface
    ) {
        $this->departmentService = $departmentServiceInterface;
        $this->positionService = $positionServiceInterface;
        $this->userService = $userServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $start_year = date('Y', strtotime('-10 years'));
    //     return \view('kpi.EvaluationForm.staffdata', \compact('start_year'));
    // }

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
    public function edit(Request $request, $id)
    {
        $year = isset($request->year) ? $request->year : \date('Y');
        try {
            $staff = $this->userService->find($id);
            $periods = $this->targetPeriodService->byYearForEvaluate($year, $staff);
        } catch (\Throwable $th) {
            throw $th;
        }
        $start_year = date('Y', strtotime('-10 years'));
        return \view('kpi.EvaluationForm.staff', \compact('start_year', 'periods', 'staff'));
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
