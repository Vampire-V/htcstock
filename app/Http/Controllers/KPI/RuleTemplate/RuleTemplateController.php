<?php

namespace App\Http\Controllers\KPI\RuleTemplate;

use App\Enum\KPIEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\KPI\StoreRuleTemplatePost;
use App\Http\Resources\KPI\RuleTemplateCollection;
use App\Http\Resources\KPI\RuleTemplateResource;
use App\Http\Resources\KPI\TemplateResource;
use App\Models\KPI\RuleTemplate;
use App\Services\IT\Interfaces\DepartmentServiceInterface;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use App\Services\KPI\Service\RuleCategoryService;
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
        RuleCategoryService $categoryServiceInterface,
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
        $query = $request->all();
        $selectRuleTemp = \collect($request->template_id);
        $selectDepartment = \collect($request->department_id);
        try {
            $departments = $this->departmentService->dropdown();
            $templates = $this->templateService->dropdown();
            $ruleTemplates = $this->templateService->filter($request);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.RuleTemplate.index', \compact('departments', 'templates', 'ruleTemplates', 'selectRuleTemp', 'selectDepartment', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($template)
    {
        try {
            $category = $this->categoryService->dropdown();
            $template = $this->templateService->find($template);
            $rules = $this->ruleService->dropdown();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('kpi.RuleTemplate.create', \compact('category', 'template', 'rules'));
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
        try {
            $template = $this->templateService->find($template);
            $rule = $this->ruleService->find($request->rule_id);
            $rule_template = $template->ruleTemplate->filter(fn ($item) => $item->rule->category_id === $rule->category_id);

            $template->ruleTemplate()->create(
                [
                    'rule_id' => $rule->id,
                    'parent_rule_template_id' => $rule_template->count() + 1,
                    'base_line' => $rule->base_line,
                    'max_result' => $rule->max,
                ]
            );
            // $template->save();

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template->refresh()), "Created rule template", 200);
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
            $departments = $this->departmentService->dropdown();
            $ruletemplate = $this->ruleTemplateService->find($id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
            $template = $this->templateService->ruleTemplate($template);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse(RuleTemplateResource::collection($template->ruleTemplate), "rule by template", 200);
    }

    public function switchrow(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $template = $this->templateService->find($id);
            $first = $this->ruleTemplateService->find($request->rule_template_id);
            $second = $this->ruleTemplateService->find($request->rule_to_id);

            $first->parent_rule_template_id = $second->getOriginal('parent_rule_template_id');
            $second->parent_rule_template_id = $first->getOriginal('parent_rule_template_id');
            $first->save();
            $second->save();
            $template->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "Change number rule template", 200);
    }

    public function deleteRuleTemplate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->ruleTemplateService->destroy($request->rule);
            $template = $this->templateService->find($id);
            $ruleTemplate = $this->ruleTemplateService->byTemplateAndGroup($template, $request->group['id']);
            foreach ($ruleTemplate as $key => $value) {
                $value->parent_rule_template_id = $key + 1;
                $value->save();
            }
            $template->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
        DB::commit();
        return $this->successResponse(new TemplateResource($template), "Deleted rule template", 200);
    }
}
