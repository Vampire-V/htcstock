<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationReviewMail;
use App\Mail\KPI\EvaluationSelfMail;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use App\Services\KPI\Service\UserApproveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService, $evaluateDetailService,
        $categoryService, $setting_action_service,$userApproveService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        SettingActionService $settingActionService,
        UserApproveService $userApproveService
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->setting_action_service = $settingActionService;
        $this->userApproveService = $userApproveService;
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
        $status_list = [KPIEnum::on_process, KPIEnum::submit, KPIEnum::approved];
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
        $status_list = collect([KPIEnum::new, KPIEnum::ready, KPIEnum::draft, KPIEnum::on_process, KPIEnum::submit]);
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);

            $check = $this->setting_action_service->isNextStep(KPIEnum::approve);
            if ($status_list->contains($evaluate->status) && !$check) {
                return $this->errorResponse("เลยเวลาที่กำหนด", 500);
            }

            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual']]);
            }
            if ($request->next) {
                // Approved
                $user_approve = $this->userApproveService->findNextLevel($evaluate);
                if (!$user_approve->exists) {
                    // Error
                    DB::rollBack();
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", 500);
                }

                if ($this->userApproveService->isLastLevel($evaluate)) {
                    // Level last
                    $evaluate->status = KPIEnum::approved;
                    Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Update evaluate review End process : id = " . $evaluate->id);
                    $message = "Approved";
                }else{
                    // Next level
                    $evaluate->status = KPIEnum::on_process;
                    Mail::to($user_approve->approveBy->email)->send(new EvaluationReviewMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Update evaluate review next step : id = " . $evaluate->id);
                    $message = "Next step send to ".$user_approve->approveBy->name;
                }
                $evaluate->next_level = $user_approve->id;
                
                # send mail to approved
            } else {
                if (!$evaluate->nextlevel->exists) {
                    DB::rollBack();
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", 500);
                }
                $user_approve = $this->userApproveService->findFirstLevel($evaluate->user_id);
                $evaluate->status = KPIEnum::draft;
                $evaluate->comment = $request->comment;
                $evaluate->next_level = $user_approve->id;
                
                # send mail to reject
                Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                Log::notice("User : " . \auth()->user()->name . " = Send mail reject evaluate = " . $evaluate->id);
                $message = "Reject to " . $evaluate->user->name;
            }
            $evaluate->save();
            DB::commit();
            return $this->successResponse(new EvaluateResource($evaluate), $message, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
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
