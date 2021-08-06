@extends('layouts.app')
@section('style')
<style>
    .hide-contract {
        display: none;
    }

    .show-contract {
        display: block;
    }
</style>
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
            <div>CONTRACT REQUEST FORM
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
<div class="row" style="margin-top: 10%;">
    <x-head-status-legal :legalContract="$contract" />
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            {{-- <h5 class="card-title">CONTRACT REQUEST FORM</h5> --}}
            <form class="needs-validation" novalidate action="{{route('legal.contract-request.update',$contract->id)}}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationAcction"><strong>Action</strong> <span
                                style="color: red;">*</span></label>
                        <select name="action_id" id="validationAcction" class="form-control-sm form-control" required>
                            <option value="">Shoose....</option>
                            @isset($actions)
                            @foreach ($actions as $action)
                            <option value="{{$action->id}}" {{$contract->action_id == $action->id ? "selected" : ""}}>
                                {{$action->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationAgreements"><strong>General Agreements</strong> <span
                                style="color: red;">*</span></label>
                        <select name="agreement_id" id="validationAgreements" class="form-control-sm form-control"
                            required>
                            <option value="">Shoose....</option>
                            @isset($agreements)
                            @foreach ($agreements as $agreement)
                            <option value="{{$agreement->id}}"
                                {{$contract->agreement_id == $agreement->id ? "selected" : ""}}>{{$agreement->name}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyName"><strong>Full name (Company’s, Person’s)</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationCompanyName"
                            name="company_name" value="{{$contract->company_name}}" required>
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyCertificate"><strong>Company Certificate</strong> <span
                                style="color: red;">*</span> <a href="{{url('storage/'.$contract->company_cer)}}"
                                target="_blank"
                                rel="noopener noreferrer">{{$contract->company_cer ? 'view file' : ""}}</a></label>
                        <input type="file" class="form-control-sm form-control" id="validationCompanyCertificate"
                            onchange="uploadFileContract(this)" data-name="company_cer"
                            data-cache="{{substr($contract->company_cer,9)}}" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="company_cer" value="{{$contract->company_cer}}">
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresentative"><strong>Legal Representative</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationRepresentative"
                            name="representative" value="{{$contract->representative}}" required>
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresen"><strong>Representative Certificate</strong> <span
                                style="color: red;">*</span><a href="{{url('storage/'.$contract->representative_cer)}}"
                                target="_blank"
                                rel="noopener noreferrer">{{$contract->representative_cer ? 'view file' : ""}}</a></label>
                        <input type="file" class="form-control-sm form-control" id="validationRepresen"
                            onchange="uploadFileContract(this)" data-name="representative_cer"
                            data-cache="{{substr($contract->representative_cer,9)}}" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="representative_cer"
                            value="{{$contract->representative_cer}}">
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-12">
                        <label for="validationAddress"><strong>Address</strong> <span
                                style="color: red;">*</span></label>
                        <textarea class="form-control-sm form-control" name="address" id="validationAddress" rows="4"
                            required> {{$contract->address}}</textarea>
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary float-rigth" style="color: white !important; margin-top: 5px" type="button"
                    href="{{url()->previous()}}">Back</a>
                <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
@endsection