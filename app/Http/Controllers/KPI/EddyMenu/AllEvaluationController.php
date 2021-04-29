<?php

namespace App\Http\Controllers\KPI\EddyMenu;

use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Http\Resources\KPI\EvaluateResource;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllEvaluationController extends Controller
{
    protected $evaluateDetailService, $departmentService, $targetPeriodService, $userService, $evaluateService;
    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        UserServiceInterface $userServiceInterface
    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->userService = $userServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $tab = '';
        $selectedYear = empty($request->year) ? collect(date('Y')) : collect($request->year);
        $selectedDept = collect($request->department_id);
        $selectedPeriod = collect($request->period);
        $selectedUser = \intval($request->user);
        $start_year = date('Y', strtotime('-10 years'));
        
        $periods = $this->targetPeriodService->dropdown();
        $departments = $this->departmentService->dropdown();
        $users = $this->userService->dropdown();
        $evaluates = $this->evaluateService->editEvaluateFilter($request);
        $details = \collect();
        $evaluates->each(fn($value) => $value->evaluateDetail->each(fn($detail) => $details->push($detail)));
        $evaluate = EvaluateResource::collection($evaluates);
        $evaluateDetail = EvaluateDetailResource::collection($details);

        // \dd($selectedDept,$departments);
        return \view('kpi.Eddy.index', \compact('start_year', 'selectedYear', 'departments', 'selectedDept', 'periods', 'selectedPeriod', 'users', 'selectedUser','evaluate','evaluateDetail'));
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
        $body = $request->all();
        // $status_contain = collect([KPIEnum::draft, KPIEnum::ready]);
        $status = \false;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($body); $i++) {
                $element = $body[$i];
                $detail = $this->evaluateDetailService->find($element['id']);

                if ($detail) {
                    $detail->actual = floatval($element['actual']);
                    $detail->save();
                    $status = \true;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response()->json(["status" => $status]);
    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAch(Request $request, $id)
    {
        $body = $request->all();
        // $status_contain = collect([KPIEnum::draft, KPIEnum::ready]);
        $status = \false;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($body); $i++) {
                $element = $body[$i];
                $evaluate = $this->evaluateService->find($element['id']);
                if ($evaluate) {
                    $evaluate->ach_kpi = floatval($element['ach_kpi']);
                    $evaluate->ach_key_task = floatval($element['ach_key_task']);
                    $evaluate->ach_omg = floatval($element['ach_omg']);
                    $evaluate->save();
                    $status = \true;
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \response()->json(["status" => $status]);
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
