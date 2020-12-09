@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<!-- Back to top button -->
<a id="btnontop"></a>
<div class="app-page-title">
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
                <a href="{{route('legal.contract-request.create')}}" class="btn-shadow btn btn-info"
                    data-toggle="tooltip" title="create contract" data-placement="bottom">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </span>
                    Create</a>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="{{route('legal.contract-request.index')}}" method="GET">
                <div class="form-row">
                    <div class="col-md-2 mb-2">
                        <select class="form-control js-select-status-multiple" name="status[]" multiple>
                            @isset($status)
                            @foreach ($status as $item)
                            <option value="{{$item}}" @if($selectedStatus->contains($item)) selected @endif>{{$item}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select class="form-control js-select-agreements-multiple" name="agreement[]" multiple>
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
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Contract item</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full name (Company’s, Person’s) </th>
                            <th>Legal Representative </th>
                            <th>Legal Agreement </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($contracts)
                        @foreach ($contracts as $key => $item)
                        <tr>
                            <td>
                                <a href="{{route('legal.contract-request.show',$item->id)}}" data-toggle="tooltip"
                                    title="view contract" data-placement="bottom"
                                    class="btn btn-success btn-sm float-center ml-1"><i class="fa fa-eye"
                                        aria-hidden="true"></i></a>

                                @if (Auth::user()->can('delete', $item) && Auth::user()->can('update', $item))
                                <a href="{{route('legal.contract-request.edit',$item->id)}}" data-toggle="tooltip"
                                    title="edit contract" data-placement="bottom"
                                    class="btn btn-primary btn-sm float-center ml-1"><i class="fa fa-pencil-square-o"
                                        aria-hidden="true"></i></a>
                                <a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                                    rel="noopener noreferrer" style="color: white;"
                                    class="btn btn-danger btn-sm float-center ml-1" onclick="destroy({{$item->id}})"><i
                                        class="pe-7s-trash"> </i></a>
                                <form id="destroy-form{{$item->id}}"
                                    action="{{route('legal.contract-request.destroy',$item->id)}}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </td>
                            <td>{{$item->company_name}}</td>
                            <td>{{$item->representative}}</td>
                            <td>{{$item->legalAgreement->name}}</td>
                            @can('isRequest', $item)
                            <td><span class="badge badge-pill badge-primary">{{$item->status}}</span></td>
                            @elsecan('isChecking', $item)
                            <td><span class="badge badge-pill badge-info">{{$item->status}}</span></td>
                            @elsecan('isProviding', $item)
                            <td><span class="badge badge-pill badge-warning">{{$item->status}}</span></td>
                            @elsecan('isComplete', $item)
                            <td><span class="badge badge-pill badge-success">{{$item->status}}</span></td>
                            @endcan
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
            @isset($contracts)
            {{ $contracts->appends($query)->links() }}
            @endisset
        </div>
    </div>
</div>
@stop