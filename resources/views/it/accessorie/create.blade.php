@extends('layouts.app')
@section('style')
<style>
    .hide-progress {
        display: none;
    }

    .show-progress {
        display: block;
    }
    label {
        font-weight: bold;
    }
</style>
@endsection
@section('sidebar')
@include('includes.sidebar.it');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>{{ __('itstock.manage-accessorie.add-equipment') }}
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
                {{-- <a href="{{route('it.equipment.management.create')}}" class="btn-shadow btn btn-info">
                <span class="btn-icon-wrapper pr-2 opacity-7">
                    <i class="fa fa-business-time fa-w-20"></i>
                </span>
                ซื้อ</a> --}}
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-header">
            {{ __('itstock.manage-accessorie.equipment-form') }}
        </div>
        <div class="card-body">
            {{-- <h5 class="card-title">{{ __('itstock.manage-accessorie.equipment-form') }}</h5> --}}
            <form class="needs-validation" novalidate action="{{route('it.equipment.management.store')}}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-4 mb-4">
                        <label for="validationAccess_name">{{ __('itstock.manage-accessorie.name') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationAccess_name" name="access_name"
                            value="" required>
                        <div class="invalid-feedback">
                            Please provide a valid Name.
                        </div>
                    </div>
                    <div class="col-md-1 mb-1">
                        <label for="validationUnit">{{ __('itstock.manage-accessorie.unit') }}</label>
                        <input type="text" class="form-control-sm form-control" id="validationUnit" name="unit"
                            value="" required>
                        <div class="invalid-feedback">
                            Please provide a valid Unit.
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="validationEquipmentImage">{{ __('itstock.manage-accessorie.equipment-image') }}
                        </label>
                        <input type="file" class="form-control-sm form-control" id="validationEquipmentImage"
                            data-name="image" onchange="uploadFileEquipment(this)" >
                        <div class="mb-3 progress hide-progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                        </div>
                        <input type="hidden" type="text" name="image" value="">
                        <div class="invalid-feedback">
                            Please provide a valid Image Equipment.
                        </div>
                    </div>
                    <div class="col-md-5 mb-5">
                        <img src="" alt="image.....">
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top: 5px">{{ __('itstock.manage-accessorie.submit-form') }}</button>
            </form>
            <script src="{{asset('assets\js\transactions\accessorie.js')}}" defer></script>
        </div>
    </div>
</div>
@stop