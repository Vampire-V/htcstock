<?php

namespace App\Http\Controllers\KPI\EddyMenu;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ALL\UserEvaluateResource;
use App\Http\Resources\KPI\EvaluateDetailResource;
use App\Http\Resources\KPI\EvaluateResource;
use App\Models\User;
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
        $firsts = \collect([KPIEnum::ready, KPIEnum::draft]);
        $second = \collect([KPIEnum::on_process]);
        $third = \collect([KPIEnum::approved]);
        $degree = \collect([KPIEnum::one,KPIEnum::two,KPIEnum::tree]);
        try {
            $departments = $this->departmentService->dropdown();
            $users_drop = $this->userService->dropdown();
            $users = $this->userService->reportStaffEvaluate($request);
            foreach ($users as $key => $user) {
                $user->first = \false;
                $user->second = \false;
                $user->third = \false;
                if ($user->evaluates->count() > 0) {
                    $status = $user->evaluates->first()->status;
                    if ($firsts->contains($status)) {
                        $user->first = \true;
                    }
                    if ($second->contains($status)) {
                        $user->first = \true;
                        $user->second = \true;
                    }
                    if ($third->contains($status)) {
                        $user->first = \true;
                        $user->second = \true;
                        $user->third = \true;
                    }
                }
            }
            return \view('kpi.Eddy.index', \compact('users','departments','degree','users_drop'));
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
            return $this->successResponse(UserEvaluateResource::collection($data), "user evaluates all", 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
