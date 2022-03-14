<?php

namespace App\Http\Controllers\Legal\ContractRequest;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Validator;

class VendorServiceContractController extends Controller
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
        $attributes = $this->validationAndSetAttr($request);
        DB::beginTransaction();
        try {
            $contract_desc = new LegalContractDest($attributes['contract_dest']);
            $contract_desc->save();
            $attributes['comercial_terms']['contract_dest_id'] = $contract_desc->id;
            $contract_term = new LegalComercialTerm($attributes['comercial_terms']);
            $contract_term->save();
            if (!$contract_desc->legalSubtypeContract->slug = 'it-contract') {
                $payment_term = new LegalPaymentTerm($attributes['payment_terms']);
                $payment_term->save();
                $contract_desc->payment_term_id = $payment_term->id;
                $contract_desc->save();
            }

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
            // $vendorservice = $this->contractDescService->search($id);
            // $subtypeContract = $this->subtypeContractService->dropdown($vendorservice->legalcontract->agreement_id);
            // $paymentType = $this->paymentTypeService->dropdown($vendorservice->legalcontract->agreement_id);

            $contract = $this->contractRequestService->find($id);
            $paymentType = $this->paymentTypeService->dropdown($contract->agreement_id);
            $subtypeContract = $this->subtypeContractService->dropdown($contract->agreement_id);
            if ($contract->legalContractDest) {
                $row = explode("|", $contract->legalContractDest->value_of_contract);
                foreach ($row as $key => $value) {
                    $row[$key] = explode(":",$value);
                }
                $contract->legalContractDest->value_of_contract = $row;
                // $contract->legalContractDest->value_of_contract = explode(",", $contract->legalContractDest->value_of_contract);
                return \view('legal.ContractRequestForm.VendorServiceContract.edit')->with(['contract' => $contract, 'paymentType' => $paymentType, 'subtypeContract' => $subtypeContract]);
            } else {
                return \view('legal.ContractRequestForm.VendorServiceContract.create', \compact('contract', 'paymentType', 'subtypeContract'));
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
    public function update(Request $request, $id)
    {
        $attributes = $this->validationAndSetAttr($request);
        DB::beginTransaction();
        try {
            $contract_desc = $this->contractDescService->find($id);
            $contract_desc->fill($attributes['contract_dest']);
            $contract_desc->save();

            $contract_desc->legalComercialTerm->fill($attributes['comercial_terms']);
            $contract_desc->legalComercialTerm->save();

            if (!$contract_desc->legalSubtypeContract->slug = 'it-contract') {
                $contract_desc->legalPaymentTerm->fill($attributes['payment_terms']);
                $contract_desc->legalPaymentTerm->save();
            }


            if ($contract_desc->quotation !== $request->quotation) {
                Storage::delete($contract_desc->quotation);
            }
            if ($contract_desc->coparation_sheet !== $request->coparation_sheet) {
                Storage::delete($contract_desc->coparation_sheet);
            }
            if ($contract_desc->transportation_permission !== $request->transportation_permission) {
                Storage::delete($contract_desc->transportation_permission);
            }
            if ($contract_desc->vehicle_registration_certificate !== $request->vehicle_registration_certificate) {
                Storage::delete($contract_desc->vehicle_registration_certificate);
            }
            if ($contract_desc->route !== $request->route) {
                Storage::delete($contract_desc->route);
            }
            if ($contract_desc->insurance !== $request->insurance) {
                Storage::delete($contract_desc->insurance);
            }
            if ($contract_desc->driver_license !== $request->driver_license) {
                Storage::delete($contract_desc->driver_license);
            }
            if ($contract_desc->doctor_license !== $request->doctor_license) {
                Storage::delete($contract_desc->doctor_license);
            }
            if ($contract_desc->nurse_license !== $request->nurse_license) {
                Storage::delete($contract_desc->nurse_license);
            }
            if ($contract_desc->security_service_certification !== $request->security_service_certification) {
                Storage::delete($contract_desc->security_service_certification);
            }
            if ($contract_desc->security_guard_license !== $request->security_guard_license) {
                Storage::delete($contract_desc->security_guard_license);
            }
            $request->session()->flash('success',  ' has been create');
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
        }
        DB::commit();
        return \redirect()->route('legal.contract-request.show', $contract_desc->contract_id);
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
            if ($subtypeContract->slug === 'it-contract') {
                $this->validationIT($request);
                $attr = $this->setAttributesIT($request);
            }
        } catch (\Exception $e) {
            return \redirect()->back()->with('error', "Error : " . $e->getMessage());
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
            // 'untill' => 'required',

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
            // 'untill' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'monthly' => 'required',
            'payment_ot' => 'required',
            'holiday_pay' => 'required'
        ]);
    }
    private function validationCook(Request $request)
    {
        return $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            // 'untill' => 'required',
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
            // 'untill' => 'required',
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
            // 'untill' => 'required',
            'number_of_nurse' => 'required',
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
            // 'untill' => 'required',
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
            // 'untill' => 'required',
            // 'number_of_subcontractor' => 'required',
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
            'insurance' => 'required',

            'scope_of_work' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'contract_period' => 'required',
            // 'untill' => 'required',
            'route' => 'required',
            'to' => 'required',
            'dry_container_size' => 'required',
            'the_number_of_truck' => 'required',
            'working_day' => 'required',
            'working_time' => 'required',

            'price_of_service' => 'required',
        ]);
    }
    private function validationIT(Request $request)
    {
        $request->validate([
            'sub_type_contract_id' => 'required',
            'quotation' => 'required',
            'coparation_sheet' => 'required',

            'scope_of_work' => 'required',
            'location' => 'required',
            'quotation_no' => 'required',
            'dated' => 'required',
            'delivery_date' => 'required',

            'payment_type_id' => 'required',
            'value_of_contract' => 'required',

            'warranty' => 'required',
        ]);
    }

    private function setAttributesBus(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'transportation_permission', 'vehicle_registration_certificate', 'route', 'insurance', 'driver_license', 'contract_id', 'remark');
        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['transportation_permission'] = $request->transportation_permission;
        // $attributes['vehicle_registration_certificate'] = $request->vehicle_registration_certificate;
        // $attributes['route'] = $request->route;
        // $attributes['insurance'] = $request->insurance;
        // $attributes['driver_license'] = $request->driver_license;
        // $attributes['payment_term_id'] = null;

        $comercial_terms = $request->only('scope_of_work', 'location', 'quotation_no', 'dated', 'contract_period');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['location'] = $request->location;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['untill'] = $request->untill;

        $payment_terms = $request->only('monthly', 'route_change', 'payment_ot', 'holiday_pay', 'ot_driver');
        // $paymentAttr['monthly'] = $request->monthly;
        // $paymentAttr['route_change'] = $request->route_change;
        // $paymentAttr['payment_ot'] = $request->payment_ot;
        // $paymentAttr['holiday_pay'] = $request->holiday_pay;
        // $paymentAttr['ot_driver'] = $request->ot_driver;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesCleaning(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'contract_id', 'remark');
        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['payment_term_id'] = null;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'working_day', 'working_time', 'road', 'building', 'toilet', 'canteen', 'washing', 'water', 'mowing', 'general');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;
        // $comercialAttr['road'] = $request->road;
        // $comercialAttr['building'] = $request->building;
        // $comercialAttr['toilet'] = $request->toilet;
        // $comercialAttr['canteen'] = $request->canteen;
        // $comercialAttr['washing'] = $request->washing;
        // $comercialAttr['water'] = $request->water;
        // $comercialAttr['mowing'] = $request->mowing;
        // $comercialAttr['general'] = $request->general;

        $payment_terms = $request->only('monthly', 'payment_ot', 'holiday_pay');
        // $paymentAttr['monthly'] = $request->monthly;
        // $paymentAttr['payment_ot'] = $request->payment_ot;
        // $paymentAttr['holiday_pay'] = $request->holiday_pay;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesCook(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'contract_id', 'remark');

        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['comercial_term_id'] = null;
        // $attributes['payment_term_id'] = null;
        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'number_of_cook', 'working_day', 'working_time', 'comercial_ot');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['number_of_cook'] = $request->number_of_cook;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;
        // $comercialAttr['comercial_ot'] = $request->comercial_ot;

        $payment_terms = $request->only('monthly', 'other_expense');
        // $paymentAttr['monthly'] = $request->monthly;
        // $paymentAttr['other_expense'] = $request->other_expense;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesDortor(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'doctor_license', 'contract_id', 'remark');

        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['doctor_license'] = $request->doctor_license;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'number_of_doctor', 'working_day', 'working_time');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['number_of_doctor'] = $request->number_of_doctor;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;

        $payment_terms = $request->only('monthly');
        // $paymentAttr['monthly'] = $request->monthly;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesNurse(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'nurse_license', 'contract_id', 'remark');

        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['nurse_license'] = $request->coparation_sheet;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'number_of_nurse', 'working_day', 'working_time');

        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['number_of_doctor'] = $request->number_of_doctor;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;

        $payment_terms = $request->only('monthly');
        // $paymentAttr['monthly'] = $request->monthly;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesSecurity(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'security_service_certification', 'security_guard_license', 'contract_id', 'remark');

        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;
        // $attributes['security_service_certification'] = $request->security_service_certification;
        // $attributes['security_guard_license'] = $request->security_guard_license;
        // $attributes['comercial_term_id'] = null;
        // $attributes['payment_term_id'] = null;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'number_of_sercurity_guard', 'working_day', 'working_time');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['number_of_sercurity_guard'] = $request->number_of_sercurity_guard;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;

        $payment_terms = $request->only('monthly');
        // $paymentAttr['monthly'] = $request->monthly;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesSubContractor(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'contract_id', 'remark');
        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'number_of_subcontractor', 'number_of_agent', 'working_day', 'working_time');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['number_of_subcontractor'] = $request->number_of_subcontractor;
        // $comercialAttr['number_of_agent'] = $request->number_of_agent;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;

        $payment_terms = $request->only('detail_payment_term');
        // $paymentAttr['detail_payment_term'] = $request->detail_payment_term;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesTransportation(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'insurance', 'contract_id', 'remark');
        // $attributes['sub_type_contract_id'] = $request->sub_type_contract_id;
        // $attributes['quotation'] = $request->quotation;
        // $attributes['coparation_sheet'] = $request->coparation_sheet;

        $comercial_terms = $request->only('scope_of_work', 'quotation_no', 'dated', 'contract_period', 'route', 'to', 'dry_container_size', 'the_number_of_truck', 'working_day', 'working_time');
        // $comercialAttr['scope_of_work'] = $request->scope_of_work;
        // $comercialAttr['quotation_no'] = $request->quotation_no;
        // $comercialAttr['dated'] = $request->dated;
        // $comercialAttr['contract_period'] = $request->contract_period;
        // $comercialAttr['route'] = $request->route;
        // $comercialAttr['to'] = $request->to;
        // $comercialAttr['dry_container_size'] = $request->dry_container_size;
        // $comercialAttr['the_number_of_truck'] = $request->the_number_of_truck;
        // $comercialAttr['working_day'] = $request->working_day;
        // $comercialAttr['working_time'] = $request->working_time;

        $payment_terms = $request->only('price_of_service');
        // $paymentAttr['price_of_service'] = $request->price_of_service;

        return \compact('contract_dest', 'comercial_terms', 'payment_terms');
    }
    private function setAttributesIT(Request $request)
    {
        $contract_dest = $request->only('sub_type_contract_id', 'quotation', 'coparation_sheet', 'payment_type_id', 'value_of_contract', 'warranty', 'contract_id', 'remark');

        $comercial_terms = $request->only(
            'scope_of_work',
            'location',
            'quotation_no',
            'dated',
            'delivery_date'
        );

        return \compact('contract_dest', 'comercial_terms');
    }
}
