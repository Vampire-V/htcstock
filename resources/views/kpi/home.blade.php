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

    thead tr th {
        position: sticky;
    }

    thead tr:nth-of-type(1) th {
        top: 0;
    }

    thead tr:nth-of-type(2) th {
        top: 20px;
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
        {{-- --}}
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
                                        <div class="col-md-2 mb-2">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="isQuarter">
                                                <label class="custom-control-label" for="isQuarter">Month or
                                                    Quarter</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="label-custom-switch"></label>
                                            <select name="period" id="period" class="form-control-sm form-control"
                                                onchange="search_score()">
                                            </select>
                                            <select name="quarter" id="quarter" class="form-control-sm form-control"
                                                onchange="search_score()" style="display: none;">
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="label-custom-switch">Month</label>
                                            <select name="toperiod" id="toperiod" class="form-control-sm form-control"
                                                onchange="search_score()">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-1">
                                            <label for="Year">Year</label>
                                            <select name="year" id="year" class="form-control-sm form-control"
                                                onchange="search_score()">
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-1">
                                            <label for="Degree">EMC Group</label>
                                            <select name="degree" id="degree" class="form-control-sm form-control"
                                                onchange="search_score()">
                                                @isset($degree)
                                                @foreach ($degree as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                                @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="Division">Division</label>
                                            <select name="division_id" id="division_id"
                                                class="form-control-sm form-control" onchange="search_score()"></select>
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
        @if ($show_rules)
        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Rule Evaluation Report
                            &nbsp;&nbsp;&nbsp;<input type="text" name="ruleName" id="ruleName"
                                class="form-control-sm form-control" placeholder="Search for names..">

                            &nbsp;&nbsp;&nbsp;
                            Category &nbsp;
                            <select class="form-control-sm form-control" name="category" id="category">
                            </select>
                            &nbsp;&nbsp;&nbsp;

                            Year &nbsp;
                            <select class="form-control-sm form-control" name="rule_year" id="rule_year">
                            </select>
                            &nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-info btn-sm" onclick="render_rule()">Search</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="table-responsive" style="height:300px">
                                <table class="mb-0 table table-sm table-bordered table-hover"
                                    id="table-rule-evaluation">
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
        </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="mb-3 card">
                    <div class="card-header-tab card-header-tab-animation card-header">
                        <div class="card-header-title">
                            <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                            Staff Evaluation Report
                            &nbsp;&nbsp;&nbsp;<select name="degree_tab2" id="degree_tab2"
                                onchange="search_staff_table(this)">
                                <option value="">All</option>
                                @isset($degree)
                                @foreach ($degree as $item)
                                <option value="{{$item}}">{{$item}}</option>
                                @endforeach
                                @endisset
                            </select>
                            &nbsp;&nbsp;&nbsp;<select name="department" id="department"
                                onchange="search_staff_table(this)">
                                <option value="">All</option>
                                @isset($departments)
                                @foreach ($departments as $department)
                                <option value="{{$department->name}}">{{$department->name}}</option>
                                @endforeach
                                @endisset
                            </select>

                            &nbsp;&nbsp;&nbsp;<input type="text" onkeyup="search_staff_table(this)" name="full_name"
                                class="form-control-sm form-control" placeholder="Search for names..">

                                &nbsp; Year &nbsp;
                            <select class="form-control-sm form-control" name="staff_year" id="staff_year" onchange="render_staff_evaluate()">
                            </select>
                            &nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="table-responsive" style="height: 300px;">
                                <table class="mb-0 table table-sm table-bordered table-hover"
                                    id="table-staff-evaluation">
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
        </div>
        {{-- --}}
    </div>
</div>

@endsection

@section('modal')
{{-- Modal --}}
<div class="modal fade" id="list-invalid-modal" tabindex="-1" role="dialog" aria-labelledby="rule-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rule-modal-label">Data entry is invalid.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reload" class="reload"></div>
                <label for="Target">Target</label>
                <input type="text" name="target">
                <label for="Actual">Actual</label>
                <input type="text" name="actual">
                <button onclick="changeValues(this)">Change values</button>
                <ul>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('second-script')
{{-- <script src="{{asset('assets\js\index.js')}}" defer></script> --}}
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>
    var show_rules = {!!json_encode($show_rules)!!}
</script>
<script src="{{asset('assets\js\kpi\home.js')}}" defer></script>

@endsection
