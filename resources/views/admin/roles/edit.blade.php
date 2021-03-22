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
            <div>Roles Management
                <div class="page-title-subheading">Tables are the backbone of almost all web
                    applications.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">Edit</h5>
                <form action="{{route('admin.roles.update',$role->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="name"
                            class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-4">
                            <input id="name" type="text"
                                class="form-control-sm form-control @error('name') is-invalid @enderror"
                                name="name" value="{{$role->name}}"  required autocomplete="name"
                                autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="validationPermission_name"
                            class="col-md-3 col-form-label text-md-right">{{ __('Permissions') }}</label>
                        <div class="col-md-4 mb-3">
                            <select name="permission_name[]" id="validationPermission_name"
                                class="form-control-sm form-control role-select2" multiple="multiple" required>
                                <option value="">--เลือก--</option>
                                @foreach ($permissions as $permission)
                                <option value="{{$permission->id}}" {{$role->permissions->pluck('id')->contains($permission->id)? "selected" : "" }} >{{$permission->name}} For {{$permission->system->name}}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit form</button>
                </form>
                <script src="{{asset('assets\js\admin\role.js')}}"></script>
            </div>
        </div>
    </div>
</div>
@stop