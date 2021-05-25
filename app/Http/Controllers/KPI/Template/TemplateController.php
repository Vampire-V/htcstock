<?php

namespace App\Http\Controllers\KPI\Template;

use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreTemplatePost;
use App\Http\Resources\KPI\TemplateResource;
use App\Models\KPI\Template;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    protected $templateService;
    protected $ruleTemplateService;
    protected $departmentService;
    public function __construct(
        TemplateServiceInterface $templateServiceInterface,
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface
    ) {
        $this->templateService = $templateServiceInterface;
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
        $this->departmentService = $departmentServiceInterface;
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
            $dropdowntem = $this->templateService->dropdown();
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
            $departments = $this->departmentService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }

        return \view('kpi.RuleTemplate.Template.create', \compact('departments'));
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
        //
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
            $template->weight_kpi = 70;
            $template->weight_key_task = 30;
            $template->weight_omg = 0;
            $template->save();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "Created template", 200);
    }
}
