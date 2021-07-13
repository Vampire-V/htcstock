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
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
            <div class="d-inline-block">
                <a href="{{route('admin.roles.create')}}" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Add</a>
            </div>
        </div>
    </div>
</div>
{{-- <div class="col-lg-12"> --}}
<div class="main-card mb-3 card">
    <div class="card-header">
        {{ __('Form search') }}
    </div>
    <div class="card-body">
        <form action="#" method="GET">
            <div class="form-row">
                <div class="col-md-2 mb-2">
                    <label for="role">Role</label>
                    <select class="form-control-sm form-control js-select-role-multiple" name="role[]" multiple>
                        @isset($dropdown)
                        @foreach ($dropdown as $item)
                        <option value="{{$item->id}}" @if($selectedRole->contains($item->id)) selected
                            @endif>{{$item->name}}</option>
                        @endforeach
                        @endisset
                    </select>
                </div>

                <div class="col-md-2 mb-2">
                    <button class="btn-shadow btn btn-info mb-1" type="submit" style="margin-top: 30px">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-fw" aria-hidden="true" title="Copy to use search"></i>
                        </span>
                        Search</button>
                </div>
            </div>
        </form>
        <script>
            (function () {
                'use strict';
                window.addEventListener('load', function () {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    $(".js-select-role-multiple").select2({
                        placeholder: 'Select role',
                        allowClear: true
                    });
                }, false);
            })();
        </script>
    </div>
</div>
{{-- </div> --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                {{ __('Role list') }}
            </div>
            <div class="card-body">
                {{-- <h5 class="card-title">Roles</h5> --}}
                <div class="table-responsive">
                    <table class="mb-0 table table-hover" id="table-roles">
                        <thead>
                            <tr>
                                <th width="150px">#</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                            <tr>
                                <td>
                                    <a href="{{route('admin.roles.edit',$role->id)}}"><button type="button"
                                            class="btn btn-primary btn-sm float-left mr-2">Edit</button></a>
                                    @can('super-admin')
                                    <form action="{{route('admin.roles.destroy',$role->id)}}" method="POST"
                                        class="float-left">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                    </form>
                                    @endcan


                                </td>
                                <td>{{$role->name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $roles->appends($query)->links() }}
        </div>
    </div>
</div>
@stop