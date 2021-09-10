<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreMarketing;
use App\Models\Legal\LegalComercialTerm;
use App\Models\Legal\LegalContractDest;
use App\Models\Legal\LegalPaymentTerm;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\ContractRequestServiceInterface;
use App\Services\Legal\Interfaces\PaymentTermServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Legal\Interfaces\SubtypeContractServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MarketingAgreementController extends Controller
{
    protected $contractDescService;
    protected $paymentTypeService;
    protected $comercialListsService;
    protected $comercialTermService;
    protected $subtypeContractService;
    protected $paymentTermService, $contractRequestService;
    public function __construct(
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        ComercialListsServiceInterface $comercialListsServiceInterface,
        ComercialTermServiceInterface $comercialTermServiceInterface,
        SubtypeContractServiceInterface $subtypeContractServiceInterface,
        PaymentTermServiceInterface $paymentTermServiceInterface,
        ContractRequestServiceInterface $contractRequestService
    ) {
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
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
        return \view('legal.ContractRequestForm.MarketingAgreement.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.MarketingAgreement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarketing $request)
    {
        $dest = $request->only('sub_type_contract_id', 'purchase_order', 'quotation', 'payment_term_id', 'contract_id');
        // comercialTerm data
        $term = $request->only('purpose', 'promote_a_product', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');
        $payment = $request->only('detail_payment_term');
        DB::beginTransaction();
        try {
            $payment_term = new LegalPaymentTerm($payment);
            $payment_term->save();
            $dest['payment_term_id'] = $payment_term->id;
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
            $contract = $this->contractRequestService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($contract->agreement_id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.MarketingAgreement.edit')->with(['contract' => $contract, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
            } else {
                return \view('legal.ContractRequestForm.MarketingAgreement.create', \compact('contract', 'paymentType', 'subtypeContract'));
            }
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        // return \view('legal.ContractRequestForm.MarketingAgreement.edit')->with(['marketing' => $marketing, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMarketing $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $attributes = [];
        $comercialAttr = [];
        $paymentAttr = [];

        $attributes['sub_type_contract_id'] = $data['subtype'];
        if (!empty($request->purchase_order)) {
            $attributes['purchase_order'] = $data['purchase_order'];
        }
        if (!empty($request->quotation)) {
            $attributes['quotation'] = $data['quotation'];
        }

        // comercialTerm data
        $comercialAttr['purpose'] = $data['purpose'];
        $comercialAttr['promote_a_product'] = $data['promote_a_product'];
        $comercialAttr['purchase_order_no'] = $data['purchase_order_no'];
        $comercialAttr['quotation_no'] = $data['quotation_no'];
        $comercialAttr['dated'] = $data['dated'];
        $comercialAttr['contract_period'] = $data['contract_period'];
        $comercialAttr['untill'] = $data['untill'];

        $paymentAttr['detail_payment_term'] = $data['detail_payment_term'];
        DB::beginTransaction();
        try {
            if ($data['comercial_term_id']) {
                $this->comercialTermService->update($comercialAttr, $data['comercial_term_id']);
                $attributes['comercial_term_id'] = (int) $data['comercial_term_id'];
            } else {
                $attributes['comercial_term_id'] = $this->comercialTermService->create($comercialAttr)->id;
            }
            if ($data['payment_term_id']) {
                $this->paymentTermService->update($paymentAttr, $request->payment_term_id);
                $attributes['payment_term_id'] = (int) $data['payment_term_id'];
            } else {
                $attributes['payment_term_id'] = $this->paymentTermService->create($paymentAttr)->id;
            }
            $marketing = $this->contractDescService->find($id);
            $this->contractDescService->update($attributes, $id);

            if ($marketing->purchase_order !== $request->purchase_order) {
                Storage::delete($marketing->purchase_order);
            }
            if ($marketing->quotation !== $request->quotation) {
                Storage::delete($marketing->quotation);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.index');
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
