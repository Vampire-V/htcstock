@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/mould.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-balance-scale icon-gradient bg-happy-fisher" aria-hidden="true"></i>
            </div>
            <div>Mould
                {{-- <div class="page-title-subheading">THREE WEEKS PRIOR to commencement of the Contract Period.
                </div> --}}
                <div id="imagePreview"></div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
    <x-head-status-legal :legalContract="$contract->legalContractDest->legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.mould.update',$contract->legalContractDest->id)}}" method="POST"
                    enctype="multipart/form-data" id="form-mould">
                    @csrf
                    @method('PUT')
                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationPurchaseOrderFile"><strong>Purchase Order</strong> 
                                <a href="{{url('storage/'.$contract->legalContractDest->purchase_order)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->purchase_order ? 'view file' : ""}}</a></label>
                            <input type="file" class="form-control-sm form-control" id="validationPurchaseOrderFile"
                                data-name="purchase_order" data-cache="{{substr($contract->legalContractDest->purchase_order,9)}}"
                                onchange="uploadFileContract(this)" >
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="purchase_order" value="{{$contract->legalContractDest->purchase_order}}">
                            <div class="invalid-feedback">
                                Please provide a valid PO No.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                    style="color: red;">*</span> <a href="{{url('storage/'.$contract->legalContractDest->quotation)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->quotation ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationQuotationFile"
                                data-name="quotation" data-cache="{{substr($contract->legalContractDest->quotation,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="quotation" value="{{$contract->legalContractDest->quotation}}">
                            <div class="invalid-feedback">
                                Please provide a valid Quotation File.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                    style="color: red;">*</span> <a href="{{url('storage/'.$contract->legalContractDest->coparation_sheet)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->coparation_sheet ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                                data-name="coparation_sheet" data-cache="{{substr($contract->legalContractDest->coparation_sheet,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="coparation_sheet"
                                value="{{$contract->legalContractDest->coparation_sheet}}">
                            <div class="invalid-feedback">
                                Please provide a valid Coparation File.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationDrawingFile"><strong>Drawing</strong> <span
                                    style="color: red;">*</span>
                                <a href="{{url('storage/'.$contract->legalContractDest->drawing)}}" target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->drawing ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationDrawingFile"
                                data-name="drawing" data-cache="{{substr($contract->legalContractDest->drawing,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="drawing" value="{{$contract->legalContractDest->drawing}}">
                            <div class="invalid-feedback">
                                Please provide a valid Drawing File.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <input type="hidden" name="comercial_term_id" value="{{$contract->legalContractDest->comercial_term_id}}">
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationScope"><strong>Scope of Work</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationScope"
                                name="scope_of_work" placeholder="e.g. produce the mould"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Scope of Work.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationToManufacture"><strong>To Manufacture</strong> </label>
                            <input type="text" class="form-control-sm form-control" id="validationToManufacture"
                                name="to_manufacture" placeholder="e.g. UPPER HINGE FIXING PLATE"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->to_manufacture : ""}}">
                            <div class="invalid-feedback">
                                Please provide a valid To Manufacture.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationOf"><strong>Of</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationOf" name="of" placeholder="e.g. T-Door 456"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->of : ""}}">
                            <div class="invalid-feedback">
                                Please provide a valid Of.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationPurchaseOrderNo"><strong>Purchase Order No.</strong></label>
                            <input type="text" class="form-control-sm form-control" id="validationPurchaseOrderNo"
                                name="purchase_order_no"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->purchase_order_no : ""}}">
                            <div class="invalid-feedback">
                                Please provide a valid Purchase Order No.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 mb-4">
                            <label for="validationQuotationNo"><strong>Quotation No</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationQuotationNo"
                                name="quotation_no"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Quotation No.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationDated"><strong>Dated</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                                value="{{isset($contract->legalContractDest->legalComercialTerm->dated) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Dated.
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="validationDeliveryDate"><strong>Delivery Date</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationDeliveryDate"
                                name="delivery_date"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->delivery_date : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Delivery Date.
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
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="validationContractType"><strong>Contract Type</strong> <span
                                    style="color: red;">*</span></label>
                            <select name="payment_type_id" id="validationContractType"
                                class="form-control-sm form-control" onchange="changeType(this)" required>
                                <option value="">Choose....</option>
                                @isset($paymentType)
                                @foreach ($paymentType as $item)
                                <option value="{{$item->id}}"
                                    {{$contract->legalContractDest->payment_type_id == $item->id ? "selected" : "" }}>
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
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[0]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 15 days from the date of signing of
                                        the
                                        contract</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[1]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 30 days from the date to be
                                        delivered of
                                        sample products.</span></li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[2]:30}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 60 days from the date to be
                                        delivered of
                                        mould.
                                    </span>
                                </li>
                                <li class="li-none-type">
                                    <input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract) && count($contract->legalContractDest->value_of_contract)>3?$contract->legalContractDest->value_of_contract[3]:10}}"
                                        class="type-contract-input" min="0" max="100" readonly>
                                    <span>% of the total value
                                        of a contract within 30 days after 1-2 years of warranty lapse.
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8 mb-8" id="contractType2">
                            <ul>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[0]:40}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 15 days from the date of
                                        signing</span>
                                </li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[1]:50}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract within 30 days from the date to be
                                        delivered</span></li>
                                <li class="li-none-type"><input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[2]:10}}"
                                        class="type-contract-input" min="0" max="100" readonly> <span>% of the total
                                        value
                                        of a contract within 30 days from the date to be delivered
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
                                name="warranty" min="0" step="1" value="{{$contract->legalContractDest->warranty}}"
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
                        type="button" href="{{route('legal.contract-request.edit',$contract->id)}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\mould.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection