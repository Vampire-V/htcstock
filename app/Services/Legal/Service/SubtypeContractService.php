<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalSubtypeContract;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\SubtypeContractServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class SubtypeContractService extends BaseService implements SubtypeContractServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalSubtypeContract $model
     */
    public function __construct(LegalSubtypeContract $model)
    {
        parent::__construct($model);
    }

    public function dropdown(int $agreement): Collection
    {
        try {
            return LegalSubtypeContract::whereIn('agreement_id',[$agreement])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
