<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Legal\StoreMould;
use App\Services\Legal\Interfaces\ComercialListsServiceInterface;
use App\Services\Legal\Interfaces\ComercialTermServiceInterface;
use App\Services\Legal\Interfaces\ContractDescServiceInterface;
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
    public function store(Request $request)
    {
        return \redirect()->route('legal.contract-request.mould.index');
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
            $mould = $this->contractDescService->find($id);

            if ($mould->value_of_contract) {
                $mould->value_of_contract = explode(",", $mould->value_of_contract);
            }
            $paymentType = $this->paymentTypeService->dropdown($mould->legalcontract->agreement_id);
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        return \view('legal.ContractRequestForm.Mould.edit')->with(['mould' => $mould, 'paymentType' => $paymentType]);
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
        $data = $request->except(['_token', '_method']);
        $attributes = [];
        $comercialAttr = [];

        $attributes['purchase_order'] = $data['purchase_order'];
        $attributes['quotation'] = $data['quotation'];
        $attributes['coparation_sheet'] = $data['coparation_sheet'];
        $attributes['drawing'] = $data['drawing'];

        $attributes['payment_type_id'] = (int) $data['payment_type_id'];
        $attributes['value_of_contract'] = $data['value_of_contract'];
        $attributes['warranty'] = (int) $data['warranty'];
        // comercialTerm data
        $comercialAttr['scope_of_work'] = $data['scope_of_work'];
        $comercialAttr['to_manufacture'] = $data['to_manufacture'];
        $comercialAttr['of'] = $data['of'];
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
            $mould = $this->contractDescService->find($id);
            $this->contractDescService->update($attributes, $id);
            // \dd($attributes['drawing'],$mould->drawing,$this->contractDescService->find($id)->drawing);
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
