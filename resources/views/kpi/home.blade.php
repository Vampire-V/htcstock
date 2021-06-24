@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    /* .fiexd-layout {
        flex: 0 0 50% !important;
    } */
    label {
        font-weight: bold;
    }
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

            <div class="d-inline-block dropdown">

            </div>
        </div>
    </div>
</div>

{{-- tabs --}}

<div class="row">
    <div class="col-md-12">
        <div class="mb-3 card">
            <div class="card-body">
                <nav class="navbar">
                    <div class="d-inline-flex" style="width:100%;">
                        <ul class="tabs-animated-shadow tabs-animated nav">
                            {{-- @can('admin-kpi') --}}
                            <li class="nav-item">
                                <a role="tab" class="nav-link " id="tab-c-0" data-toggle="tab" href="#tab-operation"
                                    onclick="tabActive(this)">
                                    <span>Operation</span>
                                </a>
                            </li>
                            {{-- @endcan --}}
                            <li class="nav-item">
                                <a role="tab" class="nav-link " id="tab-c-1" data-toggle="tab" href="#tab-all"
                                    onclick="tabActive(this)">
                                    <span>Report all</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav list-inline ml-auto">
                            <li class="nav-item">
                                {{-- <form class="needs-validation" novalidate method="get" id="form-search">
                                    <select class="form-control form-control-sm" name="year" id="year"
                                        onchange="search()">
                                        @foreach (range(date('Y')-5,date('Y')+5) as $year)
                                        <option value="{{$year}}" @if (intVal($selectedYear)===$year) selected @endif>
                                    {{$year}}</option>
                                    @endforeach
                                    </select>
                                </form> --}}
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane " id="tab-operation" role="tabpanel">
        {{--  --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Evaluation Report Score
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="position-relative form-group">
                                <form class="needs-validation" novalidate>
                                    <div class="form-row">
                                        <div class="col-md-3 mb-3">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                                <label class="custom-control-label" for="customSwitch1">Month or
                                                    Quarter</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="label-custom-switch"></label>
                                            <select name="period" id="period" class="form-control-sm form-control"
                                                onchange="search_score()">
                                            </select>
                                            <select name="quarter" id="quarter" class="form-control-sm form-control"
                                                onchange="search_score()" style="display: none;">
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="Year">Year</label>
                                            <select name="year" id="year" class="form-control-sm form-control"
                                                onchange="search_score()">
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="Degree">EMC Group</label>
                                            <select name="degree" id="degree" class="form-control-sm form-control"
                                            onchange="search_score()">
                                                <option value="N-1">N-1</option>
                                                <option value="N-2">N-2</option>
                                                <option value="N-3">N-3</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="mb-0 table table-sm" id="table-report-score">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" style="vertical-align : middle;">Name</th>
                                            <th rowspan="2" style="vertical-align : middle;">Division
                                            </th>
                                            <th>KPI</th>
                                            <th>Key-task</th>
                                            <th>OMG</th>
                                            <th rowspan="2" style="vertical-align : middle;">Score
                                            </th>
                                            <th rowspan="2" style="vertical-align : middle;">Rank</th>
                                            <th rowspan="2" style="vertical-align : middle;">Rate</th>
                                        </tr>
                                        <tr>
                                            <th>0</th>
                                            <th>0</th>
                                            <th>0</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <div id="reload" class="reload"></div>
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
    </div>


    <div class="tab-pane " id="tab-all" role="tabpanel">
        {{--  --}}
        <div class="row">
            <div class="col-xl-6">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Evaluation Report {{date('Y')}} Your self
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="table-responsive" style="height: 110px;">
                                <table class="mb-0 table table-sm" id="table-self-evaluation">
                                    <thead class="thead-dark">
                                    </thead>
                                    <tbody>
                                        <div id="reload" class="reload"></div>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
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
                                {{-- <table class="mb-0 table table-sm" id="table-dept-evaluation">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            @foreach ($ofDept as $item)
                                            <th scope="col">{{$item->name}}</th>
                                @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Target</th>
                                        @foreach ($ofDept as $item)
                                        <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target'))}}
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th scope="row">Actual</th>
                                        @foreach ($ofDept as $item)
                                        <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('actual'))}}
                                        </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                                </table> --}}
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Rule Evaluation Report
                            &nbsp;&nbsp;&nbsp;<input type="text" onkeyup="search_table(this)"
                                class="form-control-sm form-control" placeholder="Search for names..">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="table-responsive" style="height:300px">
                                {{-- <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
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
                                    @isset($rules)
                                    @foreach ($rules as $key => $item)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td class="truncate" data-toggle="tooltip" title="{{$item->name}}">
                                            {{$item->name}}</td>
                                        @isset($item->total)
                                        @foreach ($item->total as $total)
                                        <td>{{$total->target}}</td>
                                        <td>{{$total->actual}}</td>
                                        @endforeach
                                        @endisset
                                    </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>
                                </tfoot>
                                </table> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Staff Evaluation Report
                            &nbsp;&nbsp;&nbsp;<input type="text" onkeyup="search_table(this)"
                                class="form-control-sm form-control" placeholder="Search for names..">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="table-responsive" style="height: 300px;">
                                {{-- <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2" style="vertical-align : middle;">#</th>
                                            <th rowspan="2" style="vertical-align : middle;">Department</th>
                                            <th rowspan="2" style="vertical-align : middle;">Full Name</th>
                                            @isset($periods)
                                            @foreach ($periods as $period)
                                            <th colspan="2">{{$period->name}}</th>
                                @endforeach
                                @endisset
                                </tr>
                                <tr>
                                    @isset($periods)
                                    @foreach ($periods as $period)
                                    <th>target</th>
                                    <th>actual</th>
                                    @endforeach
                                    @endisset
                                </tr>
                                </thead>
                                <tbody>
                                    @isset($users)
                                    @foreach ($users as $key => $user)
                                    <tr>
                                        <th>{{$key+1}}</th>
                                        <td class="truncate" data-toggle="tooltip" title="{{$user->department->name}}">
                                            {{$user->department->name}}</td>
                                        <td class="truncate" data-toggle="tooltip" title="{{$user->name}}">
                                            {{ $user->name }}</td>
                                        @isset($user->total)
                                        @foreach ($user->total as $total)
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
                                </table> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  --}}
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

@endsection