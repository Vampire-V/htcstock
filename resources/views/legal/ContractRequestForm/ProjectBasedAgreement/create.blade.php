@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/projectbasedagreement.css')}}">
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
            <div>Project Based Agreement
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
    <x-head-status-legal :legalContract="$projectBased->legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                {{-- <h5 class="card-title">CONTRACT REQUEST FORM</h5> --}}
                <span class="badge badge-primary">Sub-type of Contract</span>
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.projectbasedagreement.store')}}"
                    method="POST" enctype="multipart/form-data" id="form-projectbasedagreement">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationSubType"><strong></strong> </label>
                            <select id="validationSubType" class="form-control-sm form-control" name="sub_type_contract_id"
                                 required>
                                <option data-id="" value="">Choose....</option>
                                @isset($subtypeContract)
                                @foreach ($subtypeContract as $item)
                                <option value="{{$item->id}}"
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
                        <div class="col-md-3 mb-3">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                onchange="uploadFileContract(this)" data-name="purchase_order">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="purchase_order"
                                value="">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                    style="color: red;">*</span> <a href="#"
                                    target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                onchange="uploadFileContract(this)"
                                data-name="quotation" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="quotation" value="">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                    style="color: red;">*</span> <a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                onchange="uploadFileContract(this)" data-name="coparation_sheet" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="coparation_sheet"
                                value="">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationWorkPlan"><strong>Work Plan</strong> <a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" class="form-control-sm form-control" id="validationWorkPlan"
                                data-name="work_plan"
                                onchange="uploadFileContract(this)">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="work_plan" value="">
                            <div class="invalid-feedback">
                                Please provide a valid Work Plan.
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
                                name="scope_of_work"
                                value="" placeholder="e.g. WAC commercial line project"
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
                                value="" placeholder="e.g. WAC"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Location No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value=""
                                >
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
                                value=""
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Quotation No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationDated"><strong>Dated</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value=""
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Dated
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                name="contract_period"
                                value=""
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Contract period.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                                value="{{isset($projectBased->legalComercialTerm->untill) ? $projectBased->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Untill.
                            </div> --}}
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
                    <input type="hidden" name="payment_term_id" value="">
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="validationWarranty"></label>
                            <textarea class="form-control-sm form-control" name="detail_payment_term"
                                id="validationPaymentDescription" rows="3"
                                required></textarea>
                            <div class="invalid-feedback">
                                Please provide a valid Payment Terms.
                            </div>
                        </div>
                    </div>
                    <hr>
                    <span class="badge badge-primary">Warranty</span>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationWarranty"><strong>Month</strong> </label>
                            <input type="number" class="form-control-sm form-control" id="validationWarranty"
                                name="warranty" min="0" step="1" value=""
                                onchange="calMonthToYear(this)">
                            <div class="invalid-feedback">
                                Please provide a valid warranty.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationWarrantyForYear"><strong>Year</strong></label>
                            <input type="number" class="form-control-sm form-control" id="validationWarrantyForYear"
                                min="0.1" step="0.1" value="" readonly>
                        </div>
                    </div>
                    <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                        type="button"
                        href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\projectbasedagreement.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection