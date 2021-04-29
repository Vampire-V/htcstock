<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreProjectBased;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
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
    protected $paymentTermService;
    public function __construct(
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        FileService $fileService,
        ComercialListsServiceInterface $comercialListsServiceInterface,
        ComercialTermServiceInterface $comercialTermServiceInterface,
        SubtypeContractServiceInterface $subtypeContractServiceInterface,
        PaymentTermServiceInterface $paymentTermServiceInterface
    ) {
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
        $this->fileService = $fileService;
        $this->comercialListsService = $comercialListsServiceInterface;
        $this->comercialTermService = $comercialTermServiceInterface;
        $this->subtypeContractService = $subtypeContractServiceInterface;
        $this->paymentTermService = $paymentTermServiceInterface;
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
    public function store(Request $request)
    {
        return \redirect()->route('legal.contract-request.projectbasedagreement.index');
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
            $projectBased = $this->contractDescService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($projectBased->legalcontract->agreement_id);
            $paymentType = $this->paymentTypeService->dropdown($projectBased->legalcontract->agreement_id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.ContractRequestForm.ProjectBasedAgreement.edit')->with(['projectBased' => $projectBased, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
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
        $data = $request->except(['_token', '_method']);
        $attributes = [];
        $comercialAttr = [];
        $paymentAttr = [];

        $attributes['sub_type_contract_id'] = $data['subtype'];
        if (!empty($request->purchase_order)) {
            $attributes['purchase_order'] = $data['purchase_order'];
        }
        $attributes['quotation'] = $data['quotation'];
        $attributes['coparation_sheet'] = $data['coparation_sheet'];
        if (!empty($request->work_plan)) {
            $attributes['work_plan'] = $data['work_plan'];
        }
        $attributes['warranty'] = $data['warranty'];

        // comercialTerm data
        $comercialAttr['scope_of_work'] = $data['scope_of_work'];
        $comercialAttr['location'] = $data['location'];
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
            $projectBased = $this->contractDescService->find($id);
            $this->contractDescService->update($attributes, $id);

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
