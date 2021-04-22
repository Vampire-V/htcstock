<?php

namespace App\Http\Controllers\KPI\EddyMenu;

use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllEvaluationController extends Controller
{
    protected $evaluateDetailService, $departmentService;
    public function __construct(EvaluateDetailServiceInterface $evaluateDetailServiceInterface, DepartmentServiceInterface $departmentServiceInterface)
    {
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->departmentService = $departmentServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectedYear = empty($request->year) ? date('Y') : $request->year;
        $selectedDept = $request->department;
        $start_year = date('Y', strtotime('-10 years'));

        $departments = $this->departmentService->dropdown();
        $evaluateDetail = EvaluateDetailResource::collection($this->evaluateDetailService->setActualForEddyFilter($request));
        // $evaluateDetail
        return \view('kpi.Eddy.index', \compact('start_year', 'selectedYear', 'departments', 'selectedDept', 'evaluateDetail'));
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
