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
            <div>Set Target
                <div class="page-title-subheading">This is an example set target created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            {{-- <div class="d-inline-block dropdown">
                <a href="#" class="btn-shadow btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Create
                </a>
            </div> --}}
        </div>
    </div>
</div>
{{-- end title  --}}

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Set Actual for : {{Auth::user()->name}}</h5>
            <div class="position-relative form-group">
                {{-- <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="ruleName">Rule Name :</label>
                            <input type="text" class="form-control form-control-sm" id="ruleName"
                                placeholder="Rule Name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="department">Department :</label>
                            <select id="validationDepartment" class="form-control-sm form-control">
                                <option value="">Rule Category</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-1">
                            <label for="year">Year :</label>
                            <select id="validationYear" class="form-control-sm form-control">
                                @foreach (range(date('Y'),$start_year) as $year)
                                <option value="">{{$year}}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-1 mb-1">
                <label for="period">Period :</label>
                <select id="validationPeriod" class="form-control-sm form-control">
                    @foreach (range(1,12) as $month)
                    <option value="{{date('m',mktime(0, 0, 0, $month, 1, 2011))}}">
                        {{date('F',mktime(0, 0, 0, $month, 1, 2011))}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <button class="mb-2 mr-2 btn btn-primary mt-4">Search</button>
            </div>
        </div>
        </form> --}}
    </div>
</div>
</div>
</div>

<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Set Actual List</h5>
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
                            <td>{{$item->evaluate->user->name}}</td>
                            <td>{{$item->evaluate->targetperiod->name}} {{$item->evaluate->targetperiod->year}}</td>
                            <td>{{$item->rule->name}}</td>
                            <td>{{number_format($item->base_line,2)}}</td>
                            <td>{{number_format($item->max_result,2)}}</td>
                            <td>{{number_format($item->weight,2)}}%</td>
                            <td>{{number_format($item->target,2)}}</td>
                            <td><input type="number" name="actual" id="{{$item->id}}" value="{{$item->actual}}" min="0"
                                    step="0.01" class="form-control form-control-sm" onchange="changeActual(this)" />
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
                            <td><button class="mb-2 mr-2 btn btn-success btn-sm" onclick="submit(this)">Submit</button>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            {{-- <button class="mb-2 mr-2 btn btn-success">Save</button> --}}
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
        if (validationActual()) {
            setVisible(true)
            putSetActual(detail,detail[0].rules.user_actual.id)
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