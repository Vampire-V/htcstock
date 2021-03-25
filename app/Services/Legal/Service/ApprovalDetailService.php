<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalApprovalDetail;
use App\Models\Legal\LegalContract;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ApprovalDetailServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ApprovalDetailService extends BaseService implements ApprovalDetailServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalApprovalDetail $model
     */
    public function __construct(LegalApprovalDetail $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        try {
            return LegalApprovalDetail::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function byContract(LegalContract $contract): Collection
    {
        try {
            return LegalApprovalDetail::where('contract_id', $contract->id)->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function userActive(User $user, LegalContract $contract): Model
    {
        try {
            $legalApproval = LegalApprovalDetail::where('user_id', $user->id)->where('contract_id', $contract->id)->first();
            return $legalApproval;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalByLevel(LegalContract $contract, int $levels)
    {
        try {
            return LegalApprovalDetail::where('levels', $levels)->where('contract_id', $contract->id)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function approvalByLevelLastTime(LegalContract $contract, int $levels)
    {
        try {
            return LegalApprovalDetail::where('levels', $levels)->where('contract_id', $contract->id)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function contractLastTime(LegalContract $contract)
    {
        try {
            return LegalApprovalDetail::where('contract_id', $contract->id)->orderBy('updated_at', 'desc')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
