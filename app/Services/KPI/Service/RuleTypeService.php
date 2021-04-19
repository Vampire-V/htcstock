<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\KpiRuleType;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\RuleTypeServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RuleTypeService extends BaseService implements RuleTypeServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param KpiRuleType $model
     */
    public function __construct(KpiRuleType $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return KpiRuleType::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown($group = null): Collection
    {
        try {
            return KpiRuleType::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
