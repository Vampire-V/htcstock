<?php

namespace App\Http\Controllers\KPI\SelfEvaluation;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\KPI\EvaluateResource;
use App\Mail\KPI\EvaluationSelfMail;
use App\Models\KPI\EvaluateDetail;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\KPI\Interfaces\EvaluateDetailServiceInterface;
use App\Services\KPI\Interfaces\EvaluateServiceInterface;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SelfEvaluationController extends Controller
{
    protected $evaluateService, $evaluateDetailService, $userService, $categoryService;
    public function __construct(
        EvaluateServiceInterface $evaluateServiceInterface,
        EvaluateDetailServiceInterface $evaluateDetailServiceInterface,
        UserServiceInterface $userServiceInterface,
        RuleCategoryServiceInterface $ruleCategoryServiceInterface

    ) {
        $this->evaluateService = $evaluateServiceInterface;
        $this->evaluateDetailService = $evaluateDetailServiceInterface;
        $this->userService = $userServiceInterface;
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
        $selectedYear = empty($request->year) ? collect(date('Y')) : collect($request->year);
        $start_year = date('Y', strtotime('-10 years'));
        try {
            $user = Auth::user();
            $evaluates = $this->evaluateService->selfFilter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.index', \compact('start_year', 'user', 'selectedYear', 'evaluates'));
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
            $evaluate  = new EvaluateResource($f_evaluate);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.SelfEvaluation.evaluate', \compact('evaluate', 'category'));
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
        $message = KPIEnum::draft;
        DB::beginTransaction();
        try {
            $evaluate = $this->evaluateService->find($id);
            foreach ($request->detail as $value) {
                $evaluate->evaluateDetail()
                    ->where(['rule_id' => $value['rule_id'], 'evaluate_id' => $value['evaluate_id']])
                    ->update(['actual' => $value['actual']]);
            }
            
            if ($request->next) {
                # send mail to Manger
                if ($evaluate->user->head_id) {
                    $evaluate->status = KPIEnum::submit;
                    $evaluate->save();
                    $message = KPIEnum::submit;
                    $manager = $this->userService->getManager($evaluate->user);
                    Mail::to($manager->email)->send(new EvaluationSelfMail($evaluate));
                }else{
                    $evaluate->status = KPIEnum::draft;
                    $evaluate->save();
                    $message = KPIEnum::draft . " You don't have a manager!";
                    // $evaluate->user->head_id is null
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new EvaluateResource($evaluate), "evaluate self status to : " . $message, 201);
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
