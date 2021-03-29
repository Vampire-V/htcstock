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
            return Template::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return Template::with(['department'])->filter($request)->orderBy('created_at', 'desc')
            ->get();
    }
}
