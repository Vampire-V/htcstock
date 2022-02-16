<?php

namespace App\Services\KPI\Service;

use App\Models\KPI\RuleLog;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RuleLogService extends BaseService
{
    protected $periodService;
    /**
     * UserService constructor.
     *
     * @param RuleLog $model
     */
    public function __construct(RuleLog $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return RuleLog::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return RuleLog::with(['user'])
            ->filter($request)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create_action(string $action)
    {
        try {
            return $this->create(['action' => $action]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
