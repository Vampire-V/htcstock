<?php

namespace App\Http\Controllers\KPI\SetActual;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetActualController extends Controller
{
    protected $evaluateDetailService;
    public function __construct(EvaluateDetailServiceInterface $evaluateDetailServiceInterface)
    {
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_year = date('Y', strtotime('-10 years'));
        $evaluateDetail = EvaluateDetailResource::collection($this->evaluateDetailService->setActualFilter($request));
        return \view('kpi.SetActual.index', \compact('start_year', 'evaluateDetail'));
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
        $status_contain = collect([KPIEnum::draft, KPIEnum::ready]);
        $status = \false;
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($body); $i++) {
                $element = $body[$i];
                $detail = $this->evaluateDetailService->find($element['id']);

                if ($detail && $status_contain->contains($detail->evaluate->status)) {
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
