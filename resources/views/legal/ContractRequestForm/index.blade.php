@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')

{{-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-gavel icon-gradient bg-happy-fisher"> </i>
            </div>
            <div>Legal Contract
                <div class="page-title-subheading">This is an example dashboard created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">

            </div>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Contract Request
                    <div role="group" class="btn-group-sm btn-group">

                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <a href="{{route('legal.contract-request.create')}}" class="btn-shadow btn btn-danger"
                            data-toggle="tooltip" title="create contract" data-placement="bottom">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                            Create</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('legal.contract-request.index')}}" method="GET">
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <select class="form-control-sm form-control js-select-agreements-multiple"
                                name="agreement[]" multiple>
                                @isset($agreements)
                                @foreach ($agreements as $item)
                                <option value="{{$item->id}}" @if($selectedAgree->contains($item->id)) selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn-shadow btn btn-info" type="submit" data-toggle="tooltip"
                                title="search contract" data-placement="bottom">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </span>
                                Search</button>
                        </div>
                    </div>
                </form>
                <script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
                <script>
                    function destroy(id) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('destroy-form'+id).submit();
                        }
                    })
                }
                </script>
                <hr>

                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Request Date</th>
                                <th>Approval Date</th>
                                <th>Requestor</th>
                                <th>Contracting Party</th>

                                <th>Type</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($contractsRQ)
                            @foreach ($contractsRQ as $key => $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->providing_at}}</td>
                                <td>{{$item->createdBy->name}}</td>
                                <td>{{$item->company_name}}</td>

                                <td class="truncate">{{$item->legalAgreement->name}}</td>
                                @can('isRequest', $item)
                                <td><span class="badge badge-pill badge-primary">{{$item->status}}</span></td>
                                @elsecan('isChecking', $item)
                                <td><span class="badge badge-pill badge-info">{{$item->status}}</span></td>
                                @elsecan('isProviding', $item)
                                <td><span class="badge badge-pill badge-warning">{{$item->status}}</span></td>
                                @elsecan('isComplete', $item)
                                <td><span class="badge badge-pill badge-success">{{$item->status}}</span></td>
                                @endcan
                                <td>
                                    <a href="{{route('legal.contract-request.show',$item->id)}}" data-toggle="tooltip"
                                        title="view contract" data-placement="bottom"
                                        class="btn btn-success btn-sm float-center ml-1"><i class="fa fa-eye"
                                            aria-hidden="true"></i></a>

                                    @if (Auth::user()->can('delete', $item) && Auth::user()->can('update', $item))
                                    <a href="{{route('legal.contract-request.edit',$item->id)}}" data-toggle="tooltip"
                                        title="edit contract" data-placement="bottom"
                                        class="btn btn-primary btn-sm float-center ml-1"><i
                                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                                        rel="noopener noreferrer" style="color: white;"
                                        class="btn btn-danger btn-sm float-center ml-1"
                                        onclick="destroy({{$item->id}})"><i class="pe-7s-trash"> </i></a>
                                    <form id="destroy-form{{$item->id}}"
                                        action="{{route('legal.contract-request.destroy',$item->id)}}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                {{-- @isset($contractsRQ)
                {{ $contractsRQ->appends($query)->links() }}
                @endisset --}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Contract Checking
                    <div role="group" class="btn-group-sm btn-group">

                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <a href="{{route('legal.contract-request.create')}}" class="btn-shadow btn btn-danger"
                            data-toggle="tooltip" title="create contract" data-placement="bottom">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                            Create</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <form action="{{route('legal.contract-request.index')}}" method="GET">
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <select class="form-control-sm form-control js-select-agreements-multiple"
                                name="agreement[]" multiple>
                                @isset($agreements)
                                @foreach ($agreements as $item)
                                <option value="{{$item->id}}" @if($selectedAgree->contains($item->id)) selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn-shadow btn btn-info" type="submit" data-toggle="tooltip"
                                title="search contract" data-placement="bottom">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </span>
                                Search</button>
                        </div>
                    </div>
                </form>
                <script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
                <script>
                    function destroy(id) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('destroy-form'+id).submit();
                        }
                    })
                }
                </script> --}}
                <hr>

                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Request Date</th>
                                <th>Approval Date</th>
                                <th>Requestor</th>
                                <th>Contracting Party</th>

                                <th>Type</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($contractsCK)
                            @foreach ($contractsCK as $key => $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->providing_at}}</td>
                                <td>{{$item->createdBy->name}}</td>
                                <td>{{$item->company_name}}</td>

                                <td class="truncate">{{$item->legalAgreement->name}}</td>
                                @can('isRequest', $item)
                                <td><span class="badge badge-pill badge-primary">{{$item->status}}</span></td>
                                @elsecan('isChecking', $item)
                                <td><span class="badge badge-pill badge-info">{{$item->status}}</span></td>
                                @elsecan('isProviding', $item)
                                <td><span class="badge badge-pill badge-warning">{{$item->status}}</span></td>
                                @elsecan('isComplete', $item)
                                <td><span class="badge badge-pill badge-success">{{$item->status}}</span></td>
                                @endcan
                                <td>
                                    <a href="{{route('legal.contract-request.show',$item->id)}}" data-toggle="tooltip"
                                        title="view contract" data-placement="bottom"
                                        class="btn btn-success btn-sm float-center ml-1"><i class="fa fa-eye"
                                            aria-hidden="true"></i></a>

                                    @if (Auth::user()->can('delete', $item) && Auth::user()->can('update', $item))
                                    <a href="{{route('legal.contract-request.edit',$item->id)}}" data-toggle="tooltip"
                                        title="edit contract" data-placement="bottom"
                                        class="btn btn-primary btn-sm float-center ml-1"><i
                                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                                        rel="noopener noreferrer" style="color: white;"
                                        class="btn btn-danger btn-sm float-center ml-1"
                                        onclick="destroy({{$item->id}})"><i class="pe-7s-trash"> </i></a>
                                    <form id="destroy-form{{$item->id}}"
                                        action="{{route('legal.contract-request.destroy',$item->id)}}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                {{-- @isset($contractsCK)
                {{ $contractsCK->appends($query)->links() }}
                @endisset --}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Contract Providing
                    <div role="group" class="btn-group-sm btn-group">

                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <a href="{{route('legal.contract-request.create')}}" class="btn-shadow btn btn-danger"
                            data-toggle="tooltip" title="create contract" data-placement="bottom">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                            Create</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <form action="{{route('legal.contract-request.index')}}" method="GET">
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <select class="form-control-sm form-control js-select-agreements-multiple"
                                name="agreement[]" multiple>
                                @isset($agreements)
                                @foreach ($agreements as $item)
                                <option value="{{$item->id}}" @if($selectedAgree->contains($item->id)) selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn-shadow btn btn-info" type="submit" data-toggle="tooltip"
                                title="search contract" data-placement="bottom">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </span>
                                Search</button>
                        </div>
                    </div>
                </form>
                <script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
                <script>
                    function destroy(id) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('destroy-form'+id).submit();
                        }
                    })
                }
                </script> --}}
                <hr>

                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Request Date</th>
                                <th>Approval Date</th>
                                <th>Requestor</th>
                                <th>Contracting Party</th>

                                <th>Type</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($contractsP)
                            @foreach ($contractsP as $key => $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->providing_at}}</td>
                                <td>{{$item->createdBy->name}}</td>
                                <td>{{$item->company_name}}</td>

                                <td class="truncate">{{$item->legalAgreement->name}}</td>
                                @can('isRequest', $item)
                                <td><span class="badge badge-pill badge-primary">{{$item->status}}</span></td>
                                @elsecan('isChecking', $item)
                                <td><span class="badge badge-pill badge-info">{{$item->status}}</span></td>
                                @elsecan('isProviding', $item)
                                <td><span class="badge badge-pill badge-warning">{{$item->status}}</span></td>
                                @elsecan('isComplete', $item)
                                <td><span class="badge badge-pill badge-success">{{$item->status}}</span></td>
                                @endcan
                                <td>
                                    <a href="{{route('legal.contract-request.show',$item->id)}}" data-toggle="tooltip"
                                        title="view contract" data-placement="bottom"
                                        class="btn btn-success btn-sm float-center ml-1"><i class="fa fa-eye"
                                            aria-hidden="true"></i></a>

                                    @if (Auth::user()->can('delete', $item) && Auth::user()->can('update', $item))
                                    <a href="{{route('legal.contract-request.edit',$item->id)}}" data-toggle="tooltip"
                                        title="edit contract" data-placement="bottom"
                                        class="btn btn-primary btn-sm float-center ml-1"><i
                                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                                        rel="noopener noreferrer" style="color: white;"
                                        class="btn btn-danger btn-sm float-center ml-1"
                                        onclick="destroy({{$item->id}})"><i class="pe-7s-trash"> </i></a>
                                    <form id="destroy-form{{$item->id}}"
                                        action="{{route('legal.contract-request.destroy',$item->id)}}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                {{-- @isset($contractsP)
                {{ $contractsP->appends($query)->links() }}
                @endisset --}}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="btn-actions-pane">
                    Contract Complete
                    <div role="group" class="btn-group-sm btn-group">

                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <a href="{{route('legal.contract-request.create')}}" class="btn-shadow btn btn-danger"
                            data-toggle="tooltip" title="create contract" data-placement="bottom">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>
                            Create</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- <form action="{{route('legal.contract-request.index')}}" method="GET">
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <select class="form-control-sm form-control js-select-agreements-multiple"
                                name="agreement[]" multiple>
                                @isset($agreements)
                                @foreach ($agreements as $item)
                                <option value="{{$item->id}}" @if($selectedAgree->contains($item->id)) selected
                                    @endif>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn-shadow btn btn-info" type="submit" data-toggle="tooltip"
                                title="search contract" data-placement="bottom">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </span>
                                Search</button>
                        </div>
                    </div>
                </form>
                <script src="{{asset('assets\js\legals\contractRequestForm\create.js')}}"></script>
                <script>
                    function destroy(id) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('destroy-form'+id).submit();
                        }
                    })
                }
                </script> --}}
                <hr>

                <div class="table-responsive">
                    <table class="mb-0 table table-hover table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Request Date</th>
                                <th>Approval Date</th>
                                <th>Requestor</th>
                                <th>Contracting Party</th>

                                <th>Type</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($contractsCP)
                            @foreach ($contractsCP as $key => $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->providing_at}}</td>
                                <td>{{$item->createdBy->name}}</td>
                                <td>{{$item->company_name}}</td>

                                <td class="truncate">{{$item->legalAgreement->name}}</td>
                                @can('isRequest', $item)
                                <td><span class="badge badge-pill badge-primary">{{$item->status}}</span></td>
                                @elsecan('isChecking', $item)
                                <td><span class="badge badge-pill badge-info">{{$item->status}}</span></td>
                                @elsecan('isProviding', $item)
                                <td><span class="badge badge-pill badge-warning">{{$item->status}}</span></td>
                                @elsecan('isComplete', $item)
                                <td><span class="badge badge-pill badge-success">{{$item->status}}</span></td>
                                @endcan
                                <td>
                                    <a href="{{route('legal.contract-request.show',$item->id)}}" data-toggle="tooltip"
                                        title="view contract" data-placement="bottom"
                                        class="btn btn-success btn-sm float-center ml-1"><i class="fa fa-eye"
                                            aria-hidden="true"></i></a>

                                    @if (Auth::user()->can('delete', $item) && Auth::user()->can('update', $item))
                                    <a href="{{route('legal.contract-request.edit',$item->id)}}" data-toggle="tooltip"
                                        title="edit contract" data-placement="bottom"
                                        class="btn btn-primary btn-sm float-center ml-1"><i
                                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                                        rel="noopener noreferrer" style="color: white;"
                                        class="btn btn-danger btn-sm float-center ml-1"
                                        onclick="destroy({{$item->id}})"><i class="pe-7s-trash"> </i></a>
                                    <form id="destroy-form{{$item->id}}"
                                        action="{{route('legal.contract-request.destroy',$item->id)}}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
                {{-- @isset($complete)
                {{ $complete->appends($query)->links() }}
                @endisset --}}
            </div>
        </div>
    </div>
</div>
@stop