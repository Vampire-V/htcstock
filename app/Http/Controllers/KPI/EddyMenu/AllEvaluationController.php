<?php

namespace App\Http\Controllers\KPI\EddyMenu;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ALL\UserEvaluateResource;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Http\Resources\KPI\EvaluateResource;
use App\Models\KPI\EvaluateDetail;
use App\Models\User;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\TargetPeriodServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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

        $firsts = \collect([KPIEnum::ready, KPIEnum::draft]);
        $second = \collect([KPIEnum::on_process]);
        $third = \collect([KPIEnum::approved]);
        $degree = \collect(KPIEnum::$degree);
        $sel_month = $request->month ?? date('m');
        $sel_year = $request->year ?? date('Y');
        $sel_user = \collect($request->users_where);
        $sel_dept = \collect($request->department_where);
        $sel_degree = \collect($request->degree);
        try {
            $departments = $this->departmentService->dropdown();
            $users_drop = $this->userService->dropdown();
            $users = $this->userService->reportStaffEvaluate($request);
            foreach ($users as $key => $user) {
                $user->first = \false;
                $user->second = \false;
                $user->third = \false;
                if ($user->evaluates->count() > 0) {
                    $form_evaluate = $user->evaluates->first();
                    if ($firsts->contains($form_evaluate->status)) {
                        $user->first = \true;
                        $user->time_first = $form_evaluate->created_at->format('d-M-Y H:i:s');
                    }
                    if ($second->contains($form_evaluate->status)) {
                        $user->first = \true;
                        $user->time_first = $form_evaluate->created_at->format('d-M-Y H:i:s');
                        $user->second = \true;
                        $times_second = $form_evaluate->history->whereIn('status',$second->toArray());
                        $user->time_second = $times_second->first()->created_at->format('d-M-Y H:i:s');
                    }
                    if ($third->contains($form_evaluate->status)) {
                        $user->first = \true;
                        $user->time_first = $form_evaluate->created_at->format('d-M-Y H:i:s');
                        $user->second = \true;
                        $times_second = $form_evaluate->history->whereIn('status',$second->toArray());
                        $user->time_second = $times_second->first()->created_at->format('d-M-Y H:i:s');
                        $user->third = \true;
                        $times_third = $form_evaluate->history->whereIn('status',$third->toArray());
                        $user->time_third = $times_third->first()->created_at->format('d-M-Y H:i:s');
                    }
                }
            }
            return \view('kpi.Eddy.index', \compact('users','departments','degree','users_drop','sel_month','sel_year','sel_user','sel_dept','sel_degree'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
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
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user_evaluates(Request $request)
    {
        try {
            $data = User::with(['evaluate.evaluateDetail'])->notResigned()->get();
            return $this->successResponse(UserEvaluateResource::collection($data), "user evaluates all", Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rules_ready(Request $request)
    {
        try {
            $sel_month = $request->month ?? date('m');
            $sel_year = $request->year ?? date('Y');
            $items = EvaluateDetail::select('id','evaluate_id','rule_id','target','actual')->with(['evaluate.user', 'evaluate.targetperiod', 'rule.category'])
            // ->whereHas('rule', fn ($query) => $query->where('user_actual', \auth()->id()))
            ->whereHas('evaluate', fn ($query) => $query->whereIn('status', [KPIEnum::ready, KPIEnum::draft]))->setActualFilter($request)->get();
            // $this->evaluateDetailService->setActualFilter($request);
            return \view('kpi.Eddy.rulesready',\compact('items','sel_month','sel_year'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
