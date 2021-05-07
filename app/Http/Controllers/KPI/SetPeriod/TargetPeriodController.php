<?php

namespace App\Http\Controllers\KPI\SetPeriod;

use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StorePeriod;
use App\Models\KPI\TargetPeriod;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TargetPeriodController extends Controller
{
    protected $periodService;
    public function __construct(TargetPeriodServiceInterface $periodServiceInterface)
    {
        $this->periodService = $periodServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedPeriod = collect($request->period);
        $selectedYear = $request->year;
        try {
            $periods = $this->periodService->filterIndex($request);
            $months = $this->periodService->dropdown()->unique('name');
            $years = $months->unique('year');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SetPeriod.index', \compact('periods', 'selectedPeriod', 'selectedYear','years','months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // $response = $this->user()->cannot('create');
            // \dd($response);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SetPeriod.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePeriod $request)
    {
        DB::beginTransaction();

        try {
            $period = new TargetPeriod();
            $period->name = $request->name;
            $period->year = $request->year;
            $period->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('kpi.set-period.edit', $period->id);
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
        try {
            $period = $this->periodService->find($id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SetPeriod.edit', \compact('period'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePeriod $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->periodService->update($request->except(['_token', '_method']), $id);
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->back();
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
