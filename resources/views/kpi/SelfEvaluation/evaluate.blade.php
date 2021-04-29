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
            <div>Self Evaluate
                <div class="page-title-subheading">This is an example self evaluate created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="top"
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
                        <h5>status : <span class="badge badge-info">{{$evaluate->status}}</span></h5>
                    </div>
                </div>
            </div>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$evaluate->user->{'name_'.app()->getLocale()} }}" readonly>
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

@isset($category)
@foreach ($category as $group)
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{$group->name}}
            </h5>
            <div class="table-responsive">
                <table class="mb-0 table table-sm" id="table-{{$group->name}}">
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

                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>Total :</td>
                            <td>%</td>
                            <td></td>
                            <td></td>
                            <td>%</td>
                            <td>%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endisset




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
                                placeholder="Main Rule" value="" readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="Cal">%Cal :</label>
                            <input type="text" class="form-control form-control-sm" id="Cal" placeholder="%Cal" value=""
                                readonly>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="mb-0 table table-sm" id="table-calculation">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Weight</th>
                            <th>%Ach</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($category)
                        @foreach ($category as $item)
                        <tr>
                            <th>{{$item->name}}</th>
                            <td>0.00%</td>
                            <td>0.00%</td>
                            <td>0.00%</td>
                        </tr>
                        @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                        <tr>
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td>%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Main Rule Conditions --}}
{{-- <div class="col-lg-12">
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
</div> --}}
{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading"></div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" onclick="submit()">Save</button>
            <button class="mb-2 mr-2 btn btn-success" onclick="submitToManager(this)">Submit to Manager</button>
        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script>
</script>
<script defer>
    // variable
    const auth = {!!json_encode(Auth::id())!!}
    const evaluate = {!!json_encode($evaluate)!!}
    
</script>
<script src="{{asset('assets\js\kpi\evaluationSelf\evaluate.js')}}" defer></script>
<script>
    const changeActualValue = (e) => {
        evaluateForm.detail.forEach((element,index) => {
            if (e.offsetParent.parentNode.cells[1].textContent === element.rules.name) {
                formulaRuleDetail(e,index)
            }
        });
    }

    const submit = () => {
        // window.scroll({top: 0, behavior: "smooth"})
        setVisible(true)
        putEvaluateSelf(evaluate.id,evaluateForm).then(res => {
            if (res.status === 200) {
                status.textContent = res.data.data.status
                toast(`Save evaluate-form.`,'success')
            }
        })
        .catch(error => {
            toast(error.response.data.message,'error')
            console.log(error.response.data.message)
        })
        .finally( () => {
            setVisible(false)
            toastClear()
        })
    }

    const submitToManager = (e) => {
        evaluateForm.next = !evaluateForm.next
        // window.scroll({top: 0, behavior: "smooth"})
        setVisible(true)
        // Save & send to manager 
        putEvaluateSelf(evaluate.id,evaluateForm).then(res => {
            let label_status = document.getElementsByClassName('card-header')[0].querySelector('span')
            if (res.status === 200) {
                label_status.textContent = res.data.data.status
                if (res.data.data.status === status.SUBMITTED) {
                    pageDisable()
                }
                toast(`Sent evaluate-form To Manager.`,'success')
            }
        })
        .catch(error => {
            toast(error.response.data.message,'error')
            console.log(error.response.data.message)
        })
        .finally( () => {
            evaluateForm.next = !evaluateForm.next
            setVisible(false)
            toastClear()
        })
    }
</script>
@endsection