<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalPaymentType;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentTypeService extends BaseService implements PaymentTypeServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalPaymentType $model
     */
    public function __construct(LegalPaymentType $model)
    {
        parent::__construct($model);
    }

    public function dropdown(int $agreement): Collection
    {
        try {
            return LegalPaymentType::whereIn('agreement_id',[$agreement])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
