<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationReviewMail;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService, $evaluateDetailService, $categoryService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
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
        $status_list = [KPIEnum::submit, KPIEnum::approved];
        try {
            $user = Auth::user();
            $months = $this->targetPeriodService->dropdown()->unique('name');
            $years = $months->unique('year');
            $evaluates = $this->evaluateService->reviewfilter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \view(
            'kpi.EvaluationReview.index',
            \compact('start_year', 'user', 'status_list', 'months', 'evaluates', 'query', 'selectedStatus', 'selectedYear', 'selectedPeriod')
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
        try {
            $category = $this->categoryService->dropdown();
            $f_evaluate = $this->evaluateService->find($id);
            // $f_evaluate->evaluateDetail->each(fn ($item) => $this->evaluateDetailService->formulaKeyTask($item));
            $evaluate  = new EvaluateResource($f_evaluate);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationReview.evaluate', \compact('evaluate', 'category'));
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
        $message = "";
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);
            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual']]);
            }
            $evaluate->status = $request->next ? KPIEnum::approved : KPIEnum::draft;
            $evaluate->comment = $request->comment;
            $evaluate->save();
            Log::notice("User : " . \auth()->user()->id . " = Update evaluate review : id = " . $evaluate->id);


            if ($request->next) {
                $message = KPIEnum::approved;
                # send mail to approved
            } else {
                $message = KPIEnum::draft;
                # send mail to reject
                Mail::to($evaluate->user->email)->send(new EvaluationReviewMail($evaluate));
                Log::notice("User : " . \auth()->user()->id . " = Send mail evaluate review : id = " . $evaluate->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), "evaluate review status to : " . $message, 201);
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
