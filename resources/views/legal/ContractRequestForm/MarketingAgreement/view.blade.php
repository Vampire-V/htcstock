@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/marketingagreement.css')}}">
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
            <div>Advertisement and Marketing Agreement
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
                <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
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
                    <span class="badge badge-primary">Sub-type of Contract</span>
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationSubType"><strong></strong> </label>
                            <input type="text" class="form-control-sm form-control"
                                value="{{$legalContract->legalContractDest->legalSubTypeContract->name}}" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <hr>
                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> </label>
                            <div>
                                <a href="{{url('storage/'.$legalContract->legalContractDest->purchase_order)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$legalContract->legalContractDest->purchase_order ?
                                    'view file' : ""}}</a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationQuotationFile"><strong>Quotation</strong> </label>
                            <div>
                                <a href="{{url('storage/'.$legalContract->legalContractDest->quotation)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$legalContract->legalContractDest->quotation ? 'view
                                    file' : ""}}</a>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationPurpose"><strong>Purpose</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurpose"
                                name="purpose"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->purpose : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Purpose.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPromoteProduct"><strong>Promote a product</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationPromoteProduct"
                                name="promote_a_product"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->promote_a_product : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Promote a product.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->purchase_order_no : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Purchase Order No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationQuotationNo"><strong>Quotation No</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                name="quotation_no"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Quotation No.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationDated"><strong>Dated</strong></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm->dated) ? $legalContract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Dated
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationContractPeriod"
                                name="contract_period"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm) ? $legalContract->legalContractDest->legalComercialTerm->contract_period : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Contract period.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            {{-- <label for="validationUntill"><strong>Untill</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                                value="{{isset($legalContract->legalContractDest->legalComercialTerm->untill) ? $legalContract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Untill.
                            </div> --}}
                        </div>
                    </div>
                    <hr>
                    <span class="badge badge-primary">Payment Terms</span>
                    <input type="hidden" name="payment_term_id"
                        value="{{$legalContract->legalContractDest->payment_term_id}}">
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="validationWarranty"></label>
                            <textarea class="form-control-sm form-control" name="detail_payment_term"
                                id="validationPaymentDescription" rows="3"
                                readonly>{{isset($legalContract->legalContractDest->legalPaymentTerm) ? $legalContract->legalContractDest->legalPaymentTerm->detail_payment_term : ""}}</textarea>
                            <div class="invalid-feedback">
                                Please provide a valid Payment Terms.
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
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\marketingagreement.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection
