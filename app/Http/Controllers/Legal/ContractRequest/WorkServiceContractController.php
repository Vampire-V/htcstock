<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreWorkServiceContract;
use App\Models\Legal\LegalComercialTerm;
use App\Models\Legal\LegalContractDest;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Utils\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkServiceContractController extends Controller
{
    protected $contractDescService;
    protected $paymentTypeService;
    protected $fileService;
    protected $comercialListsService;
    protected $comercialTermService, $contractRequestService;
    public function __construct(
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        FileService $fileService,
        ComercialListsServiceInterface $comercialListsServiceInterface,
        ComercialTermServiceInterface $comercialTermServiceInterface,
        ContractRequestServiceInterface $contractRequestService
    ) {
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
        $this->fileService = $fileService;
        $this->comercialListsService = $comercialListsServiceInterface;
        $this->comercialTermService = $comercialTermServiceInterface;
        $this->contractRequestService = $contractRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \view('legal.ContractRequestForm.WorkServiceContract.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.WorkServiceContract.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkServiceContract $request)
    {
        // comercialTerm data
        $dest = $request->except(['_token', 'scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period', 'description', 'quantity', 'unit_price', 'price', 'discount', 'amount']);
        $term = $request->only('scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');
        DB::beginTransaction();
        try {
            $contract_desc = new LegalContractDest($dest);
            $contract_desc->save();
            $term['contract_dest_id'] = $contract_desc->id;
            $contract_term = new LegalComercialTerm($term);
            $contract_term->save();
            DB::commit();
            return \redirect()->route('legal.contract-request.show', $contract_desc->contract_id);
        } catch (\Exception $e) {
            DB::rollBack();
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
            // $workservicecontract = $this->contractDescService->search($id);
            $contract = $this->contractRequestService->find($id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.WorkServiceContract.edit')->with(['contract' => $contract, 'paymentType' => $paymentType]);
            } else {
                return \view('legal.ContractRequestForm.WorkServiceContract.create', \compact('contract', 'paymentType'));
            }
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
    public function update(StoreWorkServiceContract $request, $id)
    {
        // $comercialAttr['untill'] = $data['untill'];
        $dest = $request->except(['_token', '_method', 'scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period', 'description', 'quantity', 'unit_price', 'price', 'discount', 'amount','comercial_term_id']);
        $term = $request->only('scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');

        DB::beginTransaction();
        try {
            $workServiceContract = $this->contractDescService->find($id);
            if ($workServiceContract->legalContract->legalComercialList->count() < 1) {
                return \redirect()->back()->with('error', "Error : ");
            }
            if ($workServiceContract->legalComercialTerm) {
                $this->comercialTermService->update($term, $workServiceContract->legalComercialTerm->id);
            }

            $this->contractDescService->update($dest, $workServiceContract->id);

            if ($workServiceContract->quotation !== $request->quotation) {
                Storage::delete($workServiceContract->quotation);
            }
            if ($workServiceContract->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($workServiceContract->coparation_sheet);
            }
            if ($workServiceContract->work_plan !== $request->work_plan) {
                Storage::delete($workServiceContract->work_plan);
            }
            if ($workServiceContract->purchase_order !== $request->purchase_order) {
                Storage::delete($workServiceContract->purchase_order);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.show', $workServiceContract->contract_id);
        // return \redirect()->route('legal.contract-request.index');
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
