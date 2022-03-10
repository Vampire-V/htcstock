@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/leasecontract.css')}}">
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
            <div>Lease Contract
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
    <x-head-status-legal :legalContract="$leaseContract->legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                {{-- <h5 class="card-title">CONTRACT REQUEST FORM</h5> --}}
                <span class="badge badge-primary">Sub-type of Contract</span>
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.leasecontract.store')}}" method="POST"
                    enctype="multipart/form-data" id="form-leasecontract">
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
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> <a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" accept="application/pdf" class="form-control-sm form-control" id="validationPurchaseOrderFile"
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
                        <div class="col-md-4 mb-4">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                    style="color: red;">*</span> <a href="#"
                                    target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" accept="application/pdf" class="form-control-sm form-control" id="validationQuotationFile"
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
                        <div class="col-md-4 mb-4">
                            <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                    style="color: red;">*</span> <a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" accept="application/pdf" class="form-control-sm form-control" id="validationCoparationFile"
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
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-4 hide-contract">
                            <label for="InsurancePolicyFile"><strong>Insurance Policy</strong> <span style="color: red;">*</span><a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" accept="application/pdf" class="form-control-sm form-control" id="InsurancePolicyFile"
                                onchange="uploadFileContract(this)" data-name="insurance_policy" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="insurance_policy"
                                value="">
                            <div class="invalid-feedback">
                                Please provide a valid Insurance Policy.
                            </div>
                        </div>

                        <div class="col-md-4 mb-4 hide-contract">
                            <label for="CerOfOwnershipFile"><strong>Certificate Of Ownership</strong> <span style="color: red;">*</span><a
                                    href="#" target="_blank"
                                    rel="noopener noreferrer"></a></label>
                            <input type="file" accept="application/pdf" class="form-control-sm form-control" id="CerOfOwnershipFile"
                                onchange="uploadFileContract(this)" data-name="cer_of_ownership" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="cer_of_ownership"
                                value="">
                            <div class="invalid-feedback">
                                Please provide a valid Certificate Of Ownership.
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
                                value="" placeholder="test"
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
                                value=""
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Location No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong> </label>
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
                                value="{{isset($leaseContract->legalComercialTerm->untill) ? $leaseContract->legalComercialTerm->untill->format('Y-m-d') : ""}}"
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
                                            name="discount" min="0" step=0.01 >
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
                    <input type="hidden" name="payment_term_id" value="">
                    <div class="form-row" id="contract-render">
                        <div class="col-md-3 mb-3">
                            <label for="validationContractType"><strong>Contract Type</strong> <span
                                    style="color: red;">*</span></label>
                            <select name="payment_type_id" id="validationContractType"
                                class="form-control-sm form-control" onchange="changeType(this)" required>
                                <option value="">Choose....</option>
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType1" >
                            {{-- <div class="col-md-3 mb-3">
                                <label for="validationMonthly"><strong>Monthly</strong> <span
                                        style="color: red;">*</span></label>
                                <input type="number" class="form-control-sm form-control" id="validationMonthly"
                                    name="monthly" min="0"
                                    value="">
                                <div class="invalid-feedback">
                                    Please provide a valid Monthly.
                                </div>
                            </div> --}}
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType2">
                            {{-- <ul>
                                <li class="li-none-type"><input type="number"
                                        value="30"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>of the total value of a contract within 15 days from the date of
                                        signing of the contract</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="30"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>of the total value of a contract within 30 days from the date of
                                        delivered by Lessor and inspected by HTC </span></li>
                                <li class="li-none-type"><input type="number"
                                        value="40"
                                        class="type-contract-input" min="0" max="100" readonly
                                        onchange="changeContractValue(this)">
                                    <span>of the total value of a contract within 15 days from the date of
                                        contract lapse
                                    </span></li>
                            </ul> --}}
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType3" data-id="LW.,LS.">
                            <span>100 % of contract price as per monthly lease basis within 30 days of receipt of invoice.</span>
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType4" data-id="LIT.,LF.">
                            <span>Payment shall be made every third Friday of every month for bills placed to HTC every second Tuesday of the previous month.</span>
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType5" data-id="LE.">
                            <textarea name="detail_payment_term" id="detail_payment_term" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <div class="col-md-9 mb-9 hide-contract" id="contractType6" data-id="Orther">
                            <textarea name="detail_payment_term" id="detail_payment_term" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>

                    <hr>

                    <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px"
                        href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script>
    const payment_type = {!!json_encode($paymentType)!!}
    const contract_attr = {!!json_encode($contract)!!}
</script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\leasecontract.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection
