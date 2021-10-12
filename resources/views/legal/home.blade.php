@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.legal');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-balance-scale icon-gradient bg-happy-fisher" aria-hidden="true"></i>
            </div>
            <div>CONTRACT LIFECYCLE MANAGEMENT
                <div class="page-title-subheading">Please read "Contract Management SOP" carefully before requesting!
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Dowload SOP" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-download " aria-hidden="true"></i>
            </button>
            {{-- <div class="d-inline-block dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    class="btn-shadow dropdown-toggle btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Buttons
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-inbox"></i>
                                <span>
                                    Inbox
                                </span>
                                <div class="ml-auto badge badge-pill badge-secondary">86</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-book"></i>
                                <span>
                                    Book
                                </span>
                                <div class="ml-auto badge badge-pill badge-danger">5</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-picture"></i>
                                <span>
                                    Picture
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a disabled href="javascript:void(0);" class="nav-link disabled">
                                <i class="nav-link-icon lnr-file-empty"></i>
                                <span>
                                    File Disabled
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-6">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">All contract</div>
                        <div class="widget-subheading">สัญญา ทั้งหมด</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-success">{{$allPromised}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Own promise</div>
                        <div class="widget-subheading">สัญญา ที่สร้าง</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-warning">{{$ownPromise}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Don't know what to show</div>
                        <div class="widget-subheading">-----------</div>
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-danger">45,9%</div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="d-xl-none d-lg-block col-md-6 col-xl-4">
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
    </div> --}}
</div>
<div class="divider mt-0" style="margin-bottom: 30px;"></div>
<div class="row">
    <div class="col-lg-6 col-xl-3">
        <div class="card mb-3 widget-content bg-night-fade">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Request</div>
                    <div class="widget-subheading">Last year expenses</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span>{{$requestSum}}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card mb-3 widget-content bg-arielle-smile">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Checking</div>
                    <div class="widget-subheading">Total Clients Profit</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span>{{$checking}}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card mb-3 widget-content bg-premium-dark">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Providing</div>
                    <div class="widget-subheading">Total revenue streams</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-warning"><span>{{$providing}}</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3">
        <div class="card mb-3 widget-content bg-happy-green">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Complete</div>
                    <div class="widget-subheading">People Interested</div>
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-dark"><span>{{$complete}}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="divider mt-0" style="margin-bottom: 30px;"></div>
@can('for-adminlegal')
<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="#" method="GET">
                    <div class="form-row">
                        <div class="col-md-2 mb-2">
                            <select class="form-control-sm form-control js-select-created-multiple" name="created_by[]"
                                multiple>
                                @isset($requestor)
                                @foreach ($requestor as $item)
                                <option value="{{$item->created_by}}" @if($selectedCreated->contains($item->created_by))
                                    selected
                                    @endif>{{$item->createdBy->name}}
                                </option>
                                @endforeach
                                @endisset
                            </select>
                        </div>
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
                <script>
                    (function () {
                        'use strict';

                        document.addEventListener('DOMContentLoaded', function () {
                            $(".js-select-created-multiple").select2({
                                placeholder: 'Select Requestor',
                                allowClear: true
                            });
                            // $(".js-select-status-multiple").val('request')
                            // $('.js-select-status-multiple').trigger('change');
                            $(".js-select-agreements-multiple").select2({
                                placeholder: 'Select type',
                                allowClear: true
                            });
                        })

                        window.addEventListener('load', function () {
                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                            // let forms = document.getElementsByClassName('needs-validation');
                            // Loop over them and prevent submission
                            // validationForm(forms)
                        }, false);

                    })();
                </script>
            </div>
        </div>
    </div>
</div>
@isset($contracts)
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-header">Contract Status
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        {{-- <button class="active btn btn-focus">Last Week</button>
                        <button class="btn btn-focus">All Month</button> --}}
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Requestor</th>
                            <th>Contracting Party</th>
                            <th>Legal Representative</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

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
                            <td>{{$item->createdBy->name}}</td>
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
                    </tbody>
                </table>
            </div>
            <div class="d-block text-center card-footer">
                {{-- <button class="mr-2 btn-icon btn-icon-only btn btn-outline-danger"><i
                        class="pe-7s-trash btn-icon-wrapper">
                    </i></button>
                <button class="btn-wide btn btn-success">Save</button> --}}
            </div>
        </div>
    </div>
</div>
@endisset
@endcan

{{-- <div class="row">
    <div class="col-md-6 col-lg-3">
        <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
            <div class="widget-content">
                <div class="widget-content-outer">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left pr-2 fsize-1">
                            <div class="widget-numbers mt-0 fsize-3 text-primary">{{$requestCal}}%</div>
</div>
<div class="widget-content-right w-100">
    <div class="progress-bar-xs progress">
        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="{{$requestCal}}" aria-valuemin="0"
            aria-valuemax="100" style="width: {{$requestCal}}%;"></div>
    </div>
</div>
</div>
<div class="widget-content-left fsize-1">
    <div class="text-muted opacity-6">Request</div>
</div>
</div>
</div>
</div>
</div>
<div class="col-md-6 col-lg-3">
    <div class="card-shadow-info mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left pr-2 fsize-1">
                        <div class="widget-numbers mt-0 fsize-3 text-info">{{$checking}}%</div>
                    </div>
                    <div class="widget-content-right w-100">
                        <div class="progress-bar-xs progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{$checking}}"
                                aria-valuemin="0" aria-valuemax="100" style="width: {{$checking}}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="widget-content-left fsize-1">
                    <div class="text-muted opacity-6">Checking</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-3">
    <div class="card-shadow-warning mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left pr-2 fsize-1">
                        <div class="widget-numbers mt-0 fsize-3 text-warning">{{$providing}}%</div>
                    </div>
                    <div class="widget-content-right w-100">
                        <div class="progress-bar-xs progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{$providing}}"
                                aria-valuemin="0" aria-valuemax="100" style="width: {{$providing}}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="widget-content-left fsize-1">
                    <div class="text-muted opacity-6">Providing</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6 col-lg-3">
    <div class="card-shadow-success mb-3 widget-chart widget-chart2 text-left card">
        <div class="widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left pr-2 fsize-1">
                        <div class="widget-numbers mt-0 fsize-3 text-success">{{$complete}}%</div>
                    </div>
                    <div class="widget-content-right w-100">
                        <div class="progress-bar-xs progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{$complete}}"
                                aria-valuemin="0" aria-valuemax="100" style="width: {{$complete}}%;"></div>
                        </div>
                    </div>
                </div>
                <div class="widget-content-left fsize-1">
                    <div class="text-muted opacity-6">Complete</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> --}}
@endsection

@section('modal')

<div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="alert-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alert-modal-label">Contract request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ยังไม่เปิดให้ใช้งาน.....</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" >Add</button> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script type="text/javascript" defer>
    $(window).on('load', function() {
        let isShow = getCookie("alert_modal_shown");
        if (!isShow) {
            setCookie("alert_modal_shown", 'yes', 1, '/legal/dashboard')
            $('#alert-modal').modal('show');
        }
    });
</script>

@endsection
