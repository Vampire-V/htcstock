<?php

namespace App\Http\Controllers\KPI\Template;

use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreTemplatePost;
use App\Http\Resources\KPI\TemplateResource;
use App\Models\KPI\Template;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\IT\Service\UserService;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\RuleCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\KPI\Interfaces\RuleServiceInterface;

class TemplateController extends Controller
{
    protected $templateService, $ruleTemplateService, $departmentService,
        $categoryService, $userService, $ruleService;
    public function __construct(
        TemplateServiceInterface $templateServiceInterface,
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        RuleCategoryService $categoryServiceInterface,
        UserService $userService,
        RuleServiceInterface $ruleServiceInterface,
    ) {
        $this->templateService = $templateServiceInterface;
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->categoryService = $categoryServiceInterface;
        $this->userService = $userService;
        $this->ruleService = $ruleServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();
        $selectRuleTemp = \collect($request->template_id);
        $selectDepartment = \collect($request->department_id);
        try {
            $departments = $this->departmentService->dropdown();
            $dropdowntem = $this->templateService->all()->where('user_created', auth()->id())->get();
            $templates = $this->templateService->filter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.RuleTemplate.index', \compact('departments', 'templates', 'dropdowntem', 'query', 'selectRuleTemp', 'selectDepartment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $category = $this->categoryService->dropdown();
            $departments = $this->departmentService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \view('kpi.RuleTemplate.Template.create', \compact('departments', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTemplatePost $request)
    {
        DB::beginTransaction();
        $fromValue = $request->except(['_token']);
        try {
            $template = $this->templateService->create($fromValue);
            if (!$template) {
                $request->session()->flash('error', ' has been create template fail');
                return \back();
            }
            $request->session()->flash('success', ' has been create success');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('kpi.rule-template.create', $template->id);
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
            $template = $this->templateService->find($id);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "find template", 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = new TemplateResource($this->templateService->find($id));
        $category = $this->categoryService->dropdown();
        $rules = $this->ruleService->dropdown();
        return \view('kpi.RuleTemplate.Template.edit', \compact('template', 'category', 'rules'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $template = $this->templateService->find($id);
            $template->remove = 'Y';
            $template->save();
            DB::commit();
            return \redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_dynamic(StoreTemplatePost $request)
    {
        DB::beginTransaction();
        try {
            $template = new Template();
            $template->name = $request->name;
            $template->department_id = \auth()->user()->department->id;
            $template->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "Created template", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_dynamic(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $template = Template::find($id);
            $template->weight_kpi = $request->kpi ?? 0.00;
            $template->weight_key_task = $request->key_task ?? 0.00;
            $template->weight_omg = $request->omg ?? 0.00;
            $template->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "Update weight success!", 200);
    }

    public function rename(Request $request, $id)
    {
        $validate = Validator::make(
            $request->only('name'),
            ['name' => 'required']
        );
        if ($validate->fails()) {
            return $this->errorResponse($validate->errors(), 500);
        }

        try {
            $template = Template::find($id);
            $template->name = $request->name;
            $template->save();
            DB::commit();
            return $this->successResponse(true, "Rename template success!", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }


    public function transferToUser(Request $request, $id)
    {
        $validate = Validator::make(
            $request->only('user'),
            ['user' => 'required']
        );

        if ($validate->fails()) {
            return $this->errorResponse($validate->errors(), 500);
        }
        DB::beginTransaction();
        try {
            $this->userService->find($request->user);
            $template = $this->templateService->find($id);
            $template->user_created = $request->user;
            $template->save();
            DB::commit();
            return $this->successResponse(true, "Transfer to ", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
