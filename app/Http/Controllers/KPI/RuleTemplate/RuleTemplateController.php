<?php

namespace App\Http\Controllers\KPI\RuleTemplate;

use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreRuleTemplatePost;
use App\Models\KPI\RuleCategory;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleTemplateController extends Controller
{
    protected $ruleTemplateService;
    protected $departmentService;
    protected $templateService;
    protected $categoryService;
    public function __construct(
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        TemplateServiceInterface $templateServiceInterface,
        RuleCategory $categoryServiceInterface
    ) {
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->templateService = $templateServiceInterface;
        $this->categoryService = $categoryServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = $this->departmentService->dropdown();
        $templates = $this->templateService->dropdown();
        $ruleTemplates = $this->templateService->filter($request);

        return \view('kpi.RuleTemplate.index', \compact('departments', 'templates', 'ruleTemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($template)
    {
        try {
            $category = $this->categoryService->all();
            $template = $this->templateService->find($template);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.RuleTemplate.create',\compact('category','template'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($template,StoreRuleTemplatePost $request)
    {
        DB::beginTransaction();
        $fromValue = $request->except(['_token']);
        try {
            $ruleTemplate = $this->ruleTemplateService->create($fromValue);
            if (!$ruleTemplate) {
                $request->session()->flash('error', ' has been create Rule Template fail');
                return \back();
            }
            $request->session()->flash('success', ' has been create success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
        // \dd(new JsonResponse($ruleTemplate));
        DB::commit();
        return new JsonResponse($ruleTemplate);
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
            $category = $this->categoryService->all();
            $departments = $this->departmentService->dropdown();
            $ruletemplate = $this->ruleTemplateService->find($id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('kpi.RuleTemplate.edit',\compact('ruletemplate','departments','category'));
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
