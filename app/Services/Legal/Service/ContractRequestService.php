<?php

namespace App\Services\Legal\Service;

use App\Enum\ContractEnum;
use App\Models\Legal\LegalContract;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractRequestService extends BaseService implements ContractRequestServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalContract $model
     */
    public function __construct(LegalContract $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        try {
            return LegalContract::orderBy('created_at', 'desc')->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getByCreated()
    {
        try {
            return LegalContract::where('created_by', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where('trash', false)
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    }

    public function filterDraft(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where(['trash'=> false,'status' => ContractEnum::D])
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function filterRequest(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where(['trash'=> false,'status' => ContractEnum::RQ])
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function filterChecking(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where(['trash'=> false,'status' => ContractEnum::CK])
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function filterProviding(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where(['trash'=> false,'status' => ContractEnum::P])
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function filterComplete(Request $request)
    {
        return LegalContract::with(['legalContractDest','legalAgreement','createdBy'])
        ->filter($request)
        ->where(['trash'=> false,'status' => ContractEnum::CP])
        ->orderBy('status', 'ASC')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function filterForAdmin(Request $request)
    {
        return LegalContract::filter($request)->where('trash', false)->orderBy('status', 'ASC')->orderBy('created_at', 'desc')
        ->get();
        // ->paginate(10);
    }

    public function totalpromised(): int
    {
        try {
            return LegalContract::where('trash',false)->count();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function ownpromised(User $user): int
    {
        try {
            return LegalContract::where(['trash'=>false,'created_by' => $user->id])->count();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function countStatus(string $status): int
    {
        try {
            return LegalContract::where(['trash'=>false,'status' => $status])->count();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function notRequest(string $request)
    {
        try {
            return LegalContract::whereNotIn('status',[$request])->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function requestorInSystem()
    {
        try {
            return LegalContract::select('created_by')->where('trash',false)->groupBy('created_by')->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
