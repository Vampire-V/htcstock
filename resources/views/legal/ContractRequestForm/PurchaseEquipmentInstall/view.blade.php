@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/purchaseequipment_install.css')}}">
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
            <div>Purchase Equipment and Installation
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
                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data"
                    id="form-purchaseequipmentinstall">
                    @csrf
                    @method('PUT')
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
                            <input type="text" class="form-control-sm form-control"
                                value="{{$legalContract->company_name}}" readonly>
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
                                    rel="noopener noreferrer">{{$legalContract->representative_cer ? 'view file' :
                                    ""}}</a>
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
                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> </label>
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
                                    style="color: red;">*</span> </label>
                            <div>
                                <a href="{{url('storage/'.$legalContract->legalContractDest->coparation_sheet)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$legalContract->legalContractDest->coparation_sheet ?
                                    'view file' : ""}}</a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationBOQFile"><strong>BOQ</strong> <span style="color: red;">*</span>
                            </label>
                            <div>
                                <a href="{{url('storage/'.$legalContract->legalContractDest->boq)}}" target="_blank"
                                    rel="noopener noreferrer">{{$legalContract->legalContractDest->boq ? 'view file' :
                                    ""}}</a>
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
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
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
                                    <th id="total">{{number_format($legalContract->legalComercialList->reduce(function ($ac,$item) {
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
                                class="form-control-sm form-control" onchange="changeType(this)" readonly disabled>
                                <option value="">Choose....</option>
                                @isset($paymentType)
                                @foreach ($paymentType as $item)
                                <option value="{{$item->id}}" {{$legalContract->legalContractDest->payment_type_id ==
                                    $item->id ? "selected" : "" }}>
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
                        </div>
                    </div>
                    <hr>

                    <span class="badge badge-primary">Warranty</span>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationWarranty"><strong>Month</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="number" class="form-control-sm form-control" id="validationWarranty"
                                name="warranty" min="0" step="1" value="{{$legalContract->legalContractDest->warranty}}"
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
                    @endisset
                </form>
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
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\purchaseequipment_install.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection
