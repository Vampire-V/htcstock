<?php

namespace App\Http\Controllers\KPI\SetPeriod;

use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StorePeriod;
use App\Models\KPI\TargetPeriod;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            $years = $this->periodService->dropdown()->unique('year');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SetPeriod.index', \compact('periods', 'selectedPeriod', 'selectedYear', 'years', 'months'));
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
            DB::commit();
            return \redirect()->route('kpi.set-period.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
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

    public function generateMonth(Request $request)
    {
        $start = 1;
        $end = 12;
        $month = strtotime('2021-01-01');
        DB::beginTransaction();
        try {
            while ($start <= $end) {
                $m = date('m', $month);
                $y = date('Y');
                $validator = Validator::make(['name' => $m, 'year' => $y], [
                    'name' => Rule::unique('kpi_target_periods')->where(fn ($query) => $query->where('name', $m)->where('year', $y))
                ]);

                if ($validator->fails()) {
                    // dump($validator->errors()->messages());
                }else{
                    $period = new TargetPeriod();
                    $period->name = $m;
                    $period->year = $y;
                    $period->save();
                }


                $month = strtotime("+1 month", $month);
                $start++;
            }
            $request->session()->flash('success', 'The system has created the month to be used for this year.');
            DB::commit();
            // \dd('End');
            return \redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

    }
}
