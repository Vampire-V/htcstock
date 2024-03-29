<?php

namespace App\Http\Controllers\KPI\EvaluateReview;

use App\Enum\KPIEnum;
use App\Enum\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Filters\All\Query\EMCGroupIn;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationReviewMail;
use App\Mail\KPI\EvaluationSelfMail;
use App\Models\KPI\Evaluate;
use App\Models\KPI\UserApprove;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\DivisionServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use App\Services\KPI\Service\SettingActionService;
use App\Services\KPI\Service\UserApproveService;
use Facade\FlareClient\Http\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class EvaluateReviewController extends Controller
{
    protected $userService, $targetPeriodService, $evaluateService, $evaluateDetailService,
        $categoryService, $setting_action_service, $userApproveService, $divisionService, $departmentService;
    public function __construct(
        UserServiceInterface $userServiceInterface,
        TargetPeriodServiceInterface $targetPeriodServiceInterface,
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        SettingActionService $settingActionService,
        UserApproveService $userApproveService,
        DivisionServiceInterface $divisionService,
        DepartmentServiceInterface $departmentService
    ) {
        $this->userService = $userServiceInterface;
        $this->targetPeriodService = $targetPeriodServiceInterface;
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->categoryService = $ruleCategoryServiceInterface;
        $this->setting_action_service = $settingActionService;
        $this->userApproveService = $userApproveService;
        $this->divisionService = $divisionService;
        $this->departmentService = $departmentService;
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
        $selectedUser = collect($request->user);
        $selectedYear = collect($request->year);
        $selectedPeriod = collect($request->month);
        $selectedDivision = \collect($request->division_id);
        $selectedDepartment = \collect($request->department_id);
        $selectedEmc = \collect($request->degree);
        $start_year = date('Y', strtotime('-5 years'));
        $status_list = [KPIEnum::on_process, KPIEnum::approved];
        $emc_group = KPIEnum::$degree;
        try {
            $keys = UserApprove::where('user_approve', \auth()->id())->get();

            $user = Auth::user();
            $users = Gate::any([UserEnum::ADMINKPI]) ? $this->userService->dropdownKpi() : $this->userService->dropdownApprovalKPI($keys->pluck('user_id'));
            $divisions = $this->divisionService->dropdown();
            $departments = $this->departmentService->dropdown();
            $months = $this->targetPeriodService->dropdown()->unique('name');
            $years = $months->unique('year');
            $evaluates = $this->evaluateService->reviewFilter($request);
            $evaluates->each(function ($item) {
                $item->background = "";
                if ($item->userApprove->sortBy('level')->last()->level === $item->current_level && $item->status === KPIEnum::on_process) {
                    $item->background = "greenyellow";
                }
            });
            // \dd($evaluates->first()->userApprove->where('level',$evaluates->first()->next_level)->first()->approveBy->name);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \view(
            'kpi.EvaluationReview.index',
            \compact('emc_group', 'start_year', 'user', 'status_list', 'months', 'evaluates', 'query', 'users', 'divisions', 'departments', 'selectedEmc', 'selectedStatus', 'selectedYear', 'selectedPeriod', 'selectedUser', 'selectedDivision', 'selectedDepartment')
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
            $current = $this->userApproveService->findCurrentLevel($f_evaluate);
            $evaluate  = new EvaluateResource($f_evaluate);
            $canInput = Gate::any([UserEnum::ADMINKPI, UserEnum::OPERATIONKPI, UserEnum::MANAGERKPI]);
            $canAdmin = Gate::allows(UserEnum::ADMINKPI);
            $history = $this->evaluateService->history($f_evaluate);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.EvaluationReview.evaluate', \compact('evaluate', 'category', 'current', 'canInput', 'canAdmin', 'history'));
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
        $status_list = collect([KPIEnum::new, KPIEnum::ready, KPIEnum::draft, KPIEnum::on_process]);
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);
            $current_approve = $evaluate->userApprove()->where('level', $evaluate->current_level)->first();

            $check = $this->setting_action_service->isNextStep(KPIEnum::approve);
            if ($status_list->contains($evaluate->status) && !$check) {
                return $this->errorResponse("เลยเวลาที่กำหนด", Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if (auth()->id() !== $current_approve->user_approve) {
                return $this->errorResponse(auth()->user()->name." ไม่ใช่ : " . $current_approve->approveBy->name, Response::HTTP_SERVICE_UNAVAILABLE);
            }

            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual'], 'target' => $value['target'], 'remark' => $value['remark']]);
            }
            if ($request->next) {
                // Approved
                $user_approve = $this->userApproveService->findNextLevel($evaluate);
                $user_cur = $this->userApproveService->findCurrentLevel($evaluate);
                if (!$user_approve) {
                    // Error
                    DB::rollBack();
                    Log::warning($evaluate->user->name . " ไม่มี Level approve kpi system..");
                    return $this->errorResponse($evaluate->user->name . " ไม่มี Level approve", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                $evaluate->kpi_reduce = $request->kpi_reduce;
                $evaluate->key_task_reduce = $request->key_task_reduce;
                $evaluate->omg_reduce = $request->omg_reduce;
                $evaluate->kpi_reduce_hod = $request->kpi_reduce_hod;
                $evaluate->key_task_reduce_hod = $request->key_task_reduce_hod;
                $evaluate->omg_reduce_hod = $request->omg_reduce_hod;
                if ($this->userApproveService->isLastLevel($evaluate)) {
                    // Level last
                    $last_level = $this->userApproveService->findLastLevel($evaluate);
                    $evaluate->status = KPIEnum::approved;
                    // Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Update evaluate review End process : id = " . $evaluate->id);
                    $message = KPIEnum::approved;
                    $evaluate->current_level = $last_level->level;
                    $evaluate->next_level = $last_level->level;
                } else {
                    // Next level
                    Mail::to($user_approve->approveBy->email)->send(new EvaluationReviewMail($evaluate));
                    Log::notice("User : " . \auth()->user()->name . " = Update evaluate review next step : id = " . $evaluate->id);
                    $message = "Next step send to " . $user_approve->approveBy->name;
                    $evaluate->status = KPIEnum::on_process;

                    $evaluate->current_level += 1;
                    $evaluate->next_level += 1;
                    $next = $this->userApproveService->findNextLevel($evaluate);
                    $evaluate->next_level = $next->exists ? $next->level : $evaluate->current_level;
                }

                # send mail to approved
            } else {
                $user_approve = $this->userApproveService->findFirstLevel($evaluate->user_id);
                $evaluate->status = KPIEnum::draft;
                $evaluate->comment = $request->comment;
                $evaluate->current_level = null;
                $evaluate->next_level = $user_approve->level;

                # send mail to reject
                Mail::to($evaluate->user->email)->send(new EvaluationSelfMail($evaluate));
                Log::notice("User : " . \auth()->user()->name . " = Send mail reject evaluate = " . $evaluate->id);
                $message = "Reject to " . $evaluate->user->name;
            }
            $evaluate->save();
            DB::commit();
            return $this->successResponse(new EvaluateResource($evaluate->fresh()), $message, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function evaluateEdit(Request $request, $id)
    {
        $status_list = collect([KPIEnum::new, KPIEnum::ready, KPIEnum::draft, KPIEnum::on_process]);
        // dd($request->all());
        if (Gate::none([UserEnum::SUPERADMIN, UserEnum::ADMINKPI])) {
            return $this->errorResponse("ไม่มีสิทธิ์", 500);
        }
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);

            // $detail = collect($request->detail);
            // $g = $detail->groupBy(fn ($item) => $item['rules']['category_id']);
            // $total = [];
            // foreach ($g as $value) {
            //     $total[] = $value->reduce(function ($a, $b) {
            //         return $b['cal'] + $a;
            //     }, 0);
            // }
            // dd($request->all());
            $evaluate->kpi_reduce = $request->kpi_reduce;
            $evaluate->key_task_reduce = $request->key_task_reduce;
            $evaluate->omg_reduce = $request->omg_reduce;
            $evaluate->kpi_reduce_hod = $request->kpi_reduce_hod;
            $evaluate->key_task_reduce_hod = $request->key_task_reduce_hod;
            $evaluate->omg_reduce_hod = $request->omg_reduce_hod;

            // $evaluate->cal_kpi = $total[0] ?? 0.00;
            // $evaluate->cal_key_task = $total[1] ?? 0.00;
            // $evaluate->cal_omg = $total[2] ?? 0.00;

            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual'], 'target' => $value['target'], 'remark' => $value['remark']]);
            }

            $evaluate->save();
            DB::commit();
            return $this->successResponse(new EvaluateResource($evaluate->fresh()), "update success...", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function evaluateReviewErrors(Request $request)
    {
        $ids = explode(",", $request->evaluate);
        try {
            $result = Evaluate::with(['user', 'evaluateDetail' => fn ($q) => $q->where('rule_id', $request->rule_id)])->whereIn('id', [...$ids])->get();
            return $this->successResponse($result, "query success...", 200);
        } catch (\Exception $e) {
            Log::error("Exception Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line: " . $e->getLine());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forceApprove(Request $request)
    {
        $evaluates = $this->evaluateService->reviewFilter($request);
        DB::beginTransaction();
        try {
            foreach ($evaluates as $value) {
                if ($value->status === KPIEnum::on_process) {
                    $lastLevel = $value->userApprove->last();
                    foreach ($value->userApprove as $itemlevel) {
                        if ($itemlevel->level <= $lastLevel->level && $value->current_level >= $itemlevel->level) {

                            if ($value->next_level && $value->next_level >= $lastLevel->level) {
                                $value->next_level = $lastLevel->level;
                                $value->status = KPIEnum::approved;
                            }else{
                                $value->next_level += 1;
                                $value->status = KPIEnum::on_process;
                            }
                            $value->current_level += 1;
                            $value->save();
                        }
                    }
                    // if ($value->id !== 2029) {
                    //     \dump($value->userApprove->pluck('level'),$lastLevel);
                    //     exit;
                    //     \dump("Last Lv: "+$lastLevel);
                    // }
                    // $eddy = $value->userApprove->where('user_approve',113)->first();
                    // $befor_eddy = $value->userApprove->where('level',$eddy->level - 1)->first();
                    // dd($befor_eddy);
                    // $lastLevel = $value->userApprove->last();
                    // if ($value->next_level || $value->next_level > $lastLevel->level) {
                    //     $value->next_level = $lastLevel->level;
                    // }
                    // $value->current_level = $lastLevel->level;
                    // $value->status = KPIEnum::approved;
                    // $value->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return \redirect()->back()->with('success', "Approved data success...");
    }
}
