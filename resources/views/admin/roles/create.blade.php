@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.admin');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-drawer icon-gradient bg-happy-itmeo">
                </i>
            </div>
            <div>Permissions Management
                <div class="page-title-subheading">Tables are the backbone of almost all web
                    applications.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block">
                {{-- <button type="button" data-toggle="modal" data-target="#accessoriesModal"
                    class="btn-shadow  btn btn-info" data-param="">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    เพิ่ม
                </button> --}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">Create</h5>
                <form action="{{route('admin.roles.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-3">
                            <input id="name" type="text" class="form-control-sm form-control @error('name') is-invalid @enderror"
                                name="name" value="" required autocomplete="name" autofocus onkeyup="replateValue(this)" onchange="replateValue(this)">

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <label for="slug" class="col-md-2 col-form-label text-md-right">{{ __('Slug') }}</label>
                        <div class="col-md-3">
                            <input id="slug" type="text" class="form-control-sm form-control @error('slug') is-invalid @enderror"
                                name="slug" value="" required autocomplete="slug" autofocus readonly>

                            @error('slug')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="validationPermission_name"
                            class="col-md-2 col-form-label text-md-right">{{ __('Permissions') }}</label>
                        <div class="col-md-3 mb-3">
                            <select name="permission_name[]" id="validationPermission_name"
                                class="form-control-sm form-control role-select2" multiple="multiple" required>
                                <option value="">--เลือก--</option>
                                @foreach ($permissions as $permission)
                                <option value="{{$permission->id}}">{{$permission->name}}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit form</button>
                </form>
                <script>
                    function replateValue (e){
                        let t = e.value.replace(" ","-")
                        document.getElementById('slug').value = t.toLowerCase()
                    }
                </script>
                <script src="{{asset('assets\js\admin\role.js')}}" defer></script>
            </div>
        </div>
    </div>
</div>
@stop