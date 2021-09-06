@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/workservicecontract.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')

{{-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Hire of Work/Service Contract
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
</div> --}}

<div class="row">
    <x-head-status-legal :legalContract="$workservicecontract->legalContract" />
</div>

<div class="row" style="margin-top: 10%;">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.workservicecontract.update',$workservicecontract->id)}}"
                    method="POST" enctype="multipart/form-data" id="form-workservicecontract">
                    @csrf
                    @method('PUT')
                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a
                                    href="{{url('storage/'.$workservicecontract->purchase_order)}}" target="_blank"
                                    rel="noopener noreferrer">{{$workservicecontract->purchase_order ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                data-name="purchase_order"
                                data-cache="{{substr($workservicecontract->purchase_order,9)}}"
                                onchange="uploadFileContract(this)">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="purchase_order"
                                value="{{$workservicecontract->purchase_order}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$workservicecontract->quotation)}}" target="_blank"
                                    rel="noopener noreferrer">{{$workservicecontract->quotation ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                data-cache="{{substr($workservicecontract->quotation,9)}}" data-name="quotation"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="quotation"
                                value="{{$workservicecontract->quotation}}">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$workservicecontract->coparation_sheet)}}" target="_blank"
                                    rel="noopener noreferrer">{{$workservicecontract->coparation_sheet ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                data-name="coparation_sheet"
                                data-cache="{{substr($workservicecontract->coparation_sheet,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="coparation_sheet"
                                value="{{$workservicecontract->coparation_sheet}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationWorkPlan"><strong>Work Plan</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$workservicecontract->work_plan)}}" target="_blank"
                                    rel="noopener noreferrer">{{$workservicecontract->work_plan ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationWorkPlan"
                                data-name="work_plan" data-cache="{{substr($workservicecontract->work_plan,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="work_plan"
                                value="{{$workservicecontract->work_plan}}">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <input type="hidden" name="comercial_term_id" value="{{$workservicecontract->comercial_term_id}}">
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationScope"><strong>Scope of Work</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationScope"
                                name="scope_of_work"
                                value="{{isset($workservicecontract->legalComercialTerm) ? $workservicecontract->legalComercialTerm->scope_of_work : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationLocation"><strong>Location</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationLocation"
                                name="location"
                                value="{{isset($workservicecontract->legalComercialTerm) ? $workservicecontract->legalComercialTerm->location : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong> </label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value="{{isset($workservicecontract->legalComercialTerm) ? $workservicecontract->legalComercialTerm->purchase_order_no : ""}}">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                name="quotation_no"
                                value="{{isset($workservicecontract->legalComercialTerm) ? $workservicecontract->legalComercialTerm->quotation_no : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationDated"><strong>Dated</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($workservicecontract->legalComercialTerm->dated) ? $workservicecontract->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationContractPeriod"
                                name="contract_period"
                                value="{{isset($workservicecontract->legalComercialTerm->contract_period) ? $workservicecontract->legalComercialTerm->contract_period->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationUntill"><strong>Untill</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                                value="{{isset($workservicecontract->legalComercialTerm->untill) ? $workservicecontract->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
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
                                    <th scope="col">Discount <span style="color: red;">*</span></th>
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
                                            name="discount" min="0" step=0.01 required>
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
                                    <input type="hidden" class="form-control-sm form-control" id="contract_dests_id"
                                        name="contract_dests_id" value="{{$workservicecontract->id}}">

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
                                <option value="">Shoose....</option>
                                @isset($paymentType)
                                @foreach ($paymentType as $item)
                                <option value="{{$item->id}}"
                                    {{$workservicecontract->payment_type_id == $item->id ? "selected" : "" }}>
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
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[0]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of
                                        the total value of a contract within 15 days from the date of signing of the
                                        contract</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[1]:40}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of
                                        the total value of a contract within 30 days from the date of accomplishment and
                                        approval by HTC </span></li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[2]:30}}"
                                        class="type-contract-input" min="0" max="100" readonly> <span>% of
                                        the total value of a contract within 30 days from the date of inspection and
                                        approval by HTC
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8 mb-8 hide-contract" id="contractType2">
                            <ul>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[0]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of
                                        the total value of a contract within 15 days from the date of signing of the
                                        contract</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[1]:60}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of
                                        the total value of a contract within 30 days from the date of accomplishment and
                                        approval by HTC </span></li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($workservicecontract->value_of_contract)?$workservicecontract->value_of_contract[2]:10}}"
                                        class="type-contract-input" min="0" max="100" readonly> <span>% of
                                        the total value of a contract within 30 days from the date of inspection and
                                        approval by HTC
                                    </span></li>
                            </ul>
                        </div>
                    </div>
                    <hr>

                    <span class="badge badge-primary">Warranty</span>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationWarranty"><strong>Month</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="number" class="form-control-sm form-control" id="validationWarranty"
                                name="warranty" min="0" step="1" value="{{$workservicecontract->warranty}}"
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
                    <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                        type="button"
                        href="{{route('legal.contract-request.edit',$workservicecontract->legalcontract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\workservicecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}"></script>
@endsection