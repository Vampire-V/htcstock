@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/marketingagreement.css')}}">
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
            <div>Advertisement and Marketing Agreement
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
    <x-head-status-legal :legalContract="$marketing->legalContract" />
</div>

<div class="row" style="margin-top: 10%;">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <span class="badge badge-primary">Sub-type of Contract</span>
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.marketingagreement.update',$marketing->id)}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationSubType"><strong></strong> </label>
                            <select id="validationSubType" class="form-control-sm form-control" name="subtype"
                                required>
                                <option data-id="" value="">Choose....</option>
                                @isset($subtypeContract)
                                @foreach ($subtypeContract as $item)
                                <option value="{{$item->id}}"
                                    {{$item->id === $marketing->sub_type_contract_id ? "selected" : ""}}
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
                                    href="{{url('storage/'.$marketing->purchase_order)}}" target="_blank"
                                    rel="noopener noreferrer">{{$marketing->purchase_order ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                onchange="uploadFileContract(this)" data-name="purchase_order"
                                data-cache="{{substr($marketing->purchase_order,9)}}">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="purchase_order"
                                value="{{$marketing->purchase_order}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <a
                                    href="{{url('storage/'.$marketing->quotation)}}" target="_blank"
                                    rel="noopener noreferrer">{{$marketing->quotation ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                onchange="uploadFileContract(this)" data-cache="{{substr($marketing->quotation,9)}}"
                                data-name="quotation">
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="quotation" value="{{$marketing->quotation}}">
                            <div class="invalid-feedback">
                                Please provide a valid Ivoice No.
                            </div>
                        </div>
                    </div>
                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <input type="hidden" name="comercial_term_id" value="{{$marketing->comercial_term_id}}">
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationPurpose"><strong>Purpose</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurpose"
                                name="purpose"
                                value="{{isset($marketing->legalComercialTerm) ? $marketing->legalComercialTerm->purpose : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Purpose.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPromoteProduct"><strong>Promote a product</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationPromoteProduct"
                                name="promote_a_product"
                                value="{{isset($marketing->legalComercialTerm) ? $marketing->legalComercialTerm->promote_a_product : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Promote a product.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value="{{isset($marketing->legalComercialTerm) ? $marketing->legalComercialTerm->purchase_order_no : ""}}"
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
                                value="{{isset($marketing->legalComercialTerm) ? $marketing->legalComercialTerm->quotation_no : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Quotation No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationDated"><strong>Dated</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($marketing->legalComercialTerm->dated) ? $marketing->legalComercialTerm->dated->format('Y-m-d') : ""}}"
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
                                value="{{isset($marketing->legalComercialTerm->contract_period) ? $marketing->legalComercialTerm->contract_period->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Contract period.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationUntill"><strong>Untill</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                                value="{{isset($marketing->legalComercialTerm->untill) ? $marketing->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Untill.
                            </div>
                        </div>
                    </div>
                    <hr>
                    <span class="badge badge-primary">Payment Terms</span>
                    <input type="hidden" name="payment_term_id" value="{{$marketing->payment_term_id}}">
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="validationWarranty"></label>
                            <textarea class="form-control-sm form-control" name="detail_payment_term"
                                id="validationPaymentDescription" rows="3"
                                required>{{isset($marketing->legalPaymentTerm) ? $marketing->legalPaymentTerm->detail_payment_term : ""}}</textarea>
                            <div class="invalid-feedback">
                                Please provide a valid Payment Terms.
                            </div>
                        </div>
                    </div>
                    <hr>
                    <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                        type="button"
                        href="{{route('legal.contract-request.edit',$marketing->legalcontract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\marketingagreement.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection