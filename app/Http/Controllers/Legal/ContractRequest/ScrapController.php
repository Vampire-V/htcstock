<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreScrap;
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

class ScrapController extends Controller
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
        return \view('legal.ContractRequestForm.Scrap.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.Scrap.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScrap $request)
    {
        $dest = $request->only('quotation', 'coparation_sheet', 'factory_permission', 'waste_permission', 'contract_id', 'value_of_contract', 'payment_type_id');
        // comercialTerm data
        $term = $request->only('scope_of_work', 'location', 'quotation_no', 'dated', 'delivery_date');

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
            // $scrap = $this->contractDescService->search($id);

            // if ($scrap->value_of_contract) {
            //     $scrap->value_of_contract = explode(",", $scrap->value_of_contract);
            // }
            // $paymentType = $this->paymentTypeService->dropdown($scrap->legalcontract->agreement_id);

            $contract = $this->contractRequestService->find($id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $row = explode("|", $contract->legalContractDest->value_of_contract);
                foreach ($row as $key => $value) {
                    $row[$key] = explode(":",$value);
                }
                $contract->legalContractDest->value_of_contract = $row;
                // $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.Scrap.edit')->with(['contract' => $contract, 'paymentType' => $paymentType]);
            } else {
                return \view('legal.ContractRequestForm.Scrap.create', \compact('contract', 'paymentType'));
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
    public function update(StoreScrap $request, $id)
    {
        $dest = $request->only('quotation', 'coparation_sheet', 'factory_permission', 'waste_permission', 'contract_id', 'value_of_contract', 'payment_type_id');
        // comercialTerm data
        $term = $request->only('scope_of_work', 'location', 'quotation_no', 'dated', 'delivery_date');

        DB::beginTransaction();
        try {
            $scrap = $this->contractDescService->find($id);
            if ($scrap->legalContract->legalComercialList->count() < 1) {
                return \redirect()->back()->with('error', "Error : ");
            }
            if ($scrap->legalComercialTerm) {
                $this->comercialTermService->update($term, $scrap->legalComercialTerm->id);
            }

            $this->contractDescService->update($dest, $scrap->id);


            if ($scrap->quotation !== $request->quotation) {
                Storage::delete($scrap->quotation);
            }
            if ($scrap->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($scrap->coparation_sheet);
            }
            if ($scrap->factory_permission !== $request->factory_permission) {
                Storage::delete($scrap->factory_permission);
            }
            if ($scrap->waste_permission !== $request->waste_permission) {
                Storage::delete($scrap->waste_permission);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            DB::rollBack();
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.show', $scrap->contract_id);
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
