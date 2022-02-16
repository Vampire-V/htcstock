<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\Template;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\TemplateServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TemplateService extends BaseService implements TemplateServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Template $model
     */
    public function __construct(Template $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Template::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Template::where('remove','N')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        try {
            return Template::with(['department'])->filter($request)
                ->where('user_created', \auth()->id())
                ->where('remove', 'N')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function ruleTemplate($template_id)
    {
        try {
            return Template::with('ruleTemplate')->find($template_id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forCreated(int $id)
    {
        try {
            return Template::where('user_created', $id)->where('remove', 'N')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
