<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalComercialList;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ComercialListsService extends BaseService implements ComercialListsServiceInterface
{
    /**
     * ComercialListsService constructor.
     *
     * @param LegalComercialList $model
     */
    public function __construct(LegalComercialList $model)
    {
        parent::__construct($model);
    }

    public function comercialByContractID(int $id): Collection
    {
        try {
            return LegalComercialList::whereIn('contract_id',[$id])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
