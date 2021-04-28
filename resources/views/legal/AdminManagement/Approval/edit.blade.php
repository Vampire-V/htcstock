@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.legal')
@stop
@section('content')
@include('legal.AdminManagement.Approval.create')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>ApprovalManagement
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
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Total Orders</div>
                        <div class="widget-subheading">Last year expenses</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-success">1896</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Products Sold</div>
                        <div class="widget-subheading">Revenue streams</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-warning">$3M</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Followers</div>
                        <div class="widget-subheading">People Interested</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-danger">45.9%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-xl-none d-lg-block col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Income</div>
                        <div class="widget-subheading">Expected totals</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-focus">$147</div>
                    </div>
                </div>
                <div class="widget-progress-wrapper">
                    <div class="progress-bar-sm progress-bar-animated-alt progress">
                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="54" aria-valuemin="0"
                            aria-valuemax="100" style="width: 54%;"></div>
                    </div>
                    <div class="progress-sub-label">
                        <div class="sub-label-left">Expenses</div>
                        <div class="sub-label-right">100%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">
                Approval : {{$department->name}}
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="btn btn-focus">Last Week</button> --}}
                        <button class="btn btn-focus" data-toggle="modal" data-target="#staticBackdrop"
                            data-dept="{{$department->id}}">Add</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover" style="width: 30%">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>email</th>
                            <th class="text-center">Leval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($approvals)
                        @foreach ($approvals as $key => $item)
                        <tr>
                            <td class="text-center text-muted">
                                <button type="button"
                                    href="{{route('legal.adminmanagement.approval.levelup',$item->id)}}" tabindex="0"
                                    class="btn btn-success btn-sm float-left ml-1" onclick="event.preventDefault();
                                            document.getElementById('level-up-form-{{$item->id}}').submit();"><i
                                        class="fa fa-chevron-up" aria-hidden="true"></i></button>
                                <form id="level-up-form-{{$item->id}}"
                                    action="{{route('legal.adminmanagement.approval.levelup',$item->id)}}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    href="{{route('legal.adminmanagement.approval.leveldown',$item->id)}}" tabindex="0"
                                    class="btn btn-primary btn-sm float-left ml-1" onclick="event.preventDefault();
                                            document.getElementById('level-down-form-{{$item->id}}').submit();"><i
                                        class="fa fa-chevron-down" aria-hidden="true"></i></button>
                                <form id="level-down-form-{{$item->id}}"
                                    action="{{route('legal.adminmanagement.approval.leveldown',$item->id)}}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button type="button"
                                    href="{{route('legal.adminmanagement.approval.destroy',$item->id)}}" tabindex="0"
                                    class="btn btn-danger btn-sm float-left ml-1" onclick="event.preventDefault();
                                            document.getElementById('level-destroy-form-{{$item->id}}').submit();"><i
                                        class="fa fa-trash-o" aria-hidden="true"></i></button>
                                <form id="level-destroy-form-{{$item->id}}"
                                    action="{{route('legal.adminmanagement.approval.destroy',$item->id)}}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                            <td class="text-center">{{$item->user->{'name_'.app()->getLocale()} }}</td>
                            <td class="text-center">{{$item->levels}}</td>
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
@endsection

@section('second-script')
<script src="{{asset('assets\js\legals\adminmanagement\approval.js')}}" defer></script>
{{-- <script src="{{asset('assets\js\legals\contractRequestForm\agreements\agreementall.js')}}"></script> --}}
@endsection