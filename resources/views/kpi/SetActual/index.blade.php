@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-monitor icon-gradient bg-mean-fruit"> </i>
            </div>
            <div>Set Actual
                <div class="page-title-subheading">This is an example set target created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
        </div>
    </div>
</div>

{{-- <h1>ยังไม่เปิดให้ใช้งาน.....</h1> --}}
{{-- end title  --}}
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-header-tab card-header-tab-animation card-header">
                <div class="card-header-title">
                    <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                    <h5 class="card-title">Search</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative form-group">
                    <form class="needs-validation" novalidate>
                        <div class="form-row">
                            <div class="col-md-2 mb-2">
                                <label for="Category">Category</label>
                                <select name="category" id="category" class="form-control-sm form-control">
                                    <option value="">Choose...</option>
                                    @isset($categorys)
                                    @foreach ($categorys as $category)
                                    <option value="{{$category->id}}" @if (intval($selectedCategory)===$category->id)
                                        selected
                                        @endif>{{$category->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="RuleName">Rule Name</label>
                                <select name="rule" id="rule" class="form-control-sm form-control">
                                    <option value="">Choose...</option>
                                    @isset($rules)
                                    @foreach ($rules as $rule)
                                    <option value="{{$rule->id}}" @if (intval($selectedRule)===$rule->id)
                                        selected
                                        @endif>{{$rule->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Year">Target Period</label>
                                <select name="period" id="period" class="form-control-sm form-control">
                                    <option value="">Choose...</option>
                                    @isset($months)
                                    @foreach ($months as $month)
                                    <option value="{{date('m', strtotime($month->name." 1 2021"))}}"
                                        @if($selectedPeriod===date('m', strtotime($month->name." 1 2021")))
                                        selected
                                        @endif>{{$month->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="Year">Year</label>
                                <select name="year" id="year" class="form-control-sm form-control">
                                    @foreach (range(date('Y'), $start_year) as $year)
                                    <option value="{{$year}}" @if ($selectedYear==$year) selected @endif>{{$year}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="Department">Department</label>
                                <select name="department" id="department" class="form-control-sm form-control">
                                    <option value="">Choose...</option>
                                    @isset($departments)
                                    @foreach ($departments as $item)
                                    <option value="{{$item->id}}" @if ($selectedDept==$item->id)
                                        selected
                                        @endif>{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-1 mb-1 text-center">
                                <button class="btn btn-primary mt-4" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Set Actual for : {{Auth::user()->name}}</h5>
                <div class="table-responsive">
                    <table class="mb-0 table table-sm" id="table-set-actual">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Target Period</th>
                                <th>Rule Name</th>
                                <th>Base Line%</th>
                                <th>Max%</th>
                                <th>Weight%</th>
                                <th style="width: 12%;">Target Amount</th>
                                <th>target %</th>
                                <th style="width: 12%;">Actual Amount</th>
                                <th>actual %</th>
                                <th>Ach%</th>
                                <th>Cal%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($evaluateDetail)
                            @foreach ($evaluateDetail as $key => $item)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$item->rule->category->name }}</td>
                                <td>{{$item->evaluate->targetperiod->name}} {{$item->evaluate->targetperiod->year}}</td>
                                <td class="truncate" data-toggle="tooltip" title="" data-placement="top"
                                    data-original-title="{{$item->rule->name}}">{{$item->rule->name}}</td>
                                <td>{{number_format(floatval($item->base_line), 2, '.', '')}}%</td>
                                <td>{{number_format(floatval($item->max_result), 2, '.', '')}}%</td>
                                <td>{{number_format(floatval($item->weight), 2, '.', '')}}%</td>
                                <td><input type="number" name="target" id="target_{{$item->id}}"
                                        value="{{number_format(floatval($item->target), 2, '.', '')}}" step="0.01" class="form-control form-control-sm"
                                        onchange="changeTarget(this)" />
                                </td>
                                <td>{{number_format(floatval($item->target_pc), 2, '.', '')}}%</td>
                                <td><input type="number" name="actual" id="{{$item->id}}" value="{{number_format(floatval($item->actual), 2, '.', '')}}"
                                        step="0.01" class="form-control form-control-sm"
                                        onchange="changeActual(this)" />
                                </td>
                                <td>{{number_format(floatval($item->actual_pc), 2, '.', '')}}%</td>
                                <td>{{number_format(floatval($item->ach), 2, '.', '')}}%</td>
                                <td>{{number_format(floatval($item->cal), 2, '.', '')}}%</td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9"></td>
                                <td><button class="mb-2 mr-2 btn btn-success btn-sm"
                                        onclick="submit(this)">Save</button>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
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
    const detail = {!!json_encode($evaluateDetail)!!}
    var all_data = [];
</script>
<script src="{{asset('assets\js\kpi\setActual\index.js')}}" defer></script>
@endsection