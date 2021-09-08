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

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-balance-scale icon-gradient bg-happy-fisher" aria-hidden="true"></i>
            </div>
            <div>CONTRACT REQUEST FORM
                <div class="page-title-subheading">THREE WEEKS PRIOR to commencement of the Contract Period.
                </div>
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

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            {{-- <h5 class="card-title">CONTRACT REQUEST FORM</h5> --}}
            <form class="needs-validation" novalidate action="{{route('legal.contract-request.store')}}" method="POST"
                enctype="multipart/form-data" id="contract-request">
                @csrf
                <div class="form-row">
                    <div class="col-md-6 mb-6">
                        <label for="validationAcction"><strong>Action</strong> <span
                                style="color: red;">*</span></label>
                        <select name="action_id" id="validationAcction" class="form-control-sm form-control" required>
                            <option value="">Choose....</option>
                            @isset($actions)
                            @foreach ($actions as $action)
                            <option value="{{$action->id}}">{{$action->name}}</option>
                            @endforeach
                            @endisset
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationAgreements"><strong>Type</strong> <span
                                style="color: red;">*</span></label>
                        <select name="agreement_id" id="validationAgreements" class="form-control-sm form-control"
                            required>
                            <option value="">Choose....</option>
                            @isset($agreements)
                            @foreach ($agreements as $agreement)
                            <option value="{{$agreement->id}}">{{$agreement->name}}</option>
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
                        <label for="validationCompanyName"><strong>Contracting Party</strong> <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control-sm form-control" id="validationCompanyName"
                            name="company_name" required>
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationCompanyCertificate"><strong>Company Certificate</strong> <span
                                style="color: red;">*</span><a href="" target="_blank"
                                rel="noopener noreferrer"></a></label>
                        <input type="file" class="form-control-sm form-control" id="validationCompanyCertificate"
                            data-name="company_cer" onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="company_cer" value="">
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
                            name="representative" required>
                        <div class="invalid-feedback">
                            Please provide a valid PO No.
                        </div>
                    </div>
                    <div class="col-md-6 mb-6">
                        <label for="validationRepresen"><strong> ID Card/Passport{{--Representative Certificate--}}</strong> <span
                                style="color: red;">*</span> <a href="" target="_blank"
                                rel="noopener noreferrer"></a></label>
                        <input type="file" class="form-control-sm form-control" id="validationRepresen"
                            data-name="representative_cer" onchange="uploadFileContract(this)" required>
                        <div class="mb-3 progress hide-contract">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="representative_cer" value="">
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
                            required></textarea>
                        <div class="invalid-feedback">
                            Please provide a valid Ivoice No.
                        </div>
                    </div>
                </div>
                <div class="float-rigth">
                    <a class="btn btn-primary" style="color: white !important; margin-top: 5px" type="button"
                        href="{{url()->previous()}}">Back</a>
                    <button class="btn btn-primary" type="submit" style="margin-top: 5px">Next</button>
                </div>

            </form>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
@endsection