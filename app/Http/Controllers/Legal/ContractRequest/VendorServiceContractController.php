<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
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

class VendorServiceContractController extends Controller
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
        return \view('legal.ContractRequestForm.VendorServiceContract.edit');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \view('legal.ContractRequestForm.VendorServiceContract.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return \redirect()->route('legal.contract-request.vendorservicecontract.index');
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
            $vendorservice = $this->contractDescService->find($id);
            $subtypeContract = $this->subtypeContractService->dropdown($vendorservice->legalcontract->agreement_id);
            $paymentType = $this->paymentTypeService->dropdown($vendorservice->legalcontract->agreement_id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return \view('legal.ContractRequestForm.VendorServiceContract.edit')->with([
            'vendorservice' => $vendorservice,
            'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attributes = $this->validationAndSetAttr($request);
        DB::beginTransaction();
        try {
            if ($request->comercial_term_id) {
                $this->comercialTermService->update($attributes['comercialAttr'], $request->comercial_term_id);
                $attributes['attributes']['comercial_term_id'] = $request->comercial_term_id;
            } else {
                $attributes['attributes']['comercial_term_id'] = $this->comercialTermService->create($attributes['comercialAttr'])->id;
            }
            if ($request->payment_term_id) {
                $this->paymentTermService->update($attributes['paymentAttr'], $request->payment_term_id);
                $attributes['attributes']['payment_term_id'] = $request->payment_term_id;
            } else {
                $attributes['attributes']['payment_term_id'] = $this->paymentTermService->create($attributes['paymentAttr'])->id;
            }
            $vendorServiceContract = $this->contractDescService->find($id);
            $this->contractDescService->update($attributes['attributes'], $id);

            if ($vendorServiceContract->quotation !== $request->quotation) {
                Storage::delete($vendorServiceContract->quotation);
            }
            if ($vendorServiceContract->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($vendorServiceContract->coparation_sheet);
            }
            if ($vendorServiceContract->transportation_permission !== $request->transportation_permission) {
                Storage::delete($vendorServiceContract->transportation_permission);
            }
            if ($vendorServiceContract->vehicle_registration_certificate !== $request->vehicle_registration_certificate) {
                Storage::delete($vendorServiceContract->vehicle_registration_certificate);
            }
            if ($vendorServiceContract->route !== $request->route) {
                Storage::delete($vendorServiceContract->route);
            }
            if ($vendorServiceContract->insurance !== $request->insurance) {
                Storage::delete($vendorServiceContract->insurance);
            }
            if ($vendorServiceContract->driver_license !== $request->driver_license) {
                Storage::delete($vendorServiceContract->driver_license);
            }
            if ($vendorServiceContract->doctor_license !== $request->doctor_license) {
                Storage::delete($vendorServiceContract->doctor_license);
            }
            if ($vendorServiceContract->nurse_license !== $request->nurse_license) {
                Storage::delete($vendorServiceContract->nurse_license);
            }
            if ($vendorServiceContract->security_service_certification !== $request->security_service_certification) {
                Storage::delete($vendorServiceContract->security_service_certification);
            }
            if ($vendorServiceContract->security_guard_license !== $request->security_guard_license) {
                Storage::delete($vendorServiceContract->security_guard_license);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Throwable $th) {
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

    private function validationAndSetAttr(Request $request)
    {
        $attr = [];
        try {
            $subtypeContract = $this->subtypeContractService->find($request->sub_type_contract_id);
            if ($subtypeContract->slug === 'bus-contract') {
                $this->validationBus($request);
                $attr = $this->setAttributesBus($request);
            }
            if ($subtypeContract->slug === 'cleaning-contract') {
                $this->validationCleaning($request);
                $attr = $this->setAttributesCleaning($request);
            }
            if ($subtypeContract->slug === 'cook-contract') {
                $this->validationCook($request);
                $attr = $this->setAttributesCook($request);
            }
            if ($subtypeContract->slug === 'doctor-contract') {
                $this->validationDortor($request);
                $attr = $this->setAttributesDortor($request);
            }
            if ($subtypeContract->slug === 'nurse-contract') {
                $this->validationNurse($request);
                $attr = $this->setAttributesNurse($request);
            }
            if ($subtypeContract->slug === 'security-contract') {
                $this->validationSecurity($request);
                $attr = $this->setAttributesSecurity($request);
            }
            if ($subtypeContract->slug === 'subcontractor-contract') {
                $this->validationSubContractor($request);
                $attr = $this->setAttributesSubContractor($request);
            }
            if ($subtypeContract->slug === 'transportation-contract') {
                $this->validationTransportation($request);
                $attr = $this->setAttributesTransportation($request);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $attr;
    }

    private function validationBus(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'transportation_permission' => 'required',
            'vehicle_registration_certificate' => 'required',
            'route' => 'required',
            'insurance' => 'required',
            'driver_license' => 'required',

            'scope_of_work' => 'required',
            'location' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',

            'monthly' => 'required',
            'route_change' => 'required',
            'payment_ot' => 'required',
            'holiday_pay' => 'required',
            'ot_driver' => 'required'
        ]);
    }
    private function validationCleaning(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'monthly' => 'required',
            'payment_ot' => 'required',
            'holiday_pay' => 'required'
        ]);
    }
    private function validationCook(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'number_of_cook' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',
            'comercial_ot' => 'required',

            'monthly' => 'required',
            'other_expense' => 'required'
        ]);
    }
    private function validationDortor(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'doctor_license' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'number_of_doctor' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'monthly' => 'required'
        ]);
    }
    private function validationNurse(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'nurse_license' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'number_of_doctor' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'monthly' => 'required',
        ]);
    }
    private function validationSecurity(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',
            'security_service_certification' => 'required',
            'security_guard_license' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'number_of_sercurity_guard' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'monthly' => 'required',
        ]);
    }
    private function validationSubContractor(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'number_of_subcontractor' => 'required',
            'number_of_agent' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'detail_payment_term' => 'required',
        ]);
    }
    private function validationTransportation(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            'untill' => 'required',
            'route' => 'required',
            'to' => 'required',
            'dry_container_size' => 'required',
            'the_number_of_truck' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'price_of_service' => 'required',
        ]);
    }

    private function setAttributesBus(Request $request)
    {

        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['transportation_permission'] = $request->transportation_permission;
        $attributes['vehicle_registration_certificate'] = $request->vehicle_registration_certificate;
        $attributes['route'] = $request->route;
        $attributes['insurance'] = $request->insurance;
        $attributes['driver_license'] = $request->driver_license;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['location'] = $request->location;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;

        $paymentAttr['monthly'] = $request->monthly;
        $paymentAttr['route_change'] = $request->route_change;
        $paymentAttr['payment_ot'] = $request->payment_ot;
        $paymentAttr['holiday_pay'] = $request->holiday_pay;
        $paymentAttr['ot_driver'] = $request->ot_driver;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesCleaning(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $comercialAttr['road'] = $request->road;
        $comercialAttr['building'] = $request->building;
        $comercialAttr['toilet'] = $request->toilet;
        $comercialAttr['canteen'] = $request->canteen;
        $comercialAttr['washing'] = $request->washing;
        $comercialAttr['water'] = $request->water;
        $comercialAttr['mowing'] = $request->mowing;
        $comercialAttr['general'] = $request->general;

        $paymentAttr['monthly'] = $request->monthly;
        $paymentAttr['payment_ot'] = $request->payment_ot;
        $paymentAttr['holiday_pay'] = $request->holiday_pay;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesCook(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation; 
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['number_of_cook'] = $request->number_of_cook;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;
        $comercialAttr['comercial_ot'] = $request->comercial_ot;

        $paymentAttr['monthly'] = $request->monthly;
        $paymentAttr['other_expense'] = $request->other_expense;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesDortor(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation; 
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['doctor_license'] = $request->doctor_license;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['number_of_doctor'] = $request->number_of_doctor;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $paymentAttr['monthly'] = $request->monthly;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesNurse(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['nurse_license'] = $request->coparation_sheet;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['number_of_doctor'] = $request->number_of_doctor;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $paymentAttr['monthly'] = $request->monthly;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesSecurity(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['security_service_certification'] = $request->security_service_certification;
        $attributes['security_guard_license'] = $request->security_guard_license;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['number_of_sercurity_guard'] = $request->number_of_sercurity_guard;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $paymentAttr['monthly'] = $request->monthly;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesSubContractor(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['number_of_subcontractor'] = $request->number_of_subcontractor;
        $comercialAttr['number_of_agent'] = $request->number_of_agent;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $paymentAttr['detail_payment_term'] = $request->detail_payment_term;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
    private function setAttributesTransportation(Request $request)
    {
        $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        $attributes['quotation'] = $request->quotation;
        $attributes['coparation_sheet'] = $request->coparation_sheet;
        $attributes['comercial_term_id'] = null;
        $attributes['payment_term_id'] = null;

        $comercialAttr['scope_of_work'] = $request->scope_of_work;
        $comercialAttr['quotation_no'] = $request->quotation_no;
        $comercialAttr['dated'] = $request->dated;
        $comercialAttr['contract_period'] = $request->contract_period;
        $comercialAttr['untill'] = $request->untill;
        $comercialAttr['route'] = $request->route;
        $comercialAttr['to'] = $request->to;
        $comercialAttr['dry_container_size'] = $request->dry_container_size;
        $comercialAttr['the_number_of_truck'] = $request->the_number_of_truck;
        $comercialAttr['working_day'] = $request->working_day;
        $comercialAttr['working_time'] = $request->working_time;

        $paymentAttr['price_of_service'] = $request->price_of_service;

        return ['attributes' => $attributes, 'comercialAttr' => $comercialAttr, 'paymentAttr' => $paymentAttr];
    }
}
