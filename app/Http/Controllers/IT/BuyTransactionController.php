<?php

namespace App\Http\Controllers\IT;

use App\Enum\TransactionTypeEnum;
use Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\FormSearches\BuyFormSearch;
use App\Http\Requests\IT\BuyFormRequest;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyTransactionController extends Controller
{
    protected $accessoriesService;
    protected $transactionsService;
    protected $userService;
    public function __construct(
        AccessoriesServiceInterface $accessoriesServiceInterface,
        TransactionsServiceInterface $transactionsServiceInterface,
        UserServiceInterface $userServiceInterface
    ) {
        $this->accessoriesService = $accessoriesServiceInterface;
        $this->transactionsService = $transactionsServiceInterface;
        $this->userService = $userServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = $request->all();
        $selectedAccessorys = collect($request->accessory);
        $ir_no = $request->ir_no;
        $po_no = $request->po_no;
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        try {
            $accessorys = $this->accessoriesService->dropdown();
            $transactions = $this->transactionsService->filterForBuy($request);
            return \view('it.buys.list', \compact('selectedAccessorys', 'accessorys', 'transactions', 'ir_no', 'po_no', 'start_at', 'end_at', 'query'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return \view('it.buys.create')->with('accessories', $this->accessoriesService->all()->get());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyFormRequest $request)
    {
        try {
            $attributes = array_merge($request->except(['_token']), ['trans_type' => TransactionTypeEnum::B, 'trans_by' => Auth::user()->id, 'created_by' => Auth::user()->id]);
            $create = $this->transactionsService->create($attributes);
            if (!$create) {
                $request->session()->flash('error', 'error create!');
                return \back();
            }
            $request->session()->flash('success',  ' has been create');
            return \redirect()->route('it.buy.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $accessories = $this->accessoriesService->all()->get();
            $transaction = $this->transactionsService->find($id);
            return \view('it.buys.edit')->with('transaction', $transaction)->with('accessories', $accessories);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BuyFormRequest $request, $id)
    {
        try {
            $token = Helper::makeRandomTokenKey();
            $transaction = $this->transactionsService->find($id);
            if (!is_null($request->ref_no)) {

                // ตรวจสอบ stock ว่าเหลือเท่าตอนซื้อ
                if ($this->transactionsService->quantityAccessorie($transaction->access_id)->quantity < $transaction->qty) {
                    $request->session()->flash('error', 'มีของในคลังไม่พอให้ยกเลิก!');
                    return \back();
                }
                // ตรวจสอบ Ref_no
                if ($transaction->ref_no) {
                    $request->session()->flash('error', 'รายการเคยยกเลิกแล้ว!');
                    return \back();
                }
                $transaction->ref_no = $token;
                if (!$this->transactionsService->update($transaction->attributesToArray(), $id)) {
                    $request->session()->flash('error', 'error update!');
                } else {
                    $transaction->trans_type = TransactionTypeEnum::CB;
                    $transaction->qty = '-' . $transaction->qty;
                    if (!$this->transactionsService->create($transaction->attributesToArray())) {
                        $request->session()->flash('error', 'error update!');
                    } else {
                        $request->session()->flash('success',  ' has been update');
                    }
                }
            } else {
                $request->session()->flash('error', 'ไม่ให้แก้ไข!');
                return \back();
            }
            return \redirect()->route('it.buy.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
