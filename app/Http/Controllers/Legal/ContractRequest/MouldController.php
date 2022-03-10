<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreMould;
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

class MouldController extends Controller
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
        return \view('legal.ContractRequestForm.Mould.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.Mould.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMould $request)
    {
        $dest = $request->only('quotation', 'coparation_sheet', 'purchase_order', 'drawing', 'contract_id', 'value_of_contract', 'payment_type_id', 'warranty');
        // comercialTerm data
        $term = $request->only('scope_of_work', 'to_manufacture', 'of', 'purchase_order_no', 'quotation_no', 'dated', 'delivery_date');

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
            // $mould = $this->contractDescService->search($id);

            // if ($mould->value_of_contract) {
            //     $mould->value_of_contract = explode(",", $mould->value_of_contract);
            // }
            // $paymentType = $this->paymentTypeService->dropdown($mould->legalcontract->agreement_id);

            $contract = $this->contractRequestService->find($id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $row = explode("|", $contract->legalContractDest->value_of_contract);
                foreach ($row as $key => $value) {
                    $row[$key] = explode(":",$value);
                }
                $contract->legalContractDest->value_of_contract = $row;
                // $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.Mould.edit')->with(['contract' => $contract, 'paymentType' => $paymentType]);
            } else {
                return \view('legal.ContractRequestForm.Mould.create', \compact('contract', 'paymentType'));
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
    public function update(StoreMould $request, $id)
    {
        $dest = $request->only('quotation', 'coparation_sheet', 'purchase_order', 'drawing', 'contract_id', 'value_of_contract', 'payment_type_id', 'warranty');
        // comercialTerm data
        $term = $request->only('scope_of_work', 'to_manufacture', 'of', 'purchase_order_no', 'quotation_no', 'dated', 'delivery_date');

        DB::beginTransaction();
        try {
            $mould = $this->contractDescService->find($id);
            if ($mould->legalContract->legalComercialList->count() < 1) {
                return \redirect()->back()->with('error', "Error : ");
            }
            if ($mould->legalComercialTerm) {
                $this->comercialTermService->update($term, $mould->legalComercialTerm->id);
            }

            $this->contractDescService->update($dest, $mould->id);
            if ($mould->purchase_order !== $request->purchase_order) {
                Storage::delete($mould->purchase_order);
            }
            if ($mould->quotation !== $request->quotation) {
                Storage::delete($mould->quotation);
            }
            if ($mould->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($mould->coparation_sheet);
            }
            if ($mould->drawing !== $request->drawing) {
                Storage::delete($mould->drawing);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.show', $mould->contract_id);
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
