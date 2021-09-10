<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreLeaseContract;
use App\Models\Legal\LegalComercialTerm;
use App\Models\Legal\LegalContractDest;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Services\Legal\Interfaces\PaymentTermServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Legal\Interfaces\SubtypeContractServiceInterface;
use App\Services\Utils\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaseContractController extends Controller
{
    protected $contractDescService;
    protected $paymentTypeService;
    protected $fileService;
    protected $comercialListsService;
    protected $comercialTermService;
    protected $subtypeContractService;
    protected $paymentTermService, $contractRequestService;
    public function __construct(
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        FileService $fileService,
        ComercialListsServiceInterface $comercialListsServiceInterface,
        ComercialTermServiceInterface $comercialTermServiceInterface,
        SubtypeContractServiceInterface $subtypeContractServiceInterface,
        PaymentTermServiceInterface $paymentTermServiceInterface,
        ContractRequestServiceInterface $contractRequestService
    ) {
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
        $this->fileService = $fileService;
        $this->comercialListsService = $comercialListsServiceInterface;
        $this->comercialTermService = $comercialTermServiceInterface;
        $this->subtypeContractService = $subtypeContractServiceInterface;
        $this->paymentTermService = $paymentTermServiceInterface;
        $this->contractRequestService = $contractRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \view('legal.ContractRequestForm.LeaseContract.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.LeaseContract.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaseContract $request)
    {
        $dest = $request->only(
            'sub_type_contract_id',
            'purchase_order',
            'quotation',
            'coparation_sheet',
            'payment_type_id',
            'value_of_contract',
            'contract_id'
        );
        // comercialTerm data
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
            // $leaseContract = $this->contractDescService->search($id);

            // if ($leaseContract->value_of_contract) {
            //     $leaseContract->value_of_contract = explode(",", $leaseContract->value_of_contract);
            // }
            // $subtypeContract = $this->subtypeContractService->dropdown($leaseContract->legalcontract->agreement_id);
            // $paymentType = $this->paymentTypeService->dropdown($leaseContract->legalcontract->agreement_id);


            $contract = $this->contractRequestService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($contract->agreement_id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.LeaseContract.edit')->with(['contract' => $contract, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
            } else {
                return \view('legal.ContractRequestForm.LeaseContract.create', \compact('contract', 'paymentType', 'subtypeContract'));
            }
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        // return \view('legal.ContractRequestForm.LeaseContract.edit')->with(['leaseContract' => $leaseContract, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLeaseContract $request, $id)
    {
        $dest = $request->only(
            'sub_type_contract_id',
            'purchase_order',
            'quotation',
            'coparation_sheet',
            'payment_type_id',
            'value_of_contract',
            'contract_id'
        );
        // comercialTerm data
        $term = $request->only('scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');
        DB::beginTransaction();
        try {
            $leaseContract = $this->contractDescService->find($id);
            if ($leaseContract->legalContract->legalComercialList->count() < 1) {
                return \redirect()->back()->with('error', "Error : ");
            }
            if ($leaseContract->legalComercialTerm) {
                $this->comercialTermService->update($term, $leaseContract->legalComercialTerm->id);
            }

            $this->contractDescService->update($dest, $leaseContract->id);

            if ($leaseContract->purchase_order !== $request->purchase_order) {
                Storage::delete($leaseContract->purchase_order);
            }
            if ($leaseContract->quotation !== $request->quotation) {
                Storage::delete($leaseContract->purchase_order);
            }
            if ($leaseContract->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($leaseContract->coparation_sheet);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.show', $leaseContract->contract_id);
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
