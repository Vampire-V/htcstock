@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/purchaseequipment_install.css')}}">
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
            <div>Purchase Equipment and Installation
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
    <x-head-status-legal :legalContract="$purchaseequipmentinstall->legalContract" />
</div>

<div class="row" style="margin-top: 10%;">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.purchaseequipmentinstall.update',$purchaseequipmentinstall->id)}}"
                    method="POST" enctype="multipart/form-data" id="form-purchaseequipmentinstall">
                    @csrf
                    @method('PUT')

                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a
                                    href="{{url('storage/'.$purchaseequipmentinstall->purchase_order)}}" target="_blank"
                                    rel="noopener noreferrer">{{$purchaseequipmentinstall->purchase_order ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                data-name="purchase_order"
                                data-cache="{{substr($purchaseequipmentinstall->purchase_order,9)}}"
                                onchange="uploadFileContract(this)">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="purchase_order"
                                value="{{$purchaseequipmentinstall->purchase_order}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$purchaseequipmentinstall->quotation)}}" target="_blank"
                                    rel="noopener noreferrer">{{$purchaseequipmentinstall->quotation ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                data-cache="{{substr($purchaseequipmentinstall->quotation,9)}}" data-name="quotation"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="quotation"
                                value="{{$purchaseequipmentinstall->quotation}}">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$purchaseequipmentinstall->coparation_sheet)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$purchaseequipmentinstall->coparation_sheet ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                data-name="coparation_sheet"
                                data-cache="{{substr($purchaseequipmentinstall->coparation_sheet,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="coparation_sheet"
                                value="{{$purchaseequipmentinstall->coparation_sheet}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationBOQFile"><strong>BOQ</strong> <span style="color: red;">*</span> <a
                                    href="{{url('storage/'.$purchaseequipmentinstall->boq)}}" target="_blank"
                                    rel="noopener noreferrer">{{$purchaseequipmentinstall->boq ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationBOQFile"
                                data-name="boq" data-cache="{{substr($purchaseequipmentinstall->boq,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="boq" value="{{$purchaseequipmentinstall->boq}}">
                            <div class="invalid-feedback">
                                Please provide a valid BOQ.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <input type="hidden" name="comercial_term_id"
                        value="{{$purchaseequipmentinstall->comercial_term_id}}">
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationScope"><strong>Scope of Work</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationScope"
                                name="scope_of_work"
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm) ? $purchaseequipmentinstall->legalComercialTerm->scope_of_work : ""}}"
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
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm) ? $purchaseequipmentinstall->legalComercialTerm->location : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm) ? $purchaseequipmentinstall->legalComercialTerm->purchase_order_no : ""}}">
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
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm) ? $purchaseequipmentinstall->legalComercialTerm->quotation_no : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationDated"><strong>Dated</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm->dated) ? $purchaseequipmentinstall->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationDeliveryDate"><strong>Delivery Date</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDeliveryDate"
                                name="delivery_date"
                                value="{{isset($purchaseequipmentinstall->legalComercialTerm->delivery_date) ? $purchaseequipmentinstall->legalComercialTerm->delivery_date->format('Y-m-d') : ""}}"
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
                                    <th scope="col">#</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Amount</th>
                                </tr>
                                <tr>
                                    <td> <button type="button" class="btn btn-warning"
                                            onclick="createRow()">Create</button>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control-sm form-control"
                                            id="validationDescription" name="description" min="0" step=0.01>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control-sm form-control"
                                            id="validationUnitPrice" name="unit_price" min="0" step=0.01>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control-sm form-control"
                                            id="validationDiscount" name="discount" min="0" step=0.01>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control-sm form-control" id="validationAmount"
                                            name="amount" min="0" step=0.01>
                                    </td>
                                    <input type="hidden" class="form-control-sm form-control"
                                        id="validationContractDestsId" name="contract_dests_id"
                                        value="{{$purchaseequipmentinstall->id}}">
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
                                    {{$purchaseequipmentinstall->payment_type_id == $item->id ? "selected" : "" }}>
                                    {{$item->name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-8 mb-8" id="contractType1">
                            <ul>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($purchaseequipmentinstall->value_of_contract)?$purchaseequipmentinstall->value_of_contract[0]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 15 days from the date of signing of
                                        the
                                        contract</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($purchaseequipmentinstall->value_of_contract)?$purchaseequipmentinstall->value_of_contract[1]:60}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 30 days from the date of delivery,
                                        inspection and approval by HTC</span></li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($purchaseequipmentinstall->value_of_contract)?$purchaseequipmentinstall->value_of_contract[2]:10}}"
                                        class="type-contract-input" min="0" max="100" readonly> <span>% of the total
                                        value
                                        of a contract within 60 days from the date of the Seller has receipt the 2nd
                                        installment as a performance bond
                                    </span>
                                </li>
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
                                name="warranty" min="0" step="1" value="{{$purchaseequipmentinstall->warranty}}"
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
                        href="{{route('legal.contract-request.edit',$purchaseequipmentinstall->legalcontract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\purchaseequipment_install.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection