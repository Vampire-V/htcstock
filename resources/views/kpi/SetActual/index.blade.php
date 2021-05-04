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
                                <label for="Year">Target Period</label>
                                <select name="period" id="period" class="form-control-sm form-control">
                                    <option value="">Choose...</option>
                                    @isset($months)
                                    @foreach ($months as $month)
                                    <option value="{{date('m', strtotime($month->name." 1 2021"))}}" @if ($selectedPeriod===date('m', strtotime($month->name." 1 2021")))
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
                                <th>Evaluation form</th>
                                <th>Target Period</th>
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
                            @isset($evaluateDetail)
                            @foreach ($evaluateDetail as $key => $item)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$item->evaluate->user->name }} : <span
                                        class="{{Helper::kpiStatusBadge($item->evaluate->status)}}">{{$item->evaluate->status}}</span>
                                </td>
                                <td>{{$item->evaluate->targetperiod->name}} {{$item->evaluate->targetperiod->year}}</td>
                                <td class="truncate">{{$item->rule->name}}</td>
                                <td>{{number_format($item->base_line,2)}}</td>
                                <td>{{number_format($item->max_result,2)}}</td>
                                <td>{{number_format($item->weight,2)}}%</td>
                                <td>{{number_format($item->target,2)}}</td>
                                <td><input type="number" name="actual" id="{{$item->id}}" value="{{$item->actual}}"
                                        min="0" step="0.01" class="form-control form-control-sm"
                                        onchange="changeActual(this)" />
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8"></td>
                                <td><button class="mb-2 mr-2 btn btn-success btn-sm"
                                        onclick="submit(this)">Submit</button>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Button --}}
{{-- <div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-success">Save</button>
        </div>
    </div>
</div> --}}
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
<script>
    var changeActual = (e) => {
        let button = document.getElementById('table-set-actual').querySelector('button')
        if (Array.isArray(e.value.match(/\w+/))) {
            for (let index = 0; index < detail.length; index++) {
                const element = detail[index];
                if (element.id === parseInt(e.id)) {
                    element.actual = parseFloat(e.value).toFixed(2)
                    let ach = findAchValue(element)
                    let cal = findCalValue(element,ach)
                    element.ach = ach
                    element.cal = cal
                    e.parentNode.nextElementSibling.textContent =  ach.toFixed(2) + '%'
                    e.parentNode.nextElementSibling.nextElementSibling.textContent = cal.toFixed(2) + '%'
                    e.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle = changeTooltipCal(e.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle, element)
                }
            }
        }
    }

    var submit = async (e) => {
        if (detail.length > 0) {
            if (validationActual()) {
                setVisible(true)
                putSetActual(detail,detail[0].rules.user_actual.id)
                .then(res => {
                    if (res.status === 201) {
                        toast(res.data.message,res.data.status)
                    }
                })
                .catch(error => {
                    toast(error.response.data.message,error.response.data.status)
                })
                .finally(() => {
                    setVisible(false)
                    toastClear()
                })
            }else{
                toast(`Can’t contain letters`,'error')
                toastClear()
            }
        }
    }

    var validationActual = () => {
        let tBody = document.getElementById('table-set-actual').tBodies[0]
        for (let index = 0; index < tBody.rows.length; index++) {
            const element = tBody.rows[index];
            if (!Array.isArray(element.cells[8].firstChild.value.match(/\w+/))) {
                element.cells[8].firstChild.focus()
                console.log(element.cells[8].firstChild);
                return false
            }
        }
        return true
    }
</script>
@endsection