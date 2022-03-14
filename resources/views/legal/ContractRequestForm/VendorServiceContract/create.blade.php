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
    <x-head-status-legal :legalContract="$vendorservice->legalContract" />
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
                            <option value="{{$item->id}}" data-id="{{$item->slug}}">
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
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-bus">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value=""> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    onchange="uploadFileContract(this)" data-name="quotation" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationTransportationPermission"><strong>Transportation
                                        Permission</strong>
                                    <span style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control"
                                    id="validationTransportationPermission" data-name="transportation_permission"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="transportation_permission" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Transportation Permission.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationVehicleRegistration"><strong>Vehicle Registration</strong>
                                    <span style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control"
                                    id="validationVehicleRegistration" data-name="vehicle_registration_certificate"
                                    onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="vehicle_registration_certificate" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Vehicle Registration.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationRoute"><strong>Route</strong> <span style="color: red;">*</span>
                                    <a href="#" target="_blank" rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationRoute"
                                    onchange="uploadFileContract(this)" data-name="route" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="route" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Route.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationInsurance"
                                    onchange="uploadFileContract(this)" data-name="insurance" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="insurance" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Insurance.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationDriverLicense"><strong>Driver License</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationDriverLicense"
                                    onchange="uploadFileContract(this)" data-name="driver_license" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="driver_license" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Driver License.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationLocation"><strong>Location</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationLocation"
                                    name="location" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Location No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
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
                                    name="dated" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value=""
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" step="0.1" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationRouteChange"><strong>Route Change</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationRouteChange"
                                    name="route_change" step="0.1" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Route Change.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" step="0.1" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" step="0.1" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Holiday Pay.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationOTDriver"><strong>OT driver</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOTDriver"
                                    name="ot_driver" step="0.1" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cleaning-contract">
                    <span class="badge badge-primary">Supporting Documents cleaning</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-cleaning">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>

                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value=""
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
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-primary">Number of maid</span><span style="color: red;">*</span>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationRoad"><strong>Road</strong> </label>
                                <input type="number" class="form-control-sm form-control" id="validationRoad"
                                    name="road" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Road.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationBuilding"><strong>Building</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationBuilding"
                                    name="building" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Building.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationToilet"><strong>Toilet</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationToilet"
                                    name="toilet" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Toilet.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCanteen"><strong>Canteen</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationCanteen"
                                    name="canteen" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Canteen.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationWashing"><strong>Washing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWashing"
                                    name="washing" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Washing.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWater"><strong>Water</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationWater"
                                    name="water" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Water.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationMowing"><strong>Mowing</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationMowing"
                                    name="mowing" min="0" onchange="totalOfMaid()" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Mowing.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationGeneral"><strong>General</strong></label>
                                <input type="number" class="form-control-sm form-control" id="validationGeneral"
                                    name="general" min="0" onchange="totalOfMaid()" value="" required>
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
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="payment_ot" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationHolidayPay"><strong>Holiday Pay</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationHolidayPay"
                                    name="holiday_pay" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Holiday Pay.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="cook-contract">
                    <span class="badge badge-primary">Supporting Documents cook</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-cook">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value=""> <input type="hidden"
                            name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($vendorservice->legalComercialTerm->untill) ? $vendorservice->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfCook"><strong>Number of cook</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfCook"
                                    name="number_of_cook" min="0" value="" required>
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
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOT"><strong>OT</strong> <span style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationOT"
                                    name="comercial_ot" min="0" step="0.1" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid OT.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationOtherExpense"><strong>Other Expense</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationOtherExpense"
                                    name="other_expense" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Other Expense.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="doctor-contract">
                    <span class="badge badge-primary">Supporting Documents doctor</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-doctor">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDoctorLicense"><strong>Doctor License</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>

                                <input type="file" class="form-control-sm form-control" id="validationDoctorLicense"
                                    onchange="uploadFileContract(this)" data-name="doctor_license" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="doctor_license" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Doctor License.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value=""
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfDoctor"><strong>Number of doctor</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfDoctor"
                                    name="number_of_doctor" min="0" value="" required>
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
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="nurse-contract">
                    <span class="badge badge-primary">Supporting Documents nurse</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-nurse">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNurseLicense"><strong>Nurse License</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>

                                <input type="file" class="form-control-sm form-control" id="validationNurseLicense"
                                    onchange="uploadFileContract(this)" data-name="nurse_license" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="nurse_license" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Nurse License.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($vendorservice->legalComercialTerm->untill) ? $vendorservice->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfNurse"><strong>Number of Nurse</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationNumberOfNurse"
                                    name="number_of_nurse" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Number of Nurse.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="security-contract">
                    <span class="badge badge-primary">Supporting Documents security-guard</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-security">
                        @csrf

                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityService"><strong>Security Service
                                        Certification</strong> <span style="color: red;">*</span> <a href="#"
                                        target="_blank" rel="noopener noreferrer"></a></label>

                                <input type="file" class="form-control-sm form-control" id="validationSecurityService"
                                    onchange="uploadFileContract(this)" data-name="security_service_certification"
                                    required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="security_service_certification" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Security Service Certification.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationSecurityGuardLicense"><strong>Security Guard License</strong>
                                    <span style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>

                                <input type="file" class="form-control-sm form-control"
                                    id="validationSecurityGuardLicense" onchange="uploadFileContract(this)"
                                    data-name="security_guard_license" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="security_guard_license" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Security Guard License.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($vendorservice->legalComercialTerm->untill) ? $vendorservice->legalComercialTerm->untill->format('Y-m-d') : ""}}"
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
                                    value="" required>
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
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-4 mb-4">
                                <label for="validationMonthly"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="subcontractor-contract">
                    <span class="badge badge-primary">Supporting Documents sub</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-subcontractor">
                        @csrf

                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
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
                                    name="scope_of_work" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Scope of Work.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
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
                                    name="contract_period" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Contract period.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value=""
                                    required>
                                <div class="invalid-feedback">
                                    Please provide a valid Untill.
                                </div> --}}
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationNumberOfSubcontractor"><strong>Number of subcontractor</strong>
                                </label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationNumberOfSubcontractor" name="number_of_subcontractor" min="0" value=""
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
                                    name="number_of_agent" min="0" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingDay"><strong>Working Day</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingDay"
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationWorkingTime"><strong>Working Time</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationDetailPaymentTerm"><strong>Monthly</strong><span
                                        style="color: red;">*</span></label>
                                <textarea class="form-control-sm form-control" name="detail_payment_term"
                                    id="validationDetailPaymentTerm" rows="4" required></textarea>
                                <div class="invalid-feedback">
                                    Please provide a valid Detail Payment Term.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="transportation-contract">
                    <span class="badge badge-primary">Supporting Documents transportation</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-transportation">
                        @csrf

                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Coparation Sheet.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationInsurance"><strong>Insurance</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationInsurance"
                                    onchange="uploadFileContract(this)" data-name="insurance" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="insurance" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Insurance.
                                </div>
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
                                    name="scope_of_work" value="" required>
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
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Quotation No.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dated No.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                    name="contract_period" value="" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationUntill"
                                    name="untill"
                                    value="{{isset($vendorservice->legalComercialTerm->untill) ? $vendorservice->legalComercialTerm->untill->format('Y-m-d') : ""}}"
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
                                    name="route" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Route.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationTo"><strong>To</strong> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationTo" name="to"
                                    value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid To.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationDryContainerSize"><strong>Dry Container Size</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationDryContainerSize" name="dry_container_size" step="0.01" min="0"
                                    value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Dry Container Size.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationThenumberoftruck"><strong>The number of truck</strong><span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control"
                                    id="validationThenumberoftruck" name="the_number_of_truck" min="0" value=""
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
                                    name="working_day" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Day.
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="validationWorkingTime"><strong>Working Time</strong><span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationWorkingTime"
                                    name="working_time" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Working Time.
                                </div>
                            </div>
                        </div>
                        <hr>

                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="payment_term_id" value="">
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="validationPriceOfService"><strong>Price of service</strong><span
                                        style="color: red;">*</span></label>

                                <textarea class="form-control-sm form-control" name="price_of_service"
                                    id="validationPriceOfService" rows="4" required></textarea>
                                <div class="invalid-feedback">
                                    Please provide a valid Price of service.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Remark</span>
                        <div class="form-row">
                            <div class="col-md-12 mb-12">
                                <label for="Remark"><strong></strong></label>
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                        <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                    </form>
                </div>

                <div class="hide-contract sub-type" id="it-contract">
                    <span class="badge badge-primary">Information Technology</span>
                    <form class="needs-validation" novalidate
                        action="{{route('legal.contract-request.vendorservicecontract.store')}}" method="POST"
                        enctype="multipart/form-data" id="form-it">
                        @csrf
                        <input type="hidden" name="sub_type_contract_id" value="">
                        <input type="hidden" name="contract_id" value="{{$contract->id}}">
                        <div class="form-row">
                            <div class="col-md-6 mb-6">
                                <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a href="#"
                                        target="_blank" rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                    data-name="purchase_order" onchange="uploadFileContract(this)">
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="purchase_order" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid PO No.
                                </div>
                            </div>
                            <div class="col-md-6 mb-6">
                                <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                    data-name="quotation" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="quotation" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-6">
                                <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                        style="color: red;">*</span> <a href="#" target="_blank"
                                        rel="noopener noreferrer"></a></label>
                                <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                    data-name="coparation_sheet" onchange="uploadFileContract(this)" required>
                                <div class="mb-3 progress hide-contract">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <input type="hidden" type="text" name="coparation_sheet" value="">
                                <div class="invalid-feedback">
                                    Please provide a valid PO No.
                                </div>
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
                                    name="scope_of_work" value=""
                                    placeholder="e.g. purchase the products to support WAC" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationLocation"><strong>Location</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationLocation"
                                    name="location" value="" placeholder="e.g. WAC line" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                                <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                    name="purchase_order_no" value="">
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
                                    name="quotation_no" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDated"><strong>Dated</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="date" class="form-control-sm form-control" id="validationDated"
                                    name="dated" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="validationDeliveryDate"><strong>Delivery Date</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="text" class="form-control-sm form-control" id="validationDeliveryDate"
                                    name="delivery_date" value="" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Ivoice No.
                                </div>
                            </div>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Purchase list</span>
                        <div class="form-row">
                            <table class="table table-bordered" id="table-comercial-lists">
                                <thead>
                                    <tr>
                                        <th scope="col">S/N</th>
                                        <th scope="col">Description <span style="color: red;">*</span></th>
                                        <th scope="col">Quantity <span style="color: red;">*</span></th>
                                        <th scope="col">Unit Price <span style="color: red;">*</span></th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">#</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control-sm form-control" id="desc"
                                                name="description" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control-sm form-control" id="qty"
                                                name="quantity" min="0" step=0.01 required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control-sm form-control" id="unit_price"
                                                name="unit_price" min="0" step=0.01 required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control-sm form-control" id="price"
                                                name="price" min="0" step=0.01 readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control-sm form-control" id="discount"
                                                name="discount" min="0" step=0.01>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control-sm form-control" id="amount"
                                                name="amount" min="0" step=0.01 readonly>
                                        </td>
                                        <td>
                                            <a data-toggle="tooltip" title="add contract" data-placement="bottom"
                                                rel="noopener noreferrer" style="color: white;"
                                                class="btn btn-sm btn-warning" onclick="createRow()"><i
                                                    class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                        </td>
                                        <input type="hidden" class="form-control-sm form-control" id="contract_id"
                                            name="contract_id" value="{{$contract->id}}">
                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5"></th>
                                        <th class="text-right">Total: </th>
                                        <th id="total"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                        <span class="badge badge-primary">Payment Terms</span>
                        <input type="hidden" name="value_of_contract" value="">
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="validationContractType"><strong>Contract Type</strong> <span
                                        style="color: red;">*</span></label>
                                <select name="payment_type_id" id="validationContractType"
                                    class="form-control-sm form-control" onchange="changeType(this)" required>
                                    <option value="">Choose....</option>
                                    @isset($paymentType)
                                    @foreach ($paymentType as $item)
                                    <option value="{{$item->id}}">
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
                                <ul>
                                    <li class="li-none-type">
                                        <input type="number" value="" class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">%
                                        <span>of the total value of a contract within</span>
                                        <input type="number" value="" class="type-contract-input" min="0"
                                        onchange="changeContractValue(this)">
                                        <span>days from the date of</span>
                                        <input type="text" value="" class="type-contract-input" style="width: 35%"
                                        onblur="changeContractValue(this)">
                                    </li>
                                </ul>
                                <ul>
                                    <button class="btn-shadow btn btn-primary btn-sm" type="button" onclick="addInstallmentPayment()" >
                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                            <i class="pe-7s-plus"></i>
                                        </span>
                                        งวดจ่ายเงิน
                                    </button>
                                </ul>
                                {{-- <ul>
                                    <li class="li-none-type"><input type="number" value="30" class="type-contract-input"
                                            min="0" max="100" onchange="changeContractValue(this)">
                                        <span>% of
                                            the total
                                            value of a contract within 15 days from the date of signing of the
                                            contract</span>
                                    </li>
                                    <li class="li-none-type"><input type="number" value="60" class="type-contract-input"
                                            min="0" max="100" onchange="changeContractValue(this)">
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
                                    name="warranty" min="0" step="1" value="{{$contract->warranty}}"
                                    onchange="calMonthToYear(this)" required>
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
                                <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4"></textarea>
                            </div>
                        </div>
                        <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                            type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
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
