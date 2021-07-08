<?php

namespace App\Http\Controllers\IT;

use App\Enum\TransactionTypeEnum;
use Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\FormSearches\BudgetFormSearch;
use App\Http\Requests\IT\BudgetFormRequest;
use App\Services\IT\Interfaces\BudgetServiceInterface;
use App\Services\IT\Interfaces\TransactionsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BudgetController extends Controller
{
    protected $budgetService;
    protected $transactionsService;
    public function __construct(BudgetServiceInterface $budgetServiceInterface, TransactionsServiceInterface $transactionsServiceInterface)
    {
        $this->budgetService = $budgetServiceInterface;
        $this->transactionsService = $transactionsServiceInterface;
    }

    public function index(Request $request)
    {
        $query = $request->all();
        $selectedMonth = $request->month;
        $selectedYear = $request->year;
        $months = Helper::getMonth();
        $earliest_year = 2020;
        try {
            $budgets = $this->budgetService->filterForBudget($request);
            return \view('it.budgets.index',\compact('budgets','query','months','earliest_year','selectedMonth','selectedYear'));
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Budgets  $user
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return \view('it.budgets.create')->with(['months' => Helper::getMonth(), 'earliest_year' => 2020]);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Budgets  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $budget = $this->budgetService->find($id);
            $datas = $this->transactionsService->transactionType(TransactionTypeEnum::B)->whereMonth('ir_date', $budget->month)->whereYear('ir_date', $budget->year)->get();
            
            $tempAmt = 0;
            $amountTotal = 0;
            foreach ($datas as $key => $value) {
                $value->amount = $value->unit_cost * $value->qty;
                $amountTotal += $value->amount;
                $value->amt = $tempAmt += $value->amount;
            }
            return \view('it.budgets.edit')->with([
                'budget' => $budget,
                'transactions' => $datas,
                'amountTotal' => $amountTotal,
                'remainBudget' => $budget->budgets_of_month - $amountTotal
            ]);
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
    public function store(BudgetFormRequest $request)
    {
        try {
            if ($this->budgetService->hasBudget($request->month, $request->year)) {
                $request->session()->flash('error', 'มี Budget ของเดือนนี้แล้ว!');
                return \redirect()->route('it.check.budgets.index');
            }
            if ($this->budgetService->create($request->all())) {
                $request->session()->flash('success', ' Budget create success!');
            } else {
                $request->session()->flash('error', 'error budget create!');
            }
            return \redirect()->route('it.check.budgets.index');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Budgets  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if (Gate::denies('for-superadmin-admin')) {
                return \redirect()->route('logout');
            }
            $budget = $this->budgetService->find($id);
            $budget->budgets_of_month = $request->budgets_of_month;
            if ($this->budgetService->update($budget->attributesToArray(), $id)) {
                $request->session()->flash('success', ' budget has been update');
            } else {
                $request->session()->flash('error', 'error budget update!');
            }
            return \redirect()->route('it.check.budgets.index');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Budgets  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
    }
}
