<?php

namespace App\Http\Controllers\KPI\RuleTemplate;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreRuleTemplatePost;
use App\Http\Resources\KPI\RuleTemplateCollection;
use App\Http\Resources\KPI\RuleTemplateResource;
use App\Models\KPI\RuleCategory;
use App\Models\KPI\RuleTemplate;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\RuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleTemplateController extends Controller
{
    protected $ruleTemplateService;
    protected $departmentService;
    protected $templateService;
    protected $categoryService;
    protected $ruleService;
    public function __construct(
        RuleTemplateServiceInterface $ruleTemplateServiceInterface,
        DepartmentServiceInterface $departmentServiceInterface,
        TemplateServiceInterface $templateServiceInterface,
        RuleCategory $categoryServiceInterface,
        RuleService $ruleServiceInterface
    ) {
        $this->ruleTemplateService = $ruleTemplateServiceInterface;
        $this->departmentService = $departmentServiceInterface;
        $this->templateService = $templateServiceInterface;
        $this->categoryService = $categoryServiceInterface;
        $this->ruleService = $ruleServiceInterface;
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
        return \view('kpi.RuleTemplate.create', \compact('category', 'template'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($template, StoreRuleTemplatePost $request)
    {
        DB::beginTransaction();
        $formValue = $request->except(['_token']);
        try {
            $ruleTemplate = $this->ruleTemplateService->create($formValue);
            if (\is_null($ruleTemplate->parent_rule_template_id)) {
                $ruleTemplate->parent_rule_template_id = 1;
            }
            if (!$ruleTemplate) {
                $request->session()->flash('error', ' has been create Rule Template fail');
                return \back();
            }

            $template = $this->templateService->find($template);

            $ruleTemplates = $this->ruleTemplateService->byTemplate($template);
            $request->session()->flash('success', ' has been create success');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return RuleTemplateResource::collection($ruleTemplates);
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
        return \view('kpi.RuleTemplate.edit', \compact('ruletemplate', 'departments', 'category'));
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

    public function bytemplate($template)
    {
        try {
            $template = $this->templateService->find($template);
            $ruleTemplates = $this->ruleTemplateService->byTemplate($template);
        } catch (\Throwable $th) {
            throw $th;
        }
        return RuleTemplateResource::collection($ruleTemplates);

        // return new JsonResponse($ruleTemplates);
    }

    public function switchrow(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $switch_id = $request->rule_template_id;
            $switch_to = $request->rule_to_id;

            $first = $this->ruleTemplateService->find($switch_id);
            $second = $this->ruleTemplateService->find($switch_to);

            $first->parent_rule_template_id = $second->getOriginal('parent_rule_template_id');
            $second->parent_rule_template_id = $first->getOriginal('parent_rule_template_id');
            $first->save();
            $second->save();

            $template = $this->templateService->find($id);
            $ruleTemplates = $this->ruleTemplateService->byTemplate($template);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return RuleTemplateResource::collection($ruleTemplates);
    }

    public function deleteRuleTemplate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->ruleTemplateService->destroy($request->rule);
            $template = $this->templateService->find($id);
            $ruleTemplate = $this->ruleTemplateService->byTemplateGroup($template, $request->group['id']);
            foreach ($ruleTemplate as $key => $value) {
                $value->parent_rule_template_id = $key + 1;
                $value->save();
            }
            $ruleTemplates = $this->ruleTemplateService->byTemplate($template);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
        return RuleTemplateResource::collection($ruleTemplates);
    }
}
