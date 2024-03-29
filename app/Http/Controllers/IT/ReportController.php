<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use App\Http\FormSearches\TransactionsFormSearch;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use App\Services\IT\Interfaces\UserServiceInterface;
use Barryvdh\DomPDF\Facade as PDF;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $transactionsService;
    protected $accessoriesService;
    protected $userServiceInterface;
    public $accessories;
    public function __construct(TransactionsServiceInterface $transactionsServiceInterface, AccessoriesServiceInterface $accessoriesServiceInterface,UserServiceInterface $userServiceInterface)
    {
        $this->transactionsService = $transactionsServiceInterface;
        $this->accessoriesService = $accessoriesServiceInterface;
        $this->userService = $userServiceInterface;
    }

    public function reportTransactions(Request $request)
    {
        $query = $request->all();
        $selectedAccessorys = collect($request->accessory);
        $selectedUsers = collect($request->user);
        $start_at = $request->start_at;
        $end_at = $request->end_at;
        try {
            $accessorys = $this->accessoriesService->dropdown();
            $users = $this->userService->dropdown();
            $transactions = $this->transactionsService->filterForHistory($request);
            return \view('it.reports.transactions', \compact('selectedAccessorys', 'selectedUsers', 'accessorys', 'users', 'transactions', 'start_at', 'end_at', 'query'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function reportStocks(Request $request)
    {
        $query = $request->all();
        $access_id = $request->access_id;
        try {
            $accessories = $this->accessoriesService->dropdown();
            $transactions = $this->transactionsService->filterForStock($request);
            return \view('it.reports.stocks', \compact('transactions', 'accessories', 'query', 'access_id'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function checkStock(int $id)
    {
        try {
            $result = $this->transactionsService->quantityAccessorie($id);
            if (is_null($result)) {
                return response()->json(['message' => "No data transactions"], 200);
            }
            return response()->json(['name' => $result->accessorie->access_name, 'qty' => (int) $result->quantity], 200);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    public function generateAccessoriesPDF()
    {
        try {
            $accessories = $this->accessoriesService->sumAccessories()->get();
            $pdf = PDF::loadView('it.reports.pdf', compact('accessories', $accessories));
            return $pdf->stream();
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
