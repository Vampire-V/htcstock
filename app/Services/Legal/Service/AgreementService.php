<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalAgreement;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\AgreementServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AgreementService extends BaseService implements AgreementServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalAgreement $model
     */
    public function __construct(LegalAgreement $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return LegalAgreement::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return LegalAgreement::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
