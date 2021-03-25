<?php

namespace App\Services\Legal\Service;

use App\Models\Department;
use App\Models\Legal\LegalApproval;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ApprovalServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ApprovalService extends BaseService implements ApprovalServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalApproval $model
     */
    public function __construct(LegalApproval $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return LegalApproval::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalByDepartment(Department $department): Collection
    {
        try {
            return LegalApproval::where('department_id', $department->id)->orderBy('levels', 'asc')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return LegalApproval::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalLevelLess(int $levels, int $department): Model
    {
        try {
            return LegalApproval::where('levels', '<', $levels)->where('department_id', $department)->orderBy('levels', 'desc')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalLevelOver(int $levels, int $department): Model
    {
        try {
            return LegalApproval::where('levels', '>', $levels)->where('department_id', $department)->orderBy('levels', 'asc')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalLevelAllOver(int $levels, int $department): Collection
    {
        try {
            return LegalApproval::where('levels', '>', $levels)->where('department_id', $department)->orderBy('levels', 'asc')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
