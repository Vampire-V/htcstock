@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/leasecontract.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Lease Contract
                <div class="page-title-subheading">This is an example dashboard created using
                    build-in elements and components.
                </div>
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
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            {{-- <h5 class="card-title">CONTRACT REQUEST FORM</h5> --}}
            <span class="badge badge-primary">Sub-type of Contract</span>
            <form class="needs-validation" novalidate
                action="{{route('legal.contract-request.leasecontract.update',$leaseContract->id)}}" method="POST"
                enctype="multipart/form-data" id="form-leasecontract">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationSubType"><strong></strong> </label>
                        <select id="validationSubType" class="form-control-sm form-control" name="subtype"
                            onchange="changeSubType(this)" required>
                            <option data-id="" value="">Shoose....</option>
                            @isset($subtypeContract)
                            @foreach ($subtypeContract as $item)
                            <option value="{{$item->id}}"
                                {{$item->id === $leaseContract->sub_type_contract_id ? "selected" : ""}}
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
                <span class="badge badge-primary">Supporting Documents</span>
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a
                                href="{{url('storage/'.$leaseContract->purchase_order)}}" target="_blank"
                                rel="noopener noreferrer">{{$leaseContract->purchase_order ? 'view file' : ""}}</a></label>
                        <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                            onchange="uploadFileContract(this)" data-name="purchase_order"
                            data-cache="{{substr($leaseContract->purchase_order,9)}}">
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="purchase_order"
                            value="{{$leaseContract->purchase_order}}">
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                style="color: red;">*</span> <a href="{{url('storage/'.$leaseContract->quotation)}}"
                                target="_blank"
                                rel="noopener noreferrer">{{$leaseContract->quotation ? 'view file' : ""}}</a></label>
                        <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                            onchange="uploadFileContract(this)" data-cache="{{substr($leaseContract->quotation,9)}}"
                            data-name="quotation" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="quotation" value="{{$leaseContract->quotation}}">
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                style="color: red;">*</span> <a
                                href="{{url('storage/'.$leaseContract->coparation_sheet)}}" target="_blank"
                                rel="noopener noreferrer">{{$leaseContract->coparation_sheet ? 'view file' : ""}}</a></label>
                        <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                            onchange="uploadFileContract(this)" data-name="coparation_sheet"
                            data-cache="{{substr($leaseContract->coparation_sheet,9)}}" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="coparation_sheet"
                            value="{{$leaseContract->coparation_sheet}}">
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                </div>

                <hr>

                <span class="badge badge-primary">Comercial Terms</span>
                <input type="hidden" name="comercial_term_id" value="{{$leaseContract->comercial_term_id}}">
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationScope"><strong>Scope of Work</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationScope"
                            name="scope_of_work"
                            value="{{isset($leaseContract->legalComercialTerm) ? $leaseContract->legalComercialTerm->scope_of_work : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Scope of Work.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationLocation"><strong>Location</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationLocation" name="location"
                            value="{{isset($leaseContract->legalComercialTerm) ? $leaseContract->legalComercialTerm->location : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Location No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                            name="purchase_order_no"
                            value="{{isset($leaseContract->legalComercialTerm) ? $leaseContract->legalComercialTerm->purchase_order_no : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Purchase Order No.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                            name="quotation_no"
                            value="{{isset($leaseContract->legalComercialTerm) ? $leaseContract->legalComercialTerm->quotation_no : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Quotation No.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationDated"><strong>Dated</strong> <span style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                            value="{{isset($leaseContract->legalComercialTerm->dated) ? $leaseContract->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Dated
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationContractPeriod"
                            name="contract_period"
                            value="{{isset($leaseContract->legalComercialTerm->contract_period) ? $leaseContract->legalComercialTerm->contract_period->format('Y-m-d') : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Contract period.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationUntill"><strong>Untill</strong> <span style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                            value="{{isset($leaseContract->legalComercialTerm->untill) ? $leaseContract->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Untill.
                        </div>
                    </div>
                </div>
                <hr>
                <span class="badge badge-primary">Purchase list</span>
                <div class="form-row">
                    <table class="table table-bordered" id="table-comercial-lists">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Amount</th>
                            </tr>
                            <tr>
                                <td> <button type="button" class="btn btn-warning" onclick="createRow()">Create</button>
                                </td>
                                <td>
                                    <input type="text" class="form-control-sm form-control" id="validationDescription"
                                        name="description" min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationUnitPrice"
                                        name="unit_price" min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationDiscount"
                                        name="discount" min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationAmount"
                                        name="amount" min="0" step=0.01>
                                </td>
                                <input type="hidden" class="form-control-sm form-control" id="validationContractDestsId"
                                    name="contract_dests_id" value="{{$leaseContract->id}}">
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th>Total</th>
                                <th id="total"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr>

                <span class="badge badge-primary">Payment Terms</span>
                <input type="hidden" name="value_of_contract" value="">
                <input type="hidden" name="payment_term_id" value="{{$leaseContract->payment_term_id}}">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationContractType"><strong>Contract Type</strong> <span
                                style="color: red;">*</span></label>
                        <select name="payment_type_id" id="validationContractType" class="form-control-sm form-control"
                            onchange="changeType(this)" required>
                            <option value="">Shoose....</option>
                            @isset($paymentType)
                            @foreach ($paymentType as $item)
                            <option value="{{$item->id}}"
                                {{$leaseContract->payment_type_id == $item->id ? "selected" : "" }}>
                                {{$item->name}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-9 mb-9 hide-contract" id="contractType1">
                        <div class="col-md-3 mb-3">
                            <label for="validationMonthly"><strong>Monthly</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                name="monthly" min="0"
                                value="{{isset($leaseContract->legalPaymentTerm) ? $leaseContract->legalPaymentTerm->monthly : 0}}"
                                >
                            <div class="invalid-feedback">
                                Please provide a valid Monthly.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mb-9 hide-contract" id="contractType2">
                        <ul>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($leaseContract->value_of_contract)?$leaseContract->value_of_contract[0]:30}}"
                                    class="type-contract-input" min="0" max="100" onchange="changeContractValue(this)">
                                <span>of the total value of a contract within 15 days from the date of
                                    signing of the contract</span>
                            </li>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($leaseContract->value_of_contract)?$leaseContract->value_of_contract[1]:30}}"
                                    class="type-contract-input" min="0" max="100" onchange="changeContractValue(this)">
                                <span>of the total value of a contract within 30 days from the date of
                                    delivered by Lessor and inspected by HTC </span></li>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($leaseContract->value_of_contract)?$leaseContract->value_of_contract[2]:40}}"
                                    class="type-contract-input" min="0" max="100" readonly
                                    onchange="changeContractValue(this)">
                                <span>of the total value of a contract within 15 days from the date of
                                    contract lapse
                                </span></li>
                        </ul>
                    </div>
                </div>

                <hr>

                <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                    href="{{route('legal.contract-request.edit',$leaseContract->legalcontract->id)}}">Back</a>
                <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\leasecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection