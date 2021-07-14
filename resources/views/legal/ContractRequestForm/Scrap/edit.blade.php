@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{asset('assets/css/legals/scrap.css')}}">
@endsection
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Scrap
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
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <form class="needs-validation" novalidate
                action="{{route('legal.contract-request.scrap.update',$scrap->id)}}" method="POST"
                enctype="multipart/form-data" id="form-scrap">
                @csrf
                @method('PUT')

                <span class="badge badge-primary">Supporting Documents</span>
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationQuotationFile"><strong>Quotation</strong> <span
                                style="color: red;">*</span> <a
                                href="{{url('storage/'.$scrap->quotation)}}" target="_blank"
                                rel="noopener noreferrer">{{$scrap->quotation ? 'view file' : ""}}</a></label>

                        <input type="file" class="form-control-sm form-control" id="validationQuotationFile" data-name="quotation"
                            data-cache="{{substr($scrap->quotation,9)}}" onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="quotation" value="{{$scrap->quotation}}">
                        <div class="invalid-feedback">
                            Please provide a valid quotation.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationCoparationFile"><strong>AEC/Coparation Sheet</strong> <span
                                style="color: red;">*</span> <a
                                href="{{url('storage/'.$scrap->coparation_sheet)}}" target="_blank"
                                rel="noopener noreferrer">{{$scrap->coparation_sheet ? 'view file' : ""}}</a></label>

                        <input type="file" class="form-control-sm form-control" id="validationCoparationFile"
                            data-name="coparation_sheet" data-cache="{{substr($scrap->coparation_sheet,9)}}"
                            onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="coparation_sheet" value="{{$scrap->coparation_sheet}}">
                        <div class="invalid-feedback">
                            Please provide a valid Coparation Sheet.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationFactoryPermission"><strong>Factory Permission</strong> <span
                                style="color: red;">*</span> <a
                                href="{{url('storage/'.$scrap->factory_permission)}}" target="_blank"
                                rel="noopener noreferrer">{{$scrap->factory_permission ? 'view file' : ""}}</a></label>

                        <input type="file" class="form-control-sm form-control" id="validationFactoryPermission"
                            data-name="factory_permission" data-cache="{{substr($scrap->factory_permission,9)}}"
                            onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="factory_permission"
                            value="{{$scrap->factory_permission}}">
                        <div class="invalid-feedback">
                            Please provide a valid Factory Permission.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationWastePermission"><strong>Waste Permission</strong> <span
                                style="color: red;">*</span> <a
                                href="{{url('storage/'.$scrap->waste_permission)}}" target="_blank"
                                rel="noopener noreferrer">{{$scrap->waste_permission ? 'view file' : ""}}</a></label>

                        <input type="file" class="form-control-sm form-control" id="validationWastePermission"
                            data-name="waste_permission" data-cache="{{substr($scrap->waste_permission,9)}}"
                            onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="waste_permission" value="{{$scrap->waste_permission}}">
                        <div class="invalid-feedback">
                            Please provide a valid Waste Permission.
                        </div>
                    </div>
                </div>

                <hr>

                <span class="badge badge-primary">Comercial Terms</span>
                <input type="hidden" name="comercial_term_id" value="{{$scrap->comercial_term_id}}">
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationScope"><strong>Scope of Work</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationScope" name="scope_of_work"
                            value="{{isset($scrap->legalComercialTerm) ? $scrap->legalComercialTerm->scope_of_work : ""}}"
                            required>

                        <div class="invalid-feedback">
                            Please provide a valid Scope of Work.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationLocation"><strong>Location</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationLocation" name="location"
                            value="{{isset($scrap->legalComercialTerm) ? $scrap->legalComercialTerm->location : ""}}"
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
                        <input type="text" class="form-control-sm form-control" id="validationQuotationNo" name="quotation_no"
                            value="{{isset($scrap->legalComercialTerm) ? $scrap->legalComercialTerm->quotation_no : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Quotation No.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationDated"><strong>Dated</strong> <span style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationDated" name="dated"
                            value="{{isset($scrap->legalComercialTerm->dated) ? $scrap->legalComercialTerm->dated->format('Y-m-d') : ""}}"
                            required>
                        <div class="invalid-feedback">
                            Please provide a valid Dated.
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="validationDeliveryDate"><strong>Delivery Date</strong> <span
                                style="color: red;">*</span></label>
                        <input type="date" class="form-control-sm form-control" id="validationDeliveryDate" name="delivery_date"
                            value="{{isset($scrap->legalComercialTerm->delivery_date) ? $scrap->legalComercialTerm->delivery_date->format('Y-m-d') : ""}}"
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
                                <th scope="col">#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Amount</th>
                            </tr>
                            <tr>
                                <td> <button type="button" class="btn btn-warning" onclick="createRow()">Create</button>
                                </td>
                                <td>
                                    <input type="text" class="form-control-sm form-control" id="validationDescription"
                                        name="description" min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationUnitPrice" name="unit_price"
                                        min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationDiscount" name="discount"
                                        min="0" step=0.01>
                                </td>
                                <td>
                                    <input type="number" class="form-control-sm form-control" id="validationAmount" name="amount"
                                        min="0" step=0.01>
                                </td>
                                <input type="hidden" class="form-control-sm form-control" id="validationContractDestsId"
                                    name="contract_dests_id" value="{{$scrap->id}}">
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
                        <select name="payment_type_id" id="validationContractType" class="form-control-sm form-control"
                            onchange="changeType(this)" required>
                            <option value="">Shoose....</option>
                            @isset($paymentType)
                            @foreach ($paymentType as $item)
                            <option value="{{$item->id}}" {{$scrap->payment_type_id == $item->id ? "selected" : "" }}>
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
                            <li class="li-none-type">
                                <input type="number"
                                    value="{{isset($scrap->value_of_contract)?$scrap->value_of_contract[0]:100}}"
                                    class="type-contract-input" min="0" max="100" onchange="changeContractValue(this)">
                                <span>% of the total value of a contract from the date of delivered the
                                    scrap by HTC</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
                <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px" type="button"
                    href="{{route('legal.contract-request.edit',$scrap->legalcontract->id)}}">Back</a>
                <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\scrap.js')}}" defer></script>
<script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}" defer></script>
@endsection