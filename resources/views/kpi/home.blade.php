@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    /* .fiexd-layout {
        flex: 0 0 50% !important;
    } */
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
            <label for="ForYear" class="mr-sm-2">Year</label>
            <div class="d-inline-block dropdown">
                <form class="needs-validation" novalidate method="get" id="form-search">
                    <select class="form-control form-control-sm" name="year" id="year" onchange="test()">
                        @foreach (range(date('Y')-5,date('Y')+5) as $year)
                        <option value="{{$year}}" @if (intVal($selectedYear)===$year) selected @endif>
                            {{$year}}</option>
                        @endforeach
                    </select>
                </form>
                <script>
                    function test() {
                        console.log(document.forms);
                        document.forms['form-search'].submit();
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6">
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
                                    <th scope="col">#</th>
                                    @foreach ($ofSelf as $item)
                                    <th scope="col">{{$item->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Traget</th>
                                    @foreach ($ofSelf as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target'))}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th scope="row">Actual</th>
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
                        <table class="mb-0 table table-sm" id="table-set-actual">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    @foreach ($ofDept as $item)
                                    <th scope="col">{{$item->name}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Traget</th>
                                    @foreach ($ofDept as $item)
                                    <td>{{$item->evaluates->sum(fn ($t) => $t->evaluateDetail->sum('target'))}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th scope="row">Actual</th>
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
                        <table class="table table-sm table-bordered">
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
                                @isset($rules)
                                @foreach ($rules as $key => $item)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td class="truncate" data-toggle="tooltip" title="{{$item->name}}">{{$item->name}}</td>
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
                        </table>
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
                        <table class="table table-sm table-bordered">
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
                                @foreach ($users as $key => $user)
                                <tr>
                                    <th>{{$key+1}}</th>
                                    <td class="truncate" data-toggle="tooltip" title="{{$user->name}}">{{ $user->name }}</td>
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