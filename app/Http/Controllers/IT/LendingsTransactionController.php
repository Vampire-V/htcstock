<?php

namespace App\Http\Controllers\IT;

use App\Enum\TransactionTypeEnum;
use Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\FormSearches\LendingsFormSearch;
use App\Http\Requests\IT\LendingsFormRequest;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LendingsTransactionController extends Controller
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
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        try {
            $accessorys = $this->accessoriesService->dropdown();
            $transactions = $this->transactionsService->filterForLending($request);
            return \view('it.lendings.list', \compact('selectedAccessorys', 'accessorys', 'transactions', 'start_at', 'end_at', 'query'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
            return \view('it.lendings.create')->with('accessories', $this->accessoriesService->all()->get())->with('users', $this->userService->all()->get());
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LendingsFormRequest $request)
    {
        try {
            // qty - 
            $input = array_merge($request->all(), ['trans_type' => TransactionTypeEnum::L, 'created_by' => Auth::user()->id]);
            $input['qty'] = '-' . $request->qty;
            if (!$this->transactionsService->create($input)) {
                $request->session()->flash('error', 'error create!');
            } else {
                $request->session()->flash('success',  ' has been create');
            }
            return \redirect()->route('it.equipment.lendings.index');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
            $users = $this->userService->all()->get();
            return \view('it.lendings.edit')->with('transaction', $transaction)->with('accessories', $accessories)->with('users', $users);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LendingsFormRequest $request, $id)
    {
        try {
            if (!is_null($request->ref_no)) {
                $token = Helper::makeRandomTokenKey();
                $transaction = $this->transactionsService->find($id);

                if ($transaction->ref_no) {
                    $request->session()->flash('error', 'รายการคืนแล้ว!');
                    return \back();
                }
                $transaction->ref_no = $token;

                // $transaction->qty +
                if (!$this->transactionsService->update($transaction->attributesToArray(), $id)) {
                    $request->session()->flash('error', 'error update!');
                } else {
                    $transaction->trans_type = TransactionTypeEnum::CL;
                    $transaction->qty = substr($transaction->qty, 1);
                    $transaction->created_by = Auth::user()->id;
                    if (!$this->transactionsService->create($transaction->attributesToArray())) {
                        $request->session()->flash('error', 'error update!');
                    } else {
                        $request->session()->flash('success',  ' has been update');
                    }
                }
            }
            return \redirect()->route('it.equipment.lendings.index');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
