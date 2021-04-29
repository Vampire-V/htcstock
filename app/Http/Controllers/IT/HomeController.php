<?php

namespace App\Http\Controllers\IT;

use App\Enum\TransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Services\IT\Interfaces\TransactionsServiceInterface;

class HomeController extends Controller
{
    protected $transactionsService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TransactionsServiceInterface $transactionsServiceInterface)
    {
        $this->transactionsService = $transactionsServiceInterface;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $leading = 0;
        $requisition = 0;
        try {
            $leadingQty = $this->transactionsService->getAccessoriesType(TransactionTypeEnum::L);
            $requisitionQty = $this->transactionsService->getAccessoriesType(TransactionTypeEnum::R);
            if ($leadingQty) {
                $leading = \substr($leadingQty->quantity,1);
            }
            if ($requisitionQty) {
                $requisition = \substr($requisitionQty->quantity,1);
            }

            return view('it.home')->with(['leadingTotal' => $leading, 'requisitionTotal' => $requisition]);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
