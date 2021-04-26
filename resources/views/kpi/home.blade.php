@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi');
@stop
@section('style')
<style>
</style>
@endsection
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-monitor icon-gradient bg-mean-fruit"> </i>
            </div>
            <div>Analytics Dashboard
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
{{-- end title  --}}

<div class="row">
    <div class="col-md-12 col-lg-6">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                    Evaluation Report Your self
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="table-responsive" style="height: 110px;">
                        @isset($ofSelf)
                        <table class="mb-0 table table-sm" id="table-set-actual">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @foreach ($ofSelf as $item)
                                    <th style="width: 100px">{{$item->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Traget</th>
                                    @foreach ($ofSelf as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target'))}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th>Actual</th>
                                    @foreach ($ofSelf as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('actual'))}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-6">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                    Evaluation Report Department
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="table-responsive" style="height: 110px;">
                        @isset($ofDept)
                        <table class="mb-0 table table-sm" id="table-set-actual">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @foreach ($ofDept as $item)
                                    <th style="width: 100px">{{$item->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Traget</th>
                                    @foreach ($ofDept as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target'))}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th>Actual</th>
                                    @foreach ($ofDept as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('actual'))}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                    Evaluation Report
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="table-responsive" style="height: 200px;">
                        <table class="table table-sm" id="table-set-actual">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align : middle;">#</th>
                                    <th rowspan="2" style="vertical-align : middle;">full name</th>
                                    @isset($periods)
                                    @foreach ($periods as $item)
                                    <th colspan="2">{{$item->name}}</th>
                                    @endforeach
                                    @endisset
                                </tr>
                                <tr>
                                    @isset($periods)
                                    @foreach ($periods as $item)
                                    <th>target</th>
                                    <th>actual</th>
                                    @endforeach
                                    @endisset
                                </tr>
                            </thead>
                            <tbody>
                                @isset($users)
                                @foreach ($users as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->name}}</td>
                                    @isset($item->total)
                                    @foreach ($item->total as $total)
                                    <td>
                                        {{$total->target}}
                                    </td>
                                    <td>
                                        {{$total->actual}}
                                    </td>
                                    @endforeach
                                    @endisset
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>
    // variable
</script>
<script src="{{asset('assets\js\kpi\home.js')}}" defer></script>
<script>
</script>
@endsection