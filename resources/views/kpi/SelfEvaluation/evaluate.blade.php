@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi');
@stop
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-monitor icon-gradient bg-mean-fruit"> </i>
            </div>
            <div>Self Evaluate
                <div class="page-title-subheading">This is an example self evaluate created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button>
        </div>
    </div>
</div>
{{-- Display user detail --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="card-header">
                <h5 class="card-title">{{$evaluate->targetperiod->name}} {{$evaluate->targetperiod->year}}</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>Status <span class="badge badge-info">{{$evaluate->status}}</span></h5>
                    </div>
                </div>
            </div>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$evaluate->user->name}}" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{$evaluate->user->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" readonly>
                                <div class="invalid-feedback">
                                    Please choose a username.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{$evaluate->user->positions->name}}" disabled>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="form-row">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- KPI --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">KPI</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rule Name</th>
                            <th>Base Line</th>
                            <th>Max</th>
                            <th>Weight</th>
                            <th>Target</th>
                            <th style="width: 10%;">Actual</th>
                            <th>%Ach</th>
                            <th>%Cal</th>
                            {{-- <th>Result</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @isset($kpi)
                        @foreach ($kpi->values() as $key => $item)
                        <tr data-id="{{$item->id}}">
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->rule->name}} - {{$item->rule->calculate_type}}</td>
                            <td>{{number_format($item->base_line,2,'.','')}}</td>
                            <td>{{number_format($item->max_result,2,'.','')}}</td>
                            <td>{{number_format($item->weight,2,'.','')}}%</td>
                            <td>{{number_format($item->target,2,'.','')}}</td>
                            <td><input type="number" class="form-control form-control-sm" value="{{$item->actual}}"
                                    step="0.01" onchange="changeValue(this)"></td>
                            <td>{{number_format($item->ach,2,'.','')}}%</td>
                            <td>{{number_format($item->cal,2,'.','')}}%</td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>{{number_format($kpi->sum('weight'),2,'.','')}}%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{number_format($kpi->sum('cal'),2,'.','')}}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Key Task --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Key Task</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rule Name</th>
                            <th>Base Line</th>
                            <th>Max</th>
                            <th>Weight</th>
                            <th>Target</th>
                            <th style="width: 10%;">Actual</th>
                            <th>%Ach</th>
                            <th>%Cal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($key_task)
                        @foreach ($key_task->values() as $key => $item)
                        <tr data-id="{{$item->id}}">
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->rule->name}} - {{$item->rule->calculate_type}}</td>
                            <td>{{number_format($item->base_line,2,'.','')}}</td>
                            <td>{{number_format($item->max_result,2,'.','')}}</td>
                            <td>{{number_format($item->weight,2,'.','')}}%</td>
                            <td>{{number_format($item->target,2,'.','')}}</td>
                            <td><input type="number" class="form-control form-control-sm" value="{{$item->actual}}"
                                    step="0.01" onchange="changeValue(this)"></td>
                            <td>{{number_format($item->ach,2,'.','')}}%</td>
                            <td>{{number_format($item->cal,2,'.','')}}%</td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>{{number_format($key_task->sum('weight'),2,'.','')}}%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{number_format($key_task->sum('cal'),2,'.','')}}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- OMG --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">OMG</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rule Name</th>
                            <th>Base Line</th>
                            <th>Max</th>
                            <th>Weight</th>
                            <th>Target</th>
                            <th style="width: 10%;">Actual</th>
                            <th>%Ach</th>
                            <th>%Cal</th>
                            {{-- <th>Result</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @isset($omg)
                        @foreach ($omg->values() as $key => $item)
                        <tr data-id="{{$item->id}}">
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$item->rule->name}} - {{$item->rule->calculate_type}}</td>
                            <td>{{number_format($item->base_line,2,'.','')}}</td>
                            <td>{{number_format($item->max_result,2,'.','')}}</td>
                            <td>{{number_format($item->weight,2,'.','')}}%</td>
                            <td>{{number_format($item->target,2,'.','')}}</td>
                            <td><input type="number" class="form-control form-control-sm" value="{{$item->actual}}"
                                    step="0.01" onchange="changeValue(this)"></td>
                            <td>{{number_format($item->ach,2,'.','')}}%</td>
                            <td>{{number_format($item->cal,2,'.','')}}%</td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>{{number_format($omg->sum('weight'),2,'.','')}}%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{number_format($omg->sum('cal'),2,'.','')}}%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Calculation Summary --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Calculation Summary</h5>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="mainRule">Main Rule :</label>
                            <input type="text" class="form-control form-control-sm" id="mainRule"
                                placeholder="Main Rule" value="{{$evaluate->mainRule->name}}" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="Cal">%Cal :</label>
                            <input type="text" class="form-control form-control-sm" id="Cal" placeholder="%Cal"
                                value="{{$calMainRule}}" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Weight</th>
                            <th>Total Reslt</th>
                            <th>Total Result With Main Rule</th>
                            <th>Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">KPI</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                        <tr>
                            <th scope="row">Key Task</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                        <tr>
                            <th scope="row">OMG</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Main Rule Conditions --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Main Rule Conditions</h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm">
                    <thead>
                        <tr>
                            <th>Range Name</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Condition Range 1</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                        <tr>
                            <th scope="row">Condition Range 2</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                        <tr>
                            <th scope="row">Condition Range 3</th>
                            <td>Table cell</td>
                            <td>Table cell</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading"></div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" onclick="submit()">Save</button>
            <button class="mb-2 mr-2 btn btn-success">Submit to Manager</button>
        </div>
    </div>
</div>

@endsection

@section('second-script')
<script>
    var evaluateDetail  = {!!json_encode($evaluate->evaluateDetail)!!}
    
</script>
<script src="{{asset('assets\js\kpi\evaluationSelf\evaluate.js')}}" defer></script>
<script>
    const changeValue = (e) => {
        evaluateDetail.forEach((element,index) => {
            if (parseInt(e.offsetParent.parentNode.dataset.id) === element.id) {
                // element.actual = e.value
                // element.cal = e.value
                formulaRuleDetail(e,index)
            }
        });
    }

    const submit = () => {
        console.log(evaluateDetail);
    }
</script>
@endsection