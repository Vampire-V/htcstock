@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/vendorservicecontract.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<x-legal.page-title :contract="$contract" />
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
    <x-head-status-legal :legalContract="$contract->legalContractDest->legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <span class="badge badge-primary">Sub-type Contract</span>
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationSubType"><strong></strong> </label>
                        <select id="validationSubType" class="form-control-sm form-control" name="subtype"
                            onchange="changeSubType(this)" required>
                            <option data-id="">Choose....</option>
                            @isset($subtypeContract)
                            @foreach ($subtypeContract as $item)
                            <option value="{{$item->id}}"
                                {{$item->id === $contract->legalContractDest->sub_type_contract_id ? "selected" : ""}}
                                data-id="{{$item->slug}}">
                                {{$item->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                </div>
                <hr>

                <div class="hide-contract sub-type" id="bus-contract">
                    <span class="badge badge-primary">Supporting Documents bus</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-bus">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    data-name="quotation" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationTransportationPermission"><strong>Transportation
                                        Permission</strong>
                                    <span style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->transportation_permission)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->transportation_permission ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control"
                                    id="validationTransportationPermission" data-name="transportation_permission"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->transportation_permission,9)}}"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="transportation_permission"
                                    value="{{$contract->legalContractDest->transportation_permission}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Transportation Permission.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationVehicleRegistration"><strong>Vehicle Registration</strong>
                                    <span style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->vehicle_registration_certificate)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->vehicle_registration_certificate ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control"
                                    id="validationVehicleRegistration" data-name="vehicle_registration_certificate"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->vehicle_registration_certificate,9)}}"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="vehicle_registration_certificate"
                                    value="{{$contract->legalContractDest->transportation_permission}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Vehicle Registration.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationRoute"><strong>Route</strong> <span style="color: red;">*</span>
                                    <a href="{{url('storage/'.$contract->legalContractDest->route)}}" target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->route ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationRoute"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->route,9)}}" data-name="route"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="route"
                                    value="{{$contract->legalContractDest->route}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Route.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->insurance)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->insurance ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationInsurance"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->insurance,9)}}"
                                    data-name="insurance" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="insurance"
                                    value="{{$contract->legalContractDest->insurance}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Insurance.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationDriverLicense"><strong>Driver License</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->driver_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->driver_license ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationDriverLicense"
                                    onchange="uploadFileContract(this)"
                                    data-cache="{{substr($contract->legalContractDest->driver_license,9)}}"
                                    data-name="driver_license" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="driver_license"
                                    value="{{$contract->legalContractDest->driver_license}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Driver License.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationLocation"><strong>Location</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationLocation"
                                    name="location"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->location : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Location No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" step="0.1" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationRouteChange"><strong>Route Change</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationRouteChange"
                                    name="route_change" step="0.1" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->route_change : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Route Change.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" step="0.1" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->payment_ot : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" step="0.1" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->holiday_pay : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Holiday Pay.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOTDriver"><strong>OT driver</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOTDriver"
                                    name="ot_driver" step="0.1" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->ot_driver : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cleaning-contract">
                    <span class="badge badge-primary">Supporting Documents cleaning</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-cleaning">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{asset('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>

                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
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
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-primary">Number of maid</span>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationRoad"><strong>Road</strong> </label>
                                <input type="number" class="form-control-sm form-control" id="validationRoad"
                                    name="road" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->road : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Road.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationBuilding"><strong>Building</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationBuilding"
                                    name="building" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->building : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Building.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationToilet"><strong>Toilet</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationToilet"
                                    name="toilet" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->toilet : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Toilet.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCanteen"><strong>Canteen</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationCanteen"
                                    name="canteen" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->canteen : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Canteen.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWashing"><strong>Washing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWashing"
                                    name="washing" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->washing : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Washing.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWater"><strong>Water</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWater"
                                    name="water" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->water : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Water.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationMowing"><strong>Mowing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationMowing"
                                    name="mowing" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->mowing : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Mowing.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationGeneral"><strong>General</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationGeneral"
                                    name="general" min="0" onchange="totalOfMaid()"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->general : 0}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid General.
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
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->payment_ot : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->holiday_pay : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Holiday Pay.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cook-contract">
                    <span class="badge badge-primary">Supporting Documents cook</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-cook">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfCook"><strong>Number of cook</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfCook"
                                    name="number_of_cook" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_cook : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of cook.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="comercial_ot" min="0" step="0.1"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->comercial_ot : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOtherExpense"><strong>Other Expense</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationOtherExpense"
                                    name="other_expense"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->other_expense : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Other Expense.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="doctor-contract">
                    <span class="badge badge-primary">Supporting Documents doctor</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-doctor">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDoctorLicense"><strong>Doctor License</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->doctor_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->doctor_license ? 'view file' : ""}}</a></label>

                                <input type="file" class="form-control-sm form-control" id="validationDoctorLicense"
                                    onchange="uploadFileContract(this)" data-name="doctor_license"
                                    data-cache="{{substr($contract->legalContractDest->doctor_license,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="doctor_license"
                                    value="{{$contract->legalContractDest->doctor_license}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Doctor License.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfDoctor"><strong>Number of doctor</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfDoctor"
                                    name="number_of_doctor" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_doctor : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of doctor.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="nurse-contract">
                    <span class="badge badge-primary">Supporting Documents nurse</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-nurse">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNurseLicense"><strong>Nurse License</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->nurse_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->nurse_license ? 'view file' : ""}}</a></label>

                                <input type="file" class="form-control-sm form-control" id="validationNurseLicense"
                                    onchange="uploadFileContract(this)" data-name="nurse_license"
                                    data-cache="{{substr($contract->legalContractDest->nurse_license,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="nurse_license"
                                    value="{{$contract->legalContractDest->nurse_license}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Nurse License.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfNurse"><strong>Number of Nurse</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfNurse"
                                    name="number_of_nurse" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_nurse : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of doctor.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="security-contract">
                    <span class="badge badge-primary">Supporting Documents security-guard</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-security">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityService"><strong>Security Service
                                        Certification</strong> <span style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->security_service_certification)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->security_service_certification ? 'view file' : ""}}</a></label>

                                <input type="file" class="form-control-sm form-control" id="validationSecurityService"
                                    onchange="uploadFileContract(this)" data-name="security_service_certification"
                                    data-cache="{{substr($contract->legalContractDest->security_service_certification,9)}}"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="security_service_certification"
                                    value="{{$contract->legalContractDest->security_service_certification}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Security Service Certification.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityGuardLicense"><strong>Security Guard License</strong>
                                    <span style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->security_guard_license)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->security_guard_license ? 'view file' : ""}}</a></label>

                                <input type="file" class="form-control-sm form-control"
                                    id="validationSecurityGuardLicense" onchange="uploadFileContract(this)"
                                    data-name="security_guard_license"
                                    data-cache="{{substr($contract->legalContractDest->security_guard_license,9)}}"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="security_guard_license"
                                    value="{{$contract->legalContractDest->security_guard_license}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Security Guard License.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfSercurityGuard"><strong>Number of sercurity guard</strong>
                                    <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationNumberOfSercurityGuard" name="number_of_sercurity_guard" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_sercurity_guard : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of sercurity guard.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->monthly : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="subcontractor-contract">
                    <span class="badge badge-primary">Supporting Documents sub</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-subcontractor">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfSubcontractor"><strong>Number of subcontractor</strong>
                                </label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationNumberOfSubcontractor" name="number_of_subcontractor" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_subcontractor : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of subcontractor.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfAgent"><strong>Number of agent</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfAgent"
                                    name="number_of_agent" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_agent: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time: ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationDetailPaymentTerm"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <textarea class="form-control-sm form-control" name="detail_payment_term"
                                    id="validationDetailPaymentTerm" rows="4"
                                    required>{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->detail_payment_term : ""}}</textarea>
                                <div class="invalid-feedback">
                                    Please provide a valid Detail Payment Term.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="transportation-contract">
                    <span class="badge badge-primary">Supporting Documents transportation</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.update',$contract->legalContractDest->id)}}"
                        method="POST" enctype="multipart/form-data" id="form-transportation">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="sub_type_contract_id"
                            value="{{$contract->legalContractDest->sub_type_contract_id}}"> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation"
                                    data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation"
                                    value="{{$contract->legalContractDest->quotation}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet"
                                    data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet"
                                    value="{{$contract->legalContractDest->coparation_sheet}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> <a
                                        href="{{url('storage/'.$contract->legalContractDest->insurance)}}"
                                        target="_blank"
                                        rel="noopener noreferrer">{{$contract->legalContractDest->insurance ? 'view file' : ""}}</a></label>
                                <input type="file" class="form-control-sm form-control" id="validationInsurance"
                                    onchange="uploadFileContract(this)" data-name="insurance"
                                    data-cache="{{substr($contract->legalContractDest->insurance,9)}}" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="insurance"
                                    value="{{$contract->legalContractDest->insurance}}">
                                <div class="invalid-feedback">
                                    Please provide a valid Insurance.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Comercial Terms</span>
                        <input type="hidden" name="comercial_term_id"
                            value="{{$contract->legalContractDest->comercial_term_id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationScope"><strong>Scope of Work</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationScope"
                                    name="scope_of_work"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period"
                                    value="{{$contract->legalContractDest->legalComercialTerm->contract_period}}"
                                    required>
                            </div>
                            <div class="col-md-3 mb-3">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm->untill) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
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
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->route : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Route.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationTo"><strong>To</strong> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationTo" name="to"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->to : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid To.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDryContainerSize"><strong>Dry Container Size</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationDryContainerSize" name="dry_container_size" step="0.01" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->dry_container_size : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dry Container Size.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationThenumberoftruck"><strong>The number of truck</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationThenumberoftruck" name="the_number_of_truck" min="0"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->the_number_of_truck : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid The number of truck.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWorkingDay"><strong>Working Day</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWorkingTime"><strong>Working Time</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time"
                                    value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time : ""}}"
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id"
                            value="{{$contract->legalContractDest->payment_term_id}}">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationPriceOfService"><strong>Price of service</strong><span
                                        style="color: red;">*</span></label>

                                <textarea class="form-control-sm form-control" name="price_of_service"
                                    id="validationPriceOfService" rows="4"
                                    required>{{isset($contract->legalContractDest->legalPaymentTerm) ? $contract->legalContractDest->legalPaymentTerm->price_of_service : ""}}</textarea>
                                <div class="invalid-feedback">
                                    Please provide a valid Price of service.
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button"
                            href="{{route('legal.contract-request.edit',$contract->legalContractDest->legalcontract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\vendorservicecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection