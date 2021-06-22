@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi')
@stop
@section('style')
<style>
    .input-sm {
        height: calc(1.8125rem + -7px);
        /* width: 50%; */
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
            <div>Eddy page
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

{{-- tabs --}}
<div class="row">
    <div class="col-md-12">
        <div class="mb-3 card">
            <div class="card-body">
                <ul class="tabs-animated-shadow tabs-animated nav">
                    <li class="nav-item">
                        <a role="tab" class="nav-link " id="tab-c-0" data-toggle="tab" href="#tab-actual"
                            onclick="tabclick(this)">
                            <span>Edit Actual</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="tab" class="nav-link " id="tab-c-1" data-toggle="tab" href="#tab-ach"
                            onclick="tabclick(this)">
                            <span>Edit Ach%</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane " id="tab-actual" role="tabpanel">
                        <div class="position-relative form-group">
                            <form class="needs-validation" novalidate>
                                <div class="form-row">
                                    <div class="col-md-3 mb-2">
                                        <label for="Year">Name</label>
                                        <select name="user" id="user" class="form-control-sm form-control">
                                            <option value="">Choose...</option>
                                            @isset($users)
                                            @foreach ($users as $item)
                                            <option value="{{$item->id}}" @if ($selectedUser===$item->id)
                                                selected
                                                @endif>{{$item->name }}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="Year">Target Period</label>
                                        <select name="period[]" id="period" class="form-control-sm form-control"
                                            multiple>
                                            {{-- <option value="">Choose...</option> --}}
                                            @isset($months)
                                            @foreach ($months as $month)
                                            <option value="{{date('m', strtotime($month->name." 1 2021"))}}" @if ($selectedPeriod->contains(date('m', strtotime($month->name." 1 2021"))))
                                                selected
                                                @endif>{{$month->name}}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="Year">Year</label>
                                        <select name="year[]" id="year" class="form-control-sm form-control select-year"
                                            multiple>
                                            @foreach (range(date('Y'), $start_year) as $year)
                                            <option value="{{$year}}" @if ($selectedYear->contains($year)) selected
                                                @endif>
                                                {{$year}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="Department">Department</label>
                                        <select name="department_id[]" id="department"
                                            class="form-control-sm form-control select-dept" multiple>
                                            {{-- <option value="">Choose...</option> --}}
                                            @isset($departments)
                                            @foreach ($departments as $item)
                                            <option value="{{$item->id}}" @if ($selectedDept->contains($item->id))
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

                        <div class="table-responsive-sm">
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

                                    {{-- <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td>{{$item->evaluate->user->name }}</td>
                                        <td>{{$item->evaluate->targetperiod->name}}
                                            {{$item->evaluate->targetperiod->year}}</td>
                                        <td class="truncate" data-toggle="tooltip" data-placement="top"
                                            title="{{$item->rule->name}}">{{$item->rule->name}}</td>
                                        <td>{{number_format($item->base_line,2)}}</td>
                                        <td>{{number_format($item->max_result,2)}}</td>
                                        <td>{{number_format($item->weight,2)}}%</td>
                                        <td>{{number_format($item->target,2)}}</td>
                                        <td><input type="number" name="actual" id="{{$item->id}}"
                                                value="{{$item->actual}}" min="0" step="0.01"
                                                class="form-control form-control-sm input-sm"
                                                onchange="changeActual(this)" />
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr> --}}

                                    @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>
                                            {{-- <button class="mb-2 mr-2 btn btn-success btn-sm"
                                                onclick="submitSetActual(this)">Save</button> --}}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="tab-ach" role="tabpanel">
                        <div class="position-relative form-group">
                            <form class="needs-validation" novalidate>
                                <div class="form-row">
                                    <div class="col-md-3 mb-2">
                                        <label for="Year">Name</label>
                                        <select name="user" id="user_ach" class="form-control-sm form-control">
                                            <option value="">Choose...</option>
                                            @isset($users)
                                            @foreach ($users as $item)
                                            <option value="{{$item->id}}" @if ($selectedUser===$item->id)
                                                selected
                                                @endif>{{$item->name }}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="Year">Target Period</label>
                                        <select name="period[]" id="period_ach" class="form-control-sm form-control"
                                            multiple>
                                            {{-- <option value="">Choose...</option> --}}
                                            @isset($months)
                                            @foreach ($months as $month)
                                            <option value="{{date('m', strtotime($month->name." 1 2021"))}}" @if ($selectedPeriod->contains(date('m', strtotime($month->name." 1 2021"))))
                                                selected
                                                @endif>{{$month->name}}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label for="Year">Year</label>
                                        <select name="year[]" id="year_ach"
                                            class="form-control-sm form-control select-year" multiple>
                                            @foreach (range(date('Y'), $start_year) as $year)
                                            <option value="{{$year}}" @if ($selectedYear->contains($year)) selected
                                                @endif>
                                                {{$year}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="Department">Department</label>
                                        <select name="department_id[]" id="department_ach"
                                            class="form-control-sm form-control select-dept" multiple>
                                            {{-- <option value="">Choose...</option> --}}
                                            @isset($departments)
                                            @foreach ($departments as $item)
                                            <option value="{{$item->id}}" @if ($selectedDept->contains($item->id))
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

                        <div class="table-responsive-sm">
                            <table class="mb-0 table table-sm" id="table-set-ach">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Evaluation form</th>
                                        <th>Target Period</th>
                                        <th>Status</th>
                                        <th>KPI</th>
                                        <th style="width: 10%;">New%</th>
                                        <th>Key-Task</th>
                                        <th style="width: 10%;">New%</th>
                                        <th>OMG</th>
                                        <th style="width: 10%;">New%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($evaluate)
                                    @foreach ($evaluate as $key => $item)
                                    {{-- <tr>
                                        <th id="{{$item->id}}">{{$key+1}}</th>
                                        <td>{{$item->user->name }}</td>
                                        <td>{{$item->targetperiod->name}} - {{$item->targetperiod->year}}</td>
                                        <td><span
                                                class="{{Helper::kpiStatusBadge($item->status)}}">{{$item->status}}</span>
                                        </td>
                                        <td></td>
                                        <td><input type="number" name="kpi" value="{{$item->ach_kpi}}" min="0"
                                                step="0.01" class="form-control form-control-sm input-sm"
                                                onchange="changeTotal(this)" /></td>
                                        <td></td>
                                        <td><input type="number" name="key" value="{{$item->ach_key_task}}" min="0"
                                                step="0.01" class="form-control form-control-sm input-sm"
                                                onchange="changeTotal(this)" /></td>
                                        <td></td>
                                        <td><input type="number" name="omg" value="{{$item->ach_omg}}" min="0"
                                                step="0.01" class="form-control form-control-sm input-sm"
                                                onchange="changeTotal(this)" /></td>
                                    </tr> --}}
                                    @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>
                                            {{-- <button class="mb-2 mr-2 btn btn-success btn-sm"
                                                onclick="submitSetAch(this)">Save</button> --}}
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
    </div>
</div>



@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>
    // variable
    const evaluate = {!!json_encode($evaluate)!!}
    const detail = {!!json_encode($evaluateDetail)!!}
    
    const auth = {!!json_encode(Auth()->id())!!}
    // var all_data = [];
</script>
<script src="{{asset('assets\js\kpi\eddy\index.js')}}" defer></script>
<script>
    var tabclick = (e) => {
        // console.log(e);
        window.localStorage.setItem('tab-active',e.id)
        // console.log(localStorage.getItem('tab-active'));
    }
    // Actual
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

    var submitSetActual = (e) => {
        if (detail.length > 0) {
            if (validationActual()) {
                // window.scroll({top: 0, behavior: "smooth"})
                setVisible(true)
                putSetActualForEddy(detail,auth)
                .then(res => {
                    if (res.data.status) {
                        toast(`Set Actual Success!`,'success')
                    }else{
                        toast(`Set Actual Fail!`,'error')
                    }
                })
                .catch(error => {
                    console.log(error);
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
                return false
            }
        }
        return true
    }

// Ach
    var changeTotal = (e) => {
        if (Array.isArray(e.value.match(/\w+/))) {
            let obj = evaluate.find(value => value.id === parseInt(e.parentNode.parentNode.cells[0].id))
            obj.ach_kpi = e.name === `kpi` ? parseFloat(e.value) : obj.ach_kpi
            obj.ach_key_task = e.name === `key` ? parseFloat(e.value) : obj.ach_key_task
            obj.ach_omg = e.name === `omg` ? parseFloat(e.value) : obj.ach_omg
        }else{
            toast(`Can’t contain letters`,'error')
            toastClear()
        }
    }

    var validationAch = () => {
        let tBody = document.getElementById('table-set-ach').tBodies[0]
        for (let index = 0; index < tBody.rows.length; index++) {
            const element = tBody.rows[index];
            if (!Array.isArray(element.cells[5].firstChild.value.match(/\w+/))) {
                element.cells[5].firstChild.focus()
                return true
            }
            if (!Array.isArray(element.cells[7].firstChild.value.match(/\w+/))) {
                element.cells[7].firstChild.focus()
                return true
            }
            if (!Array.isArray(element.cells[9].firstChild.value.match(/\w+/))) {
                element.cells[9].firstChild.focus()
                return true
            }
        }
        return true
    }

    var submitSetAch = () => {
        if (evaluate.length > 0) {
            if (validationAch()) {
                setVisible(true)
                putSetAchForEddy(evaluate,auth)
                .then(res => {
                    if (res.data.status) {
                        toast(`Set Value Success!`,'success')
                    }else{
                        toast(`Set Value Fail!`,'error')
                    }
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    setVisible(false)
                    toastClear()
                })
            } else{
                toast(`Can’t contain letters`,'error')
                toastClear()
            }
        }
    }
</script>
@endsection