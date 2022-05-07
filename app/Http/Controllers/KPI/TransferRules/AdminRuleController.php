<?php

namespace App\Http\Controllers\KPI\TransferRules;

use App\Enum\KPIEnum;
use App\Enum\UserEnum;
use App\Exports\KPI\RulesExport;
use App\Exports\KPI\TemplateRulesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreRulePost;
use App\Http\Requests\KPI\StoreRulePut;
use App\Http\Resources\KPI\RuleResource;
use App\Imports\KPI\RulesImport;
use App\Imports\KPI\RulesNImport;
use App\Models\Department;
use App\Models\KPI\EvaluateDetail;
use App\Models\KPI\KpiRuleType;
use App\Models\KPI\Rule;
use App\Models\KPI\RuleCategory;
use App\Models\TemporaryFile;
use App\Models\User;
use App\Services\IT\Interfaces\UserServiceInterface;
use App\Services\IT\Service\DepartmentService;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use App\Services\KPI\Interfaces\RuleServiceInterface;
use App\Services\KPI\Interfaces\RuleTypeServiceInterface;
use App\Services\KPI\Interfaces\TargetUnitServiceInterface;
use App\Services\KPI\Service\RuleLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Validators\Failure;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class AdminRuleController extends Controller
{

    protected $userService, $ruleService;
    public function __construct(UserServiceInterface $userServiceInterface, RuleServiceInterface $ruleServiceInterface)
    {
        $this->userService = $userServiceInterface;
        $this->ruleService = $ruleServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $selectedUser = collect($request->user_actual);
        try {
            $users = $this->userService->dropdownOperation();
            $rules = $params ? $this->ruleService->filter($request) : $params;
        } catch (\Exception $e) {
            // return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.TransferRules.index', \compact('users', 'params', 'rules', 'selectedUser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRulePost $request)
    {
        // if (!Gate::allows(UserEnum::OPERATIONKPI)) {
        //     return \redirect()->back()->with('error', "Error : no authorize..");
        // }

        // DB::beginTransaction();
        // $fromValue = $request->except(['_token']);
        // try {
        //     $rule = $this->ruleService->create($fromValue);
        //     if (!$rule) {
        //         $request->session()->flash('error', ' has been create fail');
        //         return \back();
        //     }
        //     $request->session()->flash('success', ' has been create success');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        // }
        // DB::commit();
        // return \redirect()->route('kpi.rule-list.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // try {
        //     $rule = $this->ruleService->find($id);
        // } catch (\Exception $e) {
        //     return $this->errorResponse($e->getMessage(), 500);
        // }
        // return $this->successResponse(new RuleResource($rule), "rule show ", 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!Gate::allows(UserEnum::OPERATIONKPI)) {
        //     return \redirect()->back()->with('error', "Error : no authorize..");
        // }

        // $calcuTypes = \collect([KPIEnum::positive, KPIEnum::negative, KPIEnum::zero_oriented_kpi]);
        // $quarter_cals = \collect([KPIEnum::average, KPIEnum::sum, KPIEnum::last_month]);
        // try {
        //     $rule = Rule::with(['parent_to', 'updatedby'])->where('id', $id)->first();
        //     $category = $this->ruleCategoryService->dropdown();
        //     $unit = $this->targetUnitService->dropdown();
        //     $rulesType = $this->ruleTypeService->dropdown();
        //     $users = $this->userService->dropdown();
        //     $departments = $this->departmentService->dropdown();
        //     $rules = $this->ruleService->dropdown($category->firstWhere('name', 'kpi')->id);
        // } catch (\Exception $e) {
        //     return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        // }
        // return \view('kpi.RuleList.edit', \compact('rule', 'rules', 'category', 'unit', 'calcuTypes', 'rulesType', 'users', 'departments', 'quarter_cals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!Gate::allows(UserEnum::ADMINKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize..");
        }

        DB::beginTransaction();
        $fromValue = $request->except(['_token', '_method']);
        try {
            $ss = $this->ruleService->transferToUser($fromValue['user_form'], $fromValue['user_to']);
            if ($ss) {
                $request->session()->flash('success', ' has been updated success');
                DB::commit();
            }else{
                $request->session()->flash('error', ' has been update fail');
            }


            return \back();
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', ' has been update fail');
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
        // if (!Gate::allows(UserEnum::OPERATIONKPI)) {
        //     return \redirect()->back()->with('error', "Error : no authorize..");
        // }

        // DB::beginTransaction();
        // try {
        //     $rule = $this->ruleService->find($id);
        //     $rule->remove = 'Y';
        //     $rule->save();
        //     DB::commit();
        //     return \back()->with('success', "has been remove success..");
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        // }
    }
}
