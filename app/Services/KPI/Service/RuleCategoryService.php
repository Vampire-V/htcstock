<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\RuleCategory;
use App\Services\BaseService;
use App\Services\KPI\Interfaces\RuleCategoryServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class RuleCategoryService extends BaseService implements RuleCategoryServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param RuleCategory $model
     */
    public function __construct(RuleCategory $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return RuleCategory::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return RuleCategory::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function category_excel(): array
    {
        try {
            $arra = [];
            $result = $this->dropdown();
            foreach ($result as $key => $item) {
                $arra[] = [
                    'slug' => $item->name, 
                    'name' => $item->description
                ];
            }
            return $arra;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
