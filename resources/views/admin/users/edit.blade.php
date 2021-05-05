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
            <div>Users Management
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Form user') }}</h5>
                <form class="" action="{{route('me.user.update',$user->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">{{ __('Name') }} (TH)</label>
                                <input name="name:th" id="name_th" placeholder="Name placeholder" type="text"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" readonly
                                    value="{{ $user->translate('th') ? $user->translate('th')->name : null }}" required
                                    autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="name" class="">{{ __('Name') }} (EN)</label>
                                <input name="name:en" id="name_en" placeholder="Name placeholder" type="text"
                                    class="form-control form-control-sm @error('name') is-invalid @enderror" readonly
                                    value="{{ $user->translate('en') ? $user->translate('en')->name : null }}" required
                                    autocomplete="name" autofocus>
                                    
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="email" class="">{{ __('E-Mail Address') }}</label>
                                <input name="email" id="email" placeholder="with a placeholder" type="email"
                                    value="{{ $user->email }}" required autocomplete="email" readonly
                                    class="form-control form-control-sm">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Phone" class="">{{ __('Phone') }}</label>
                                <input name="phone" id="phone" placeholder="Phone placeholder" type="text"
                                    class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                    value="{{ $user->phone }}" required autocomplete="phone" autofocus readonly>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Division" class="">{{ __('Division') }}</label>
                                <input name="division" id="division" placeholder="with a placeholder" type="division"
                                    value="{{ $user->divisions->name }}" required autocomplete="division" readonly
                                    class="form-control form-control-sm">
                                @error('division')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Department" class="">{{ __('Department') }}</label>
                                <input name="department" id="department" placeholder="Department placeholder"
                                    type="text"
                                    class="form-control form-control-sm @error('department') is-invalid @enderror"
                                    value="{{ $user->department->name }}" required autocomplete="department" readonly
                                    autofocus>
                                @error('department')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Position" class="">{{ __('Position') }}</label>
                                <input name="position" id="position" placeholder="with a placeholder" type="position"
                                    value="{{ $user->positions->name }}" required autocomplete="position" readonly
                                    class="form-control form-control-sm">
                                @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-warning mr-2" type="button" href="{{url()->previous()}}">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header">Role for users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="btn btn-focus" data-toggle="modal" data-target=".bd-role-modal-lg">Add</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="table-role">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($userRoles)
                        @foreach ($userRoles as $key => $item)
                        <tr>
                            <td class="text-center text-muted"><button onclick="removeRole({{$user->id}},{{$item->id}})"
                                    class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                                        class="pe-7s-trash btn-icon-wrapper"> </i></button>
                            </td>
                            <td class="text-center">
                                <div class="badge">{{$item->name}}</div>
                            </td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="d-block text-center card-footer">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header">System for users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="btn btn-focus" data-toggle="modal" data-target=".bd-system-modal-lg">Add</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="table-system">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($userSystems)
                        @foreach ($userSystems as $key => $item)
                        <tr>
                            <td class="text-center text-muted"><button
                                    onclick="removeSystem({{$user->id}},{{$item->id}})"
                                    class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                                        class="pe-7s-trash btn-icon-wrapper"> </i></button>
                            </td>
                            <td class="text-center">
                                <div class="badge badge-warning">{{$item->name}}</div>
                            </td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
            <div class="d-block text-center card-footer">
            </div>
        </div>
    </div>
</div>

<!-- Role add user modal -->

<div class="modal fade bd-role-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationRole" class="">{{ __('Role') }}</label>
                            <select class="form-control-sm form-control js-select-role-multiple" style="width: 100%"
                                name="role[]" multiple>
                                @isset($roles)
                                @foreach ($roles as $item)
                                <option value="{{$item->slug}}">{{$item->name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addRole({{$user->id}})">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- System add user modal -->

<div class="modal fade bd-system-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add system</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationSystem" class="">{{ __('System') }}</label>
                            <select class="form-control-sm form-control js-select-system-multiple" style="width: 100%"
                                name="system[]" multiple>
                                @isset($systems)
                                @foreach ($systems as $item)
                                <option value="{{$item->slug}}">{{$item->name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addSystem({{$user->id}})">Save changes</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('second-script')
<script src="{{asset('assets\js\admin\user.js')}}" defer></script>
@endsection