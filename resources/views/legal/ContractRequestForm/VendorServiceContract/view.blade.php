@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/vendorservicecontract.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<x-legal.page-title :contract="$legalContract" />
{{-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-balance-scale icon-gradient bg-happy-fisher" aria-hidden="true"></i>
            </div>
            <div>Vendor Service Contract
                <div class="page-title-subheading">THREE WEEKS PRIOR to commencement of the Contract Period.
                </div>
                <div id="imagePreview"></div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="row">
    <x-head-status-legal :legalContract="$legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                {{-- head --}}
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationAcction"><strong>Action</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control"
                            value="{{$legalContract->LegalAction->name}}" readonly>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationAgreements"><strong>General Agreements</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control"
                            value="{{$legalContract->legalAgreement->name}}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyName"><strong>Full name (Company’s, Person’s)</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->company_name}}"
                            readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationCompanyCertificate"><strong>Company Certificate</strong> <span
                                style="color: red;">*</span></label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->company_cer)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->company_cer ? 'view file' : ""}}</a>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationRepresen"><strong>Representative Certificate</strong> <span
                                style="color: red;">*</span> </label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->representative_cer)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->representative_cer ? 'view file' : ""}}</a>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresentative"><strong>Legal Representative</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control"
                            value="{{$legalContract->representative}}" readonly>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationPriority"><strong>Contract Priority</strong> <span style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->priority}}" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="validationAddress"><strong>Address</strong> <span
                                style="color: red;">*</span></label>
                        <textarea class="form-control-sm form-control" rows="4"
                            readonly>{{$legalContract->address}}</textarea>
                    </div>
                </div>
                {{-- end head --}}
                <hr>
                @isset($legalContract->legalContractDest)
                <span class="badge badge-primary">Sub-type Contract</span>
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationSubType"><strong></strong> </label>
                        <select id="validationSubType" class="form-control-sm form-control" name="subtype"
                            onchange="changeSubType(this)" readonly disabled>
                            <option data-id="">Choose....</option>
                            @isset($subtypeContract)
                            @foreach ($subtypeContract as $item)
                            <option value="{{$item->id}}" {{$item->id ===
                                $legalContract->legalContractDest->sub_type_contract_id ? "selected" : ""}}
                                data-id="{{$item->slug}}">
                                {{$item->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            {{-- Looks good! --}}
                        </div>
                    </div>
                </div>
                <hr>

                <div class="hide-contract sub-type" id="bus-contract">
                    <span class="badge badge-primary">Supporting Documents bus</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data" id="form-bus">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span></label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationTransportationPermission"><strong>Transportation
                                        Permission</strong>
                                    <span style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->transportation_permission)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->transportation_permission
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationVehicleRegistration"><strong>Vehicle Registration</strong>
                                    <span style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->vehicle_registration_certificate)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->vehicle_registration_certificate
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationRoute"><strong>Route</strong> <span style="color: red;">*</span>
                                </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->route)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->route ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->insurance)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->insurance ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationDriverLicense"><strong>Driver License</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->driver_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->driver_license ?
                                        'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationLocation"><strong>Location</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationLocation"
                                    name="location"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->location : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Location No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" step="0.1" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationRouteChange"><strong>Route Change</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationRouteChange"
                                    name="route_change" step="0.1" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->route_change : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Route Change. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" step="0.1" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->payment_ot : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid OT. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" step="0.1" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->holiday_pay : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Holiday Pay. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOTDriver"><strong>OT driver</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOTDriver"
                                    name="ot_driver" step="0.1" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->ot_driver : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Ivoice No. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cleaning-contract">
                    <span class="badge badge-primary">Supporting Documents cleaning</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-cleaning">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{asset('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-primary">Number of maid</span>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationRoad"><strong>Road</strong> </label>
                                <input type="number" class="form-control-sm form-control" id="validationRoad"
                                    name="road" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->road : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Road. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationBuilding"><strong>Building</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationBuilding"
                                    name="building" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->building : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Building. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationToilet"><strong>Toilet</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationToilet"
                                    name="toilet" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->toilet : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Toilet. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCanteen"><strong>Canteen</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationCanteen"
                                    name="canteen" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->canteen : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Canteen. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWashing"><strong>Washing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWashing"
                                    name="washing" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->washing : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Washing. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWater"><strong>Water</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWater"
                                    name="water" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->water : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Water. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationMowing"><strong>Mowing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationMowing"
                                    name="mowing" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->mowing : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Mowing. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationGeneral"><strong>General</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationGeneral"
                                    name="general" min="0" onchange="totalOfMaid()"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->general : 0}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid General. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="Total"><strong>Total</strong></label>
                                <input type="number" class="form-control-sm form-control" id="total" value="0" readonly>
                            </div>
                        </div>

                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->payment_ot : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid OT. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->holiday_pay : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Holiday Pay. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cook-contract">
                    <span class="badge badge-primary">Supporting Documents cook</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-cook">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$legalContract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfCook"><strong>Number of cook</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfCook"
                                    name="number_of_cook" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_cook : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Number of cook. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="comercial_ot" min="0" step="0.1"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->comercial_ot : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid OT. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOtherExpense"><strong>Other Expense</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationOtherExpense"
                                    name="other_expense"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->other_expense : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Other Expense. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="doctor-contract">
                    <span class="badge badge-primary">Supporting Documents doctor</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-doctor">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDoctorLicense"><strong>Doctor License</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->doctor_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->doctor_license ?
                                        'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfDoctor"><strong>Number of doctor</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfDoctor"
                                    name="number_of_doctor" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_doctor : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Number of doctor. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="nurse-contract">
                    <span class="badge badge-primary">Supporting Documents nurse</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-nurse">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNurseLicense"><strong>Nurse License</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->nurse_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->nurse_license ?
                                        'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$legalContract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfDoctor"><strong>Number of Nurse</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfDoctor"
                                    name="number_of_doctor" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_nurse : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Number of doctor. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="security-contract">
                    <span class="badge badge-primary">Supporting Documents security-guard</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-security">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span></label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityService"><strong>Security Service
                                        Certification</strong> <span style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->security_service_certification)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->security_service_certification
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityGuardLicense"><strong>Security Guard License</strong>
                                    <span style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->security_guard_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->security_guard_license
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfSercurityGuard"><strong>Number of sercurity guard</strong>
                                    <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationNumberOfSercurityGuard" name="number_of_sercurity_guard" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_sercurity_guard : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Number of sercurity guard. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Monthly. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="subcontractor-contract">
                    <span class="badge badge-primary">Supporting Documents sub</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-subcontractor">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Contract period. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfSubcontractor"><strong>Number of subcontractor</strong>
                                </label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationNumberOfSubcontractor" name="number_of_subcontractor" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_subcontractor : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Number of subcontractor. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfAgent"><strong>Number of agent</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfAgent"
                                    name="number_of_agent" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->number_of_agent: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationDetailPaymentTerm"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <textarea class="form-control-sm form-control" name="detail_payment_term"
                                    id="validationDetailPaymentTerm" rows="4" readonly>
                                        {{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->detail_payment_term : ""}}
                                    </textarea>
                                <div>
                                    {{-- Please provide a valid Detail Payment Term. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="transportation-contract">
                    <span class="badge badge-primary">Supporting Documents transportation</span>
                    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                        id="form-transportation">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span></label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->insurance)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->insurance ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$legalContract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Scope of Work. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Quotation No. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dated No. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                    readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                    readonly>
                                <div>
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationRoute"><strong>Route</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationRoute"
                                    name="route"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->route : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Route. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationTo"><strong>To</strong> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationTo" name="to"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->to : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid To. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDryContainerSize"><strong>Dry Container Size</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationDryContainerSize" name="dry_container_size" step="0.01" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->dry_container_size : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Dry Container Size. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationThenumberoftruck"><strong>The number of truck</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationThenumberoftruck" name="the_number_of_truck" min="0"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->the_number_of_truck : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid The number of truck. --}}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWorkingDay"><strong>Working Day</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_day : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Day. --}}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWorkingTime"><strong>Working Time</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->working_time : ""}}"
                                    readonly>
                                <div>
                                    {{-- Please provide a valid Working Time. --}}
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$legalContract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationPriceOfService"><strong>Price of service</strong><span
                                        style="color: red;">*</span></label>

                                <textarea class="form-control-sm form-control" name="price_of_service"
                                    id="validationPriceOfService" rows="4"
                                    readonly>{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->price_of_service : ""}}</textarea>
                                <div>
                                    {{-- Please provide a valid Price of service. --}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="it-contract">
                    <span class="badge badge-primary">Information Technology</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-it">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$legalContract->legalContractDest->sub_type_contract_id}}">
                        <div class="form-row">
                            <div class="col-md-6 mb-6">
                                <label for="validationQuotationFile"><strong>Purchase Order</strong></label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->purchase_order)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->purchase_order ?
                                        'view file' : ""}}</a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> </label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                        file' : ""}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-6">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <div>
                                    <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet
                                        ? 'view file' : ""}}</a>
                                </div>
                                {{-- <input type="hidden" type="text" name="coparation_sheet" value=""> --}}
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationLocation"><strong>Location</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationLocation"
                                    name="location"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->location : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                                <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                    name="purchase_order_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->purchase_order_no : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDeliveryDate"><strong>Delivery Date</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationDeliveryDate"
                                    name="delivery_date"
                                    value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->delivery_date : ""}}"
                                    readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Purchase list</span>
                        @isset($legalContract->legalComercialList)
                        <div class="form-row">
                            <table class="table table-bordered" id="table-comercial-lists">
                                <thead>
                                    <tr>
                                        <th scope="col">S/N</th>
                                        <th scope="col">Description <span style="color: red;">*</span></th>
                                        <th scope="col">Quantity <span style="color: red;">*</span></th>
                                        <th scope="col">Unit Price <span style="color: red;">*</span></th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Discount <span style="color: red;">*</span></th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($legalContract->legalComercialList as $key => $item)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{number_format($item->qty,2)}}</td>
                                        <td>{{number_format($item->unit_price,2)}}</td>
                                        <td>{{number_format($item->price,2)}}</td>
                                        <td>{{number_format($item->discount,2)}}</td>
                                        <td>{{number_format($item->amount, 2)}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th class="text-right">Total: </th>
                                        <th id="total">{{number_format($legalContract->legalComercialList->reduce(function ($ac,$item)
                                            {
                                            return $ac+=$item->amount;
                                            },0),2)}}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @endisset
                        <hr>
                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="value_of_contract" value="">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationContractType"><strong>Contract Type</strong> <span
                                        style="color: red;">*</span></label>
                                <select name="payment_type_id" id="validationContractType"
                                    class="form-control-sm form-control" onchange="changeType(this)" disabled>
                                    <option value="">Choose....</option>
                                    @isset($paymentType)
                                    @foreach ($paymentType as $item)
                                    <option value="{{$item->id}}" @if ($legalContract->
                                        legalContractDest->payment_type_id === $item->id)
                                        selected
                                        @endif>
                                        {{$item->name}}
                                    </option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-md-8 mb-8 hide-contract" id="contractType1">
                                @if (isset($legalContract->legalContractDest->value_of_contract))
                                <ul>
                                    @foreach ($legalContract->legalContractDest->value_of_contract as $item)
                                    <li class="li-none-type">
                                        <input type="number" value="{{$item[0] ?? 0}}" class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)" readonly>%
                                        <span>of the total value of a contract within</span>
                                        <input type="number" value="{{$item[1]  ?? 0}}" class="type-contract-input" min="0"
                                        onchange="changeContractValue(this)" readonly>
                                        <span>days from the date of</span>
                                        <input type="text" value="{{$item[2]  ?? ''}}" class="type-contract-input" style="width: 35%"
                                        onblur="changeContractValue(this)" readonly>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                                {{-- <ul>
                                    <li class="li-none-type"><input type="number" value="30" class="type-contract-input"
                                            min="0" max="100" onchange="changeContractValue(this)" readonly>
                                        <span>% of
                                            the total
                                            value of a contract within 15 days from the date of signing of the
                                            contract</span>
                                    </li>
                                    <li class="li-none-type"><input type="number" value="60" class="type-contract-input"
                                            min="0" max="100" onchange="changeContractValue(this)" readonly>
                                        <span>% of
                                            the total
                                            value of a contract within 30 days from the date of derivered by
                                            Seller</span>
                                    </li>
                                    <li class="li-none-type"><input type="number" value="10" class="type-contract-input"
                                            min="0" max="100" readonly> <span>% of
                                            the total
                                            value of a contract within 30 days from the date of inspection and approval
                                            by
                                            HTC
                                        </span>
                                    </li>
                                </ul> --}}
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Warranty</span>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWarranty"><strong>Month</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationWarranty"
                                    name="warranty" min="0" step="1"
                                    value="{{$legalContract->legalContractDest->warranty}}"
                                    onchange="calMonthToYear(this)" readonly>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWarrantyForYear"><strong>Year</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWarrantyForYear"
                                    min="0.1" step="0.1" value="" readonly>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4" readonly>{{$legalContract->legalContractDest->remark}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
                @endisset
            </div>
        </div>
        <x-legal.step-approval :contract="$legalContract" :permission="$permission" :formapprove="$form_approve" />
    </div>
</div>

{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">

        </div>
    </div>
</div>
<div class="page-title-actions fiexd-btn-botton">
    <a style="color: white" data-toggle="tooltip" title="PDF" data-placement="bottom"
        class="btn-shadow mb-3  mr-3 btn btn-dark" href="{{route('legal.pdf',$legalContract->id)}}" target="_blank"
        rel="noopener">
        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
    </a>
    <button class="mb-3 mr-3 btn btn-success" type="submit" onclick="event.preventDefault(); setVisible(true); disableScroll();
            document.getElementById('approval-contract-form').submit();" @if (!$permission) disabled
        @endif>{{$text_btn}}</button>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\vendorservicecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection
