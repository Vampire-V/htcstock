@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/leasecontract.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<!-- Back to top button -->
<a id="btnontop"></a>
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Lease Contract <span class="badge badge-primary">{{$legalContract->status}}</span>
                <div class="page-title-subheading">This is an example dashboard created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <a style="color: white" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark" href="{{route('legal.pdf',$legalContract->id)}}">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            </a>
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>

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
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->LegalAction->name}}" readonly>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationAgreements"><strong>General Agreements</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->legalAgreement->name}}"
                            readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyName"><strong>Full name (Company’s, Person’s)</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->company_name}}" readonly>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyCertificate"><strong>Company Certificate</strong> <span
                                style="color: red;">*</span></label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->company_cer)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->company_cer ? 'view file' : ""}}</a>
                        </div>

                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresentative"><strong>Legal Representative</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" value="{{$legalContract->representative}}" readonly>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresen"><strong>Representative Certificate</strong> <span
                                style="color: red;">*</span> </label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->representative_cer)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->representative_cer ? 'view file' : ""}}</a>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="validationAddress"><strong>Address</strong> <span
                                style="color: red;">*</span></label>
                        <textarea class="form-control-sm form-control" rows="4" readonly>{{$legalContract->address}}</textarea>
                    </div>
                </div>
                {{-- end head --}}
                <hr>
                <span class="badge badge-primary">Sub-type of Contract</span>
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationSubType"><strong></strong> </label>
                    <input type="text" class="form-control-sm form-control" value="{{$legalContract->approvalDetail->legalSubTypeContract->name}}" readonly>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                </div>
                <hr>
                <span class="badge badge-primary">Supporting Documents</span>
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> </label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->approvalDetail->purchase_order)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->approvalDetail->purchase_order ? 'view file' : ""}}</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                style="color: red;">*</span> </label>
                        <div>
                            <a href="{{url('storage/'.$legalContract->approvalDetail->quotation)}}"
                                target="_blank"
                                rel="noopener noreferrer">{{$legalContract->approvalDetail->quotation ? 'view file' : ""}}</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                style="color: red;">*</span> </label>
                        <div>
                            <a
                                href="{{url('storage/'.$legalContract->approvalDetail->coparation_sheet)}}" target="_blank"
                                rel="noopener noreferrer">{{$legalContract->approvalDetail->coparation_sheet ? 'view file' : ""}}</a>
                        </div>
                    </div>
                </div>

                <hr>

                <span class="badge badge-primary">Comercial Terms</span>
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationScope"><strong>Scope of Work</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationScope" name="scope_of_work"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm) ? $legalContract->approvalDetail->legalComercialTerm->scope_of_work : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Scope of Work.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationLocation"><strong>Location</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationLocation" name="location"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm) ? $legalContract->approvalDetail->legalComercialTerm->location : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Location No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo" name="purchase_order_no"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm) ? $legalContract->approvalDetail->legalComercialTerm->purchase_order_no : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Purchase Order No.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationQuotationNo" name="quotation_no"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm) ? $legalContract->approvalDetail->legalComercialTerm->quotation_no : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Quotation No.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationDated"><strong>Dated</strong> <span style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm->dated) ? $legalContract->approvalDetail->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Dated
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationContractPeriod"><strong>Contract period</strong> <span
                                style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationContractPeriod" name="contract_period"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm->contract_period) ? $legalContract->approvalDetail->legalComercialTerm->contract_period->format('Y-m-d') : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Contract period.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="validationUntill"><strong>Untill</strong> <span style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationUntill" name="untill"
                            value="{{isset($legalContract->approvalDetail->legalComercialTerm->untill) ? $legalContract->approvalDetail->legalComercialTerm->untill->format('Y-m-d') : ""}}"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Untill.
                        </div>
                    </div>
                </div>
                <hr>
                <span class="badge badge-primary">Purchase list</span>
                @isset($legalContract->approvalDetail->legalComercialList)
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
                        </thead>
                        <tbody>
                            @foreach ($legalContract->approvalDetail->legalComercialList as $key => $item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->unit_price}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{$item->amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3"></th>
                                <th>Total</th>
                                <th id="total">{{$legalContract->approvalDetail->legalComercialList->reduce(function ($ac,$item) {
                                    return $ac+=$item->amount;
                                },0)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endisset

                <hr>

                <span class="badge badge-primary">Payment Terms</span>
                <input type="hidden" name="value_of_contract" value="">
                <input type="hidden" name="payment_term_id" value="{{$legalContract->approvalDetail->payment_term_id}}">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationContractType"><strong>Contract Type</strong> <span
                                style="color: red;">*</span></label>
                        <select name="payment_type_id" id="validationContractType" class="form-control-sm form-control"
                            onchange="changeType(this)" readonly disabled>
                            <option value="">Shoose....</option>
                            @isset($paymentType)
                            @foreach ($paymentType as $item)
                            <option value="{{$item->id}}"
                                {{$legalContract->approvalDetail->payment_type_id == $item->id ? "selected" : "" }}>
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
                            <input type="number" class="form-control-sm form-control" id="validationMonthly" name="monthly" min="0"
                                value="{{isset($legalContract->approvalDetail->legalPaymentTerm) ? $legalContract->approvalDetail->legalPaymentTerm->monthly : 0}}"
                                readonly>
                            <div class="invalid-feedback">
                                Please provide a valid Monthly.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mb-9 hide-contract" id="contractType2">
                        <ul>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($legalContract->approvalDetail->value_of_contract)?$legalContract->approvalDetail->value_of_contract[0]:30}}"
                                    class="type-contract-input" min="0" max="100" onchange="changeContractValue(this)"
                                    readonly>
                                <span>of the total value of a contract within 15 days from the date of
                                    signing of the contract</span>
                            </li>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($legalContract->approvalDetail->value_of_contract)?$legalContract->approvalDetail->value_of_contract[1]:30}}"
                                    class="type-contract-input" min="0" max="100" onchange="changeContractValue(this)"
                                    readonly>
                                <span>of the total value of a contract within 30 days from the date of
                                    delivered by Lessor and inspected by HTC </span></li>
                            <li class="li-none-type"><input type="number"
                                    value="{{isset($legalContract->approvalDetail->value_of_contract)?$legalContract->approvalDetail->value_of_contract[2]:40}}"
                                    class="type-contract-input" min="0" max="100" readonly
                                    onchange="changeContractValue(this)">
                                <span>of the total value of a contract within 15 days from the date of
                                    contract lapse
                                </span></li>
                        </ul>
                    </div>
                </div>
                <hr>
            </form>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">step approval</h5>
            <button class="accordion active">Approval Info</button>
            <div class="panel" style="max-height: 100%">
                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Status change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($legalContract->approvalDetail)
                            @foreach ($legalContract->approvalDetail as $key => $item)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$item->user->name}} {{$item->user->email}}</td>
                                <td>{{$item->status}}</td>
                                <td>{{$item->comment}}</td>
                                <td>{{$item->created_at}}</td>
                            </tr>
                            @endforeach
                            @endisset

                        </tbody>
                    </table>
                </div>
            </div>
            <form id="approval-contract-form" action="{{route('legal.contract.approval',$legalContract->id)}}"
                method="POST">
                @csrf
                @if ($permission === 'Write')
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationStatus"><strong>Status</strong></label>
                        <select name="status" id="status" class="form-control-sm form-control" style="cursor: pointer">
                            <option value="">Choouse...</option>
                            <option value="reject">Reject</option>
                            <option value="approval">Approval</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="validationComment"><strong>Comment</strong></label>
                        <textarea class="form-control-sm form-control" name="comment" rows="5"></textarea>
                    </div>
                </div>
                @endif
            </form>
            <hr>


            <a class="btn-shadow mr-3 btn btn-dark" type="button" href="{{url()->previous()}}">Back</a>
            <button class="mr-3 btn btn-success" type="submit" onclick="event.preventDefault();
            document.getElementById('approval-contract-form').submit();">Submit</button>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\leasecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection