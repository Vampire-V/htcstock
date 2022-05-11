<?php

namespace App\Http\Controllers\KPI\EvaluationForm;

use App\Enum\KPIEnum;
use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ALL\UserResource;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\PositionServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;

class StaffDataController extends Controller
{
    protected $departmentService, $positionService, $userService, $evaluateService, $targetPeriodService, $divisionService;
    public function __construct(
        DepartmentServiceInterface $departmentServiceInterface,
        PositionServiceInterface $positionServiceInterface,
        UserServiceInterface $userServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        DivisionServiceInterface $divisionServiceInterface
    ) {
        $this->departmentService = $departmentServiceInterface;
        $this->positionService = $positionServiceInterface;
        $this->userService = $userServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->divisionService = $divisionServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectDepartment = \collect($request->department);
        $selectPosition = \collect($request->position);
        $selectDivision = \collect($request->division);
        $selectUser = \collect($request->users);
        $selectDegree = \collect($request->degree);
        try {
            $user = $this->userService->find(\auth()->user()->id);
            $users = $this->userService->filterForEvaluateForm($request);
            // $dropdown_users = $this->userService->dropdownKpiOfOperation();
            if ($user->hasRole(UserEnum::ADMINKPI) || $user->hasRole(UserEnum::MANAGERKPI)) {
                $dropdown_users = $this->userService->dropdownKpi();
                $departments = $this->departmentService->dropdown();
            }else {
                $dropdown_users = $this->userService->dropdownKpiOfOperation();
                $departments = $this->departmentService->dropdownOperation();
            }
            // $divisions = \collect([$this->divisionService->find(\auth()->user()->divisions_id)]);
            $divisions = $this->divisionService->dropdown();

            $positions = $this->positionService->dropdown();
            $degrees = \collect(KPIEnum::$degree);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationForm.index', \compact('users', 'divisions', 'departments', 'positions', 'query', 'selectDivision', 'selectDepartment', 'selectPosition', 'dropdown_users', 'selectUser', 'degrees', 'selectDegree'));
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
    public function edit(Request $request, $id)
    {
        $selectedYear = empty($request->year) ? \date('Y') : $request->year;
        try {
            $staff = $this->userService->user($id);
            $periods = $this->targetPeriodService->byYearForEvaluate($selectedYear, $staff);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        $start_year = date('Y', strtotime('-10 years'));
        return \view('kpi.EvaluationForm.staff', \compact('start_year', 'periods', 'staff', 'selectedYear'));
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
