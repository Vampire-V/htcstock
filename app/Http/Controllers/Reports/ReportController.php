<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\FormSearches\TransactionsFormSearch;
use App\Repository\AccessoriesRepositoryInterface;
use App\Repository\TransactionsRepositoryInterface;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $transactionsRepository;
    protected $accessoriesRepository;
    public $accessories;
    public function __construct(TransactionsRepositoryInterface $transactionsRepositoryInterface, AccessoriesRepositoryInterface $accessoriesRepositoryInterface)
    {
        $this->transactionsRepository = $transactionsRepositoryInterface;
        $this->accessoriesRepository = $accessoriesRepositoryInterface;
        $this->accessories = $this->accessoriesRepository->all()->get();
    }

    public function reportTransactions(Request $request)
    {
        try {
            $formSearch = new TransactionsFormSearch();
            $transactions = $this->transactionsRepository->all();
            if ($request->all()) {
                $formSearch->access_id = $request->access_id;
                $formSearch->s_created_at = $request->s_created_at;
                $formSearch->e_created_at = $request->e_created_at;
                if (isset($request->access_id)) {
                    $transactions->where('access_id', $request->access_id);
                }
                if (isset($request->s_created_at)) {
                    $s_date = new DateTime($request->s_created_at);
                    if (isset($request->e_created_at)) {
                        $e_date = new DateTime($request->e_created_at);
                        $e_date->add(new DateInterval('P1D'));
                        $transactions->whereBetween('created_at', [$s_date->format('Y-m-d H:i:s'), $e_date->format('Y-m-d H:i:s')]);
                    } else {
                        $new_s_date = new DateTime($request->s_created_at);
                        $new_s_date->add(new DateInterval('P1D'));
                        $transactions->whereBetween('created_at', [$s_date->format('Y-m-d H:i:s'), $new_s_date->format('Y-m-d H:i:s')]);
                    }
                }
            }
            $transactions->orderBy('created_at', 'desc');
            return \view('pages.reports.transactions', \compact('formSearch'))->with([
                'transactions' => $transactions->paginate(10)->appends((array) $formSearch),
                'accessories' => $this->accessories
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function reportStocks(Request $request)
    {
        try {
            $formSearch = new TransactionsFormSearch();
            $transactions = $this->transactionsRepository->stock();
            if ($request->all()) {
                $formSearch->access_id = $request->access_id;
                if (isset($request->access_id)) {
                    $transactions->where('access_id', $request->access_id);
                }
            }
            $transactions->orderBy('quantity', 'desc');
            return \view('pages.reports.stocks', \compact('formSearch'))->with([
                'transactions' => $transactions->paginate(10)->appends((array) $formSearch),
                'accessories' => $this->accessories
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}