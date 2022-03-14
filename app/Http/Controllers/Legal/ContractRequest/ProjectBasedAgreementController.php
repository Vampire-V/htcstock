<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreProjectBased;
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
use App\Services\Utils\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectBasedAgreementController extends Controller
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
        return \view('legal.ContractRequestForm.ProjectBasedAgreement.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.ProjectBasedAgreement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectBased $request)
    {
        $dest = $request->only(
            'sub_type_contract_id',
            'quotation',
            'coparation_sheet',
            'work_plan',
            'warranty',
            'contract_id',
            'remark'
        );
        // comercialTerm data
        $term = $request->only('scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');
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
            // $projectBased = $this->contractDescService->search($id);
            // $subtypeContract = $this->subtypeContractService->dropdown($projectBased->legalcontract->agreement_id);
            // $paymentType = $this->paymentTypeService->dropdown($projectBased->legalcontract->agreement_id);

            $contract = $this->contractRequestService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($contract->agreement_id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                return \view('legal.ContractRequestForm.ProjectBasedAgreement.edit')->with(['contract' => $contract, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
            } else {
                return \view('legal.ContractRequestForm.ProjectBasedAgreement.create', \compact('contract', 'paymentType', 'subtypeContract'));
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
    public function update(StoreProjectBased $request, $id)
    {
        $dest = $request->only(
            'sub_type_contract_id',
            'quotation',
            'coparation_sheet',
            'work_plan',
            'warranty',
            'contract_id',
            'remark'
        );
        // comercialTerm data
        $term = $request->only('scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period');
        $payment = $request->only('detail_payment_term');
        DB::beginTransaction();
        try {
            $projectBased = $this->contractDescService->find($id);
            if ($projectBased->legalContract->legalComercialList->count() < 1) {
                return \redirect()->back()->with('error', "Error : ");
            }
            if ($projectBased->legalComercialTerm) {
                $this->comercialTermService->update($term, $projectBased->legalComercialTerm->id);
            }
            if ($projectBased->legalPaymentTerm) {
                $this->paymentTermService->update($payment, $projectBased->payment_term_id);
            }

            $this->contractDescService->update($dest, $projectBased->id);

            if ($projectBased->purchase_order !== $request->purchase_order) {
                Storage::delete($projectBased->purchase_order);
            }
            if ($projectBased->quotation !== $request->quotation) {
                Storage::delete($projectBased->quotation);
            }
            if ($projectBased->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($projectBased->coparation_sheet);
            }
            if ($projectBased->work_plan !== $request->work_plan) {
                Storage::delete($projectBased->work_plan);
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
