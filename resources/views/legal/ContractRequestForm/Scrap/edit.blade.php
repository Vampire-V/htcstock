@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/scrap.css')}}">
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
            <div>Scrap
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
    <x-head-status-legal :legalContract="$contract->legalContractDest->legalContract" />
</div> --}}

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form class="needs-validation" novalidate
                    action="{{route('legal.contract-request.scrap.update',$contract->legalContractDest->id)}}" method="POST"
                    enctype="multipart/form-data" id="form-scrap">
                    @csrf
                    @method('PUT')

                    <span class="badge badge-primary">Supporting Documents</span>
                    <div class="form-row">
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
                                Please provide a valid quotation.
                            </div>
                        </div>
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
                                Please provide a valid Coparation Sheet.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationFactoryPermission"><strong>Factory Permission</strong> <span
                                    style="color: red;">*</span> <a
                                    href="{{url('storage/'.$contract->legalContractDest->factory_permission)}}" target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->factory_permission ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationFactoryPermission"
                                data-name="factory_permission" data-cache="{{substr($contract->legalContractDest->factory_permission,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="factory_permission"
                                value="{{$contract->legalContractDest->factory_permission}}">
                            <div class="invalid-feedback">
                                Please provide a valid Factory Permission.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationWastePermission"><strong>Waste Permission</strong> <span
                                    style="color: red;">*</span> <a href="{{url('storage/'.$contract->legalContractDest->waste_permission)}}"
                                    target="_blank"
                                    rel="noopener noreferrer">{{$contract->legalContractDest->waste_permission ? 'view file' : ""}}</a></label>

                            <input type="file" class="form-control-sm form-control" id="validationWastePermission"
                                data-name="waste_permission" data-cache="{{substr($contract->legalContractDest->waste_permission,9)}}"
                                onchange="uploadFileContract(this)" required>
                            <div class="mb-3 progress hide-contract">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <input type="hidden" type="text" name="waste_permission"
                                value="{{$contract->legalContractDest->waste_permission}}">
                            <div class="invalid-feedback">
                                Please provide a valid Waste Permission.
                            </div>
                        </div>
                    </div>

                    <hr>

                    <span class="badge badge-primary">Comercial Terms</span>
                    <input type="hidden" name="comercial_term_id" value="{{$contract->legalContractDest->comercial_term_id}}">
                    <div class="form-row">
                        <div class="col-md-6 mb-6">
                            <label for="validationScope"><strong>Scope of Work</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationScope"
                                name="scope_of_work" placeholder="e.g. sell the scrap during Oct 1, 2564 - Mar 31, 2565"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}"
                                required>

                            <div class="invalid-feedback">
                                Please provide a valid Scope of Work.
                            </div>
                        </div>
                        <div class="col-md-6 mb-6">
                            <label for="validationLocation"><strong>Location</strong> <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control-sm form-control" id="validationLocation"
                                name="location" placeholder="e.g. RF"
                                value="{{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->location : ""}}"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid Location.
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

                    <span class="badge badge-primary">Scrap list</span>
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
                        <div class="col-md-8 mb-8 hide-contract" id="contractType1">
                            @if (isset($contract->legalContractDest->value_of_contract))
                            <ul>
                                @foreach ($contract->legalContractDest->value_of_contract as $item)
                                <li class="li-none-type">
                                    <input type="number" value="{{$item[0] ?? 0}}" class="type-contract-input" min="0" max="100"
                                    onchange="changeContractValue(this)">%
                                    <span>of the total value of a contract within</span>
                                    <input type="number" value="{{$item[1] ?? 0}}" class="type-contract-input" min="0"
                                    onchange="changeContractValue(this)">
                                    <span>days from the date of</span>
                                    <input type="text" value="{{$item[2] ?? ''}}" class="type-contract-input" style="width: 35%"
                                    onblur="changeContractValue(this)">
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <ul>
                                <button class="btn-shadow btn btn-primary btn-sm" type="button" onclick="addInstallmentPayment()" >
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="pe-7s-plus"></i>
                                    </span>
                                    งวดจ่ายเงิน
                                </button>
                            </ul>
                            {{-- <ul>
                                <li class="li-none-type">
                                    <input type="number"
                                        value="{{isset($contract->legalContractDest->value_of_contract)?$contract->legalContractDest->value_of_contract[0]:100}}"
                                        class="type-contract-input" min="0" max="100"
                                        onchange="changeContractValue(this)">
                                    <span>% of the total value of a contract from the date of delivered the
                                        scrap by HTC</span>
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                    <hr>
                    <span class="badge badge-primary">Remark</span>
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="Remark"><strong></strong></label>
                            <textarea class="form-control-sm form-control" name="remark" id="remark" rows="4">{{$contract->legalContractDest->remark}}</textarea>
                        </div>
                    </div>
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
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\scrap.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection
