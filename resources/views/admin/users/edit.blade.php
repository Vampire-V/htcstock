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
    <div class="col-lg-12">
        <div class="main-card mb-12 card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Form user') }}</h5>
                <form action="{{route('admin.users.update',$user->id)}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-3">
                            <input id="name" type="text"
                                class="form-control-sm form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ $user->name }}" required autocomplete="name" readonly>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email"
                            class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-3">
                            <input id="email" type="email"
                                class="form-control-sm form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ $user->email }}" autocomplete="email" readonly>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="division"
                            class="col-md-3 col-form-label text-md-right">{{ __('Division') }}</label>

                        <div class="col-md-3">
                            <input id="division" type="text"
                                class="form-control-sm form-control @error('division') is-invalid @enderror"
                                name="division" value="{{ $user->divisions->name }}" autocomplete="division"
                                readonly>

                            @error('division')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="department"
                            class="col-md-3 col-form-label text-md-right">{{ __('Department') }}</label>

                        <div class="col-md-3">
                            <input id="department" type="text"
                                class="form-control-sm form-control @error('department') is-invalid @enderror"
                                name="department" value="{{ $user->department->name }}" autocomplete="department"
                                readonly>

                            @error('department')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="position" class="col-md-3 col-form-label text-md-right">{{ __('Position') }}</label>

                        <div class="col-md-3">
                            <input id="position" type="text"
                                class="form-control-sm form-control @error('position') is-invalid @enderror"
                                name="position" value="{{ $user->positions->name }}" autocomplete="position" readonly>

                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
            <a class="btn btn-warning mr-2" type="button" href="{{url()->previous()}}">Back</a>
            {{-- <button type="submit" class="btn btn-primary">Update</button> --}}
            </form>

        </div>
    </div>
</div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header">Role for users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="active btn btn-focus">Last Week</button> --}}
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
                {{-- <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                        class="pe-7s-trash btn-icon-wrapper"> </i></button>
                <button class="btn-wide btn btn-success">Save</button> --}}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="main-card mb-3 card">
            <div class="card-header">System for users
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="active btn btn-focus">Last Week</button> --}}
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
                {{-- <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                        class="pe-7s-trash btn-icon-wrapper"> </i></button>
                <button class="btn-wide btn btn-success">Save</button> --}}
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