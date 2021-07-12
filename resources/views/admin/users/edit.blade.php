@extends('layouts.app')

@section('sidebar')
@include('includes.sidebar.admin');
@stop
@section('style')
<style>
    label>span {
        color: red;
    }

    li>span {
        text-align: right;
    }

    img {
        box-shadow: 0 8px 17px 0 rgba(0, 0, 0, .2), 0 6px 20px 0 rgba(0, 0, 0, .19) !important;
    }
</style>
@endsection
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
                        <div class="col-md-3">
                            <img src="{{asset('/storage'.$user->image)}}" class="rounded z-depth-2" alt="...">
                        </div>
                        <div class="col-md-4">
                            <div class="position-absolute form-group fixed-bottom mr-2">
                                <label for="name" class="">{{ __('profile.name') }} (TH)</label>
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
                        <div class="col-md-4">
                            <div class="position-absolute form-group fixed-bottom">
                                <label for="name" class="">{{ __('profile.name') }} (EN)</label>
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
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="email" class="">{{ __('profile.email') }}</label>
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
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Phone" class="">{{ __('profile.phone') }}</label>
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
                                <label for="Division" class="">{{ __('profile.division') }}</label>
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
                                <label for="Department" class="">{{ __('profile.department') }}</label>
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
                                <label for="Position" class="">{{ __('profile.position') }}</label>
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
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @canany(['super-admin','admin-kpi'])
    <div class="col-md-4">
        <div class="main-card mb-4 card">
            <div class="card-header">KPI Level approve
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" id="user-approve-modal"
                            data-toggle="modal" data-target="#lv-approve-modal">Add</button>
                        <button class="btn btn-sm btn-alternate" data-toggle="modal" data-target="#copy-to-modal">Copy
                            To</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="table-approve">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Lv</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="d-block text-center card-footer">
            </div>
        </div>
    </div>
    @endcanany
    @can('super-admin')
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header">Role for users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <button class="btn btn-focus" data-toggle="modal" data-target=".bd-role-modal-sm">Add</button>
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
                                    class="mr-2 btn-icon btn-icon-only btn-sm btn btn-outline-danger"><i
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
                        <button class="btn btn-focus" data-toggle="modal" data-target=".bd-system-modal-sm">Add</button>
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
                                    class="mr-2 btn-icon btn-icon-only btn-sm btn btn-outline-danger"><i
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
    @endcan

</div>
@stop

@section('modal')
<!-- Level Approve user modal -->
<div class="modal fade" id="lv-approve-modal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LevelApproveModalTitle">Add Level Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="validationRole" class="">{{ __('User') }}
                                <span>(เลือกหลายคนได้นะ)</span></label>
                            <select class="form-control-sm form-control js-select-user-multiple" id="user" name="user[]"
                                multiple>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addLvApprove()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Copy level approve to user orther modal -->
<div class="modal fade" id="copy-to-modal" tabindex="-1" role="dialog" aria-labelledby="copyToModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copyToModalLabel">Copy To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="col-md-12 mb-12">
                            <label for="validationRole" class="">{{ __('User') }}
                                <span>(เลือกหลายคนได้นะ)</span></label>
                            <select class="form-control-sm form-control js-select-user-copy-multiple" id="user_copy"
                                name="user_copy[]" multiple>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="copy_to()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Role add user modal -->
<div class="modal fade bd-role-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
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
                        <div class="col-md-12 mb-12">
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
<div class="modal fade bd-system-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm">
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
                        <div class="col-md-12 mb-12">
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
@endsection
@section('second-script')
<script src="{{asset('assets\js\kpi\index.js')}}" defer>
    // all method KPI 
</script>
<script>
    var user = {!!json_encode($user)!!}
</script>
<script src="{{asset('assets\js\admin\user.js')}}" defer></script>
@endsection