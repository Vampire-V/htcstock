<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\Rule;
use App\Models\KPI\RuleTemplate;
use App\Models\KPI\Template;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\RuleTemplateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RuleTemplateService extends BaseService implements RuleTemplateServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param RuleTemplate $model
     */
    public function __construct(RuleTemplate $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return RuleTemplate::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return RuleTemplate::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byTemplate(Template $template): Collection
    {
        try {
            $ruleTem = RuleTemplate::where('template_id', $template->id)->orderBy('parent_rule_template_id')->get();
            return $ruleTem;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byTemplateGroup(Template $template, string $group) : Collection
    {
        try {
            return RuleTemplate::leftJoin('kpi_rules', 'kpi_rules.id', '=', 'kpi_rule_templates.rule_id')
                ->select('kpi_rule_templates.*')->where('kpi_rules.category_id', $group)
                ->where('kpi_rule_templates.template_id', $template->id)->orderBy('kpi_rule_templates.parent_rule_template_id')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return RuleTemplate::filter($request)->orderBy('created_at', 'desc')
            ->get();
    }
}
