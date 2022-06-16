<?php

namespace App\Services\IT\Service;

use App\Enum\TransactionTypeEnum;
use App\Models\IT\Transactions;
use App\Services\BaseService;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsService extends BaseService implements TransactionsServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Accessories $model
     */
    public function __construct(Transactions $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Transactions::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function transactionType(String $type): Builder
    {
        try {
            return Transactions::whereIn('trans_type', [$type])->whereNull('ref_no');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function stock(): Builder
    {
        try {
            return Transactions::select('access_id', DB::raw('sum(qty) as quantity'))->groupBy('access_id');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function quantityAccessorie(int $id)
    {
        try {
            return Transactions::select('access_id', DB::raw('sum(qty) as quantity'))->groupBy('access_id')->where('access_id', $id)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAccessoriesType(String $type)
    {
        try {
            return Transactions::select('access_id', DB::raw('sum(qty) as quantity'))->groupBy('access_id')->whereIn('trans_type', [$type])->whereNull('ref_no')->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filterForStock(Request $request)
    {
        return Transactions::with(['accessorie'])->filter($request)->select('access_id', DB::raw('sum(qty) as quantity'))->groupBy('access_id')->groupBy('access_id')->orderBy('quantity', 'asc')->paginate(10);
    }

    public function filterForBuy(Request $request)
    {
        return Transactions::with(['accessorie','user'])->filter($request)->whereIn('trans_type', [TransactionTypeEnum::B])->whereNull('ref_no')->orderBy('created_at', 'desc')->paginate(10);
    }
    public function filterForRequest(Request $request)
    {
        return Transactions::with(['accessorie','user'])->filter($request)->whereIn('trans_type', [TransactionTypeEnum::R])->whereNull('ref_no')->orderBy('created_at', 'desc')->paginate(10);
    }
    public function filterForLending(Request $request)
    {
        return Transactions::with(['accessorie','user'])->filter($request)->whereIn('trans_type', [TransactionTypeEnum::L])->whereNull('ref_no')->orderBy('created_at', 'desc')->paginate(10);
    }
    public function filterForHistory(Request $request)
    {
        return Transactions::with(['accessorie','user','transactionCreated'])->filter($request)->orderBy('created_at', 'desc')->paginate(10);
    }
}
