<?php
namespace App\Services\Legal\Service;

use App\Models\Legal\LegalContractDest;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;

class ContractDescService extends BaseService implements ContractDescServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalContractDest $model
     */
    public function __construct(LegalContractDest $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        try {
            return LegalContractDest::paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function search($id)
    {
        return LegalContractDest::where('id',$id)->with('legalcontract')->first();
    }
}