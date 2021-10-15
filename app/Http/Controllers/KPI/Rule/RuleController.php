<?php

namespace App\Http\Controllers\KPI\Rule;

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

class RuleController extends Controller
{

    protected $ruleCategoryService, $targetUnitService, $ruleService, $ruleTypeService, $userService,
        $departmentService,$rulelogService, $excel_errors = [], $rule_attrs = [];
    public function __construct(
        RuleCategoryServiceInterface $ruleCategoryServiceInterface,
        TargetUnitServiceInterface $targetUnitServiceInterface,
        RuleServiceInterface $ruleServiceInterface,
        RuleTypeServiceInterface $ruleTypeServiceInterface,
        UserServiceInterface $userServiceInterface,
        DepartmentService $departmentServiceInterface,
        RuleLogService $rulelogService
    ) {
        $this->ruleCategoryService = $ruleCategoryServiceInterface;
        $this->targetUnitService = $targetUnitServiceInterface;
        $this->ruleService = $ruleServiceInterface;
        $this->ruleTypeService = $ruleTypeServiceInterface;
        $this->userService = $userServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->rulelogService = $rulelogService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $searchRuleName = $request->ruleName;
        $selectedCategory = collect($request->category_id);
        $selectedRuleType = collect($request->rule_type);
        $template = Storage::url('kpi/template/template-rule.xlsx');
        $rulesType = $this->ruleTypeService->dropdown();
        try {
            $category = $this->ruleCategoryService->dropdown();
            $rules = $this->ruleService->filter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.RuleList.index', \compact('rules', 'category', 'query', 'searchRuleName', 'selectedCategory', 'template', 'rulesType', 'selectedRuleType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = $this->ruleCategoryService->dropdown();
        $unit = $this->targetUnitService->dropdown();
        $rulesType = $this->ruleTypeService->dropdown();
        $users = $this->userService->dropdown();
        $calcuTypes = \collect([KPIEnum::positive, KPIEnum::negative, KPIEnum::zero_oriented_kpi]);
        $quarter_cals = \collect([KPIEnum::average, KPIEnum::sum, KPIEnum::last_month]);
        $departments = $this->departmentService->dropdown();
        $rules = $this->ruleService->dropdown($category->firstWhere('name', 'kpi')->id);
        return \view('kpi.RuleList.create', \compact('category', 'unit', 'calcuTypes', 'rulesType', 'users', 'departments', 'rules', 'quarter_cals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRulePost $request)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        DB::beginTransaction();
        $fromValue = $request->except(['_token']);
        try {
            $rule = $this->ruleService->create($fromValue);
            if (!$rule) {
                $request->session()->flash('error', ' has been create fail');
                return \back();
            }
            $request->session()->flash('success', ' has been create success');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('kpi.rule-list.edit', $rule->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $rule = $this->ruleService->find($id);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(new RuleResource($rule), "rule show ", 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        $calcuTypes = \collect([KPIEnum::positive, KPIEnum::negative, KPIEnum::zero_oriented_kpi]);
        $quarter_cals = \collect([KPIEnum::average, KPIEnum::sum, KPIEnum::last_month]);
        try {
            $rule = Rule::with(['parent_to', 'updatedby'])->where('id', $id)->first();
            $category = $this->ruleCategoryService->dropdown();
            $unit = $this->targetUnitService->dropdown();
            $rulesType = $this->ruleTypeService->dropdown();
            $users = $this->userService->dropdown();
            $departments = $this->departmentService->dropdown();
            $rules = $this->ruleService->dropdown($category->firstWhere('name', 'kpi')->id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.RuleList.edit', \compact('rule', 'rules', 'category', 'unit', 'calcuTypes', 'rulesType', 'users', 'departments', 'quarter_cals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRulePost $request, $id)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        DB::beginTransaction();
        $fromValue = $request->except(['_token', '_method']);
        try {
            $this->ruleService->update($fromValue, $id);
            $request->session()->flash('success', ' has been updated success');
        } catch (\Exception $e) {
            DB::rollBack();
            $request->session()->flash('error', ' has been update fail');
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        DB::beginTransaction();
        try {
            $rule = $this->ruleService->find($id);
            $rule->remove = 'Y';
            $rule->save();
            DB::commit();
            return \back()->with('success', "has been remove success..");
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Get the Data for dropdown.
     *
     * @return \Illuminate\Http\Response
     */
    public function dropdown($group)
    {
        try {
            $rule = $this->ruleService->dropdown($group);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->successResponse(RuleResource::collection($rule), "rule dropdown ", 200);
    }

    public function upload(Request $request)
    {
        $status = \false;
        $message = "Import rule error!";

        $temporaryFile = TemporaryFile::where('folder', $request->file)->first();
        if (!$temporaryFile) {
            return \response()->json(["message" => "file not found!"], 422);
        }

        if (!strpos(Storage::files('public/kpi/template')[0], $temporaryFile->filename)) {
            return \response()->json(["message" => "It is not a template file."], 422);
        }
        DB::beginTransaction();
        try {
            $file = Storage::path('kpi/' . $temporaryFile->folder . '/' . $temporaryFile->filename);
            $read_data = Excel::toCollection(new RulesNImport(), $file, null, \Maatwebsite\Excel\Excel::XLSX);
            $datas = $read_data[0]->filter(fn ($value) => $value[1] !== null);
            $category = $this->ruleCategoryService->dropdown();
            $rule_type = $this->ruleTypeService->dropdown();
            $users = $this->userService->dropdown();
            $departments = $this->departmentService->dropdown();

            $datas->each(function ($value, $key) use ($category, $rule_type, $users, $departments) {
                $row = $key + 6;
                $rule_name = $value[1];
                $group_name = $value[2];
                $detinition = $value[3];
                $calculation_machianism = $value[4];
                $calcu_name = $value[5];
                $rule_type_name = $value[6];
                $username = $value[7];
                $dept_name = $value[8];
                $base_line = $value[9];
                $max = $value[10];

                $group = $category->filter(fn ($obj) => $obj->name === $group_name);
                $type = $rule_type->filter(fn ($obj) => $obj->name === $rule_type_name);
                $user = $users->filter(fn ($obj) => $obj->username === strval($username));
                $department = $departments->filter(fn ($obj) => $obj->name === strval($dept_name));
                $checkName = $this->ruleService->isName($rule_name);

                if ($checkName) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'B';
                    $error->message = 'มีอยู่แล้ว';
                    array_push($this->excel_errors, $error);
                }
                if ($group->count() < 1) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'C';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if (is_null($calcu_name)) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'F';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if ($type->count() < 1) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'G';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if ($user->count() < 1) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'H';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if ($department->count() < 1) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'I';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if (is_null($base_line)) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'J';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }
                if (is_null($max)) {
                    $error = new stdClass;
                    $error->row = $row;
                    $error->col = 'K';
                    $error->message = 'ไม่มี';
                    array_push($this->excel_errors, $error);
                }

                if ($group->count() > 0 && !is_null($calcu_name) && $type->count() > 0 && !$checkName && $user->count() > 0 && $department->count() > 0 && !is_null($base_line) && !is_null($max)) {
                    \array_push(
                        $this->rule_attrs,
                        [
                            'name' => $rule_name,
                            'category_id' => $group->first()->id,
                            'description' => $detinition,
                            'desc_m' => $calculation_machianism,
                            'calculate_type' => $calcu_name,
                            'kpi_rule_types_id' => $type->first()->id,
                            'user_actual' => $user->first()->id,
                            'department_id' => $department->first()->id,
                            'base_line' => $base_line,
                            'max' => $max,
                            'created_at' => date("Y-m-d H:i:s"),
                            'updated_at' => date("Y-m-d H:i:s"),
                        ]
                    );
                }
            });

            if ($this->rule_attrs) {
                $status = $this->ruleService->insert($this->rule_attrs);
                $message = $status ? "Import rule success!" : $message;
                if ($temporaryFile->delete()) {
                    Storage::deleteDirectory('kpi/' . $temporaryFile->folder);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(['errors' => $this->excel_errors, 'status' => $status], $message, 200);
    }

    public function import_rule(Request $request)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        $temporaryFile = TemporaryFile::where('folder', $request->file)->first();
        if (!$temporaryFile) {
            return \response()->json(["message" => "file not found!"], 422);
        }

        if (!Storage::exists('kpi/' . $temporaryFile->folder . '/' . $temporaryFile->filename)) {
            return \response()->json(["message" => "file not found!"], Response::HTTP_FORBIDDEN);
        }
        $file = Storage::path('kpi/' . $temporaryFile->folder . '/' . $temporaryFile->filename);

        $caltype = \collect([KPIEnum::positive,KPIEnum::negative,KPIEnum::zero_oriented_kpi]);
        $quarter_cal = \collect([KPIEnum::sum,KPIEnum::average,KPIEnum::last_month]);
        DB::beginTransaction();
        try {
            $import = Excel::toCollection(new RulesImport(), $file);
            $rules = [];
            $errors = [];
            foreach ($import->first() as $key => $row) {
                if (!is_null($row[0])) {
                    if ($key > 3) {
                        $rule = Rule::where('name', $row[0])->exists() ? null : $row[0];
                        $category = RuleCategory::where('name', $row[1])->first();
                        $type = KpiRuleType::where('name', $row[2])->first();
                        $employee = User::where('username', $row[3])->first();
                        $calcu = $caltype->contains($row[4]) ? $row[4] : null;
                        $quarter = $quarter_cal->contains($row[5]) ? $row[5] : null;
                        $department = Department::where('name', $row[6])->first();
                        $parent = Rule::where('name', $row[9])->first();
                        $arr = [$rule,$category,$type,$employee,$calcu,$quarter,$department,$row[7],$row[8]];
                        if (!in_array(null,$arr)) {
                            $rules[] = [
                                'name' => trim($row[0]),
                                'category_id' => $category->id,
                                'kpi_rule_types_id' => $type->id,
                                'user_actual' => $employee->id,
                                'calculate_type' => trim($row[4]),
                                'quarter_cal' => trim($row[5]),
                                'department_id' => $department->id,
                                'base_line' => trim($row[7]),
                                'max' => trim($row[8]),
                                'parent' => $row[9] ? $parent->id : $row[9],
                                'description' => trim($row[10]),
                                'desc_m' => trim($row[11]),
                                'created_by' => \auth()->id(),
                                'created_at' => \now()
                            ];
                        }else{
                            $head = $import->first()[2][array_search(null, $arr)];
                            $failure = ['row' => $key+1,'column' => $head, 'message' => "'".$row[array_search(null, $arr)]."'" . " not available"]; //new Failure($key+1,$head,["not available"]);
                            $errors[] = $failure;
                        }
                    }
                }
            }
            Rule::insert($rules);
            DB::commit();
            return $this->successResponse($errors,"Import rule success...", Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function rulesdowload()
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }
        DB::beginTransaction();
        try {
            $this->rulelogService->create_action('Dowlond rule');
            DB::commit();
            return Excel::download(new RulesExport(), "Rules_" . now() . ".xlsx");
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function rulesnotin(Request $request)
    {
        try {
            $rules = Rule::whereNotIn('id', [...$request->rules])->where('category_id', $request->group)->where('remove','<>','Y')->get();
            return $this->successResponse($rules, "Success rules...", 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function switchrule(Request $request)
    {
        DB::beginTransaction();
        try {
            $new_rule = $this->ruleService->find($request->new_rule);
            $row = EvaluateDetail::find($request->form_detail_id);
            $row->rule_id = $new_rule->id;
            $row->save();
            DB::commit();
            return $this->successResponse($new_rule, "Success update...", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function template_rule(Request $request)
    {
        if (!Gate::allows(UserEnum::OPERATIONKPI)) {
            return \redirect()->back()->with('error', "Error : no authorize.." );
        }

        $employee = $this->userService->employee_excel();
        $category = $this->ruleCategoryService->category_excel();
        $type = $this->ruleTypeService->type_excel();
        $dept = $this->departmentService->dropdown_excel();
        $rules = $this->ruleService->rule_excel();

        return Excel::download(new TemplateRulesExport($employee, $category, $type, $dept, $rules), "Rules_Template.xlsx");
    }
}
