<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StorePurchaseEquipmentInstall;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
use App\Services\Legal\Interfaces\PaymentTypeServiceInterface;
use App\Services\Utils\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurchaseEquipmentInstallController extends Controller
{
    protected $contractDescService;
    protected $paymentTypeService;
    protected $fileService;
    protected $comercialListsService;
    protected $comercialTermService;
    public function __construct(
        ContractDescServiceInterface $contractDescServiceInterface,
        PaymentTypeServiceInterface $paymentTypeServiceInterface,
        FileService $fileService,
        ComercialListsServiceInterface $comercialListsServiceInterface,
        ComercialTermServiceInterface $comercialTermServiceInterface
    ) {
        $this->contractDescService = $contractDescServiceInterface;
        $this->paymentTypeService = $paymentTypeServiceInterface;
        $this->fileService = $fileService;
        $this->comercialListsService = $comercialListsServiceInterface;
        $this->comercialTermService = $comercialTermServiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \view('legal.ContractRequestForm.PurchaseEquipmentInstall.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.PurchaseEquipmentInstall.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return \redirect()->route('legal.contract-request.purchaseequipmentinstall.index');
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
            $purchaseequipmentinstall = $this->contractDescService->find($id);
            if ($purchaseequipmentinstall->value_of_contract) {
                $purchaseequipmentinstall->value_of_contract = explode(",", $purchaseequipmentinstall->value_of_contract);
            }
            $paymentType = $this->paymentTypeService->dropdown($purchaseequipmentinstall->legalcontract->agreement_id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('legal.ContractRequestForm.PurchaseEquipmentInstall.edit')->with(['purchaseequipmentinstall' => $purchaseequipmentinstall, 'paymentType' => $paymentType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePurchaseEquipmentInstall $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $attributes = [];
        $comercialAttr = [];

        $attributes['quotation'] = $data['quotation'];
        $attributes['coparation_sheet'] = $data['coparation_sheet'];
        $attributes['boq'] = $data['boq'];
        if (!empty($request->purchase_order)) {
            $attributes['purchase_order'] = $data['purchase_order'];
        }
        $attributes['payment_type_id'] = (int) $data['payment_type_id'];
        $attributes['value_of_contract'] = $data['value_of_contract'];
        $attributes['warranty'] = (int) $data['warranty'];
        // comercialTerm data
        $comercialAttr['scope_of_work'] = $data['scope_of_work'];
        $comercialAttr['location'] = $data['location'];
        $comercialAttr['purchase_order_no'] = $data['purchase_order_no'];
        $comercialAttr['quotation_no'] = $data['quotation_no'];
        $comercialAttr['dated'] = $data['dated'];
        $comercialAttr['delivery_date'] = $data['delivery_date'];

        DB::beginTransaction();
        try {
            if ($data['comercial_term_id']) {
                $this->comercialTermService->update($comercialAttr, $data['comercial_term_id']);
                $attributes['comercial_term_id'] = $data['comercial_term_id'];
            } else {
                $attributes['comercial_term_id'] = $this->comercialTermService->create($comercialAttr)->id;
            }
            $purchaseEquipmentInstall = $this->contractDescService->find($id);
            $this->contractDescService->update($attributes, $id);
            
            if ($purchaseEquipmentInstall->quotation !== $request->quotation) {
                Storage::delete($purchaseEquipmentInstall->quotation);
            }
            if ($purchaseEquipmentInstall->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($purchaseEquipmentInstall->coparation_sheet);
            }
            if ($purchaseEquipmentInstall->boq !== $request->boq) {
                Storage::delete($purchaseEquipmentInstall->boq);
            }
            if ($purchaseEquipmentInstall->purchase_order !== $request->purchase_order) {
                Storage::delete($purchaseEquipmentInstall->purchase_order);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
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
