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

@isset($category)
@foreach ($category as $group)
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{$group->name}}</h5>
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
                            {{-- <th>Result</th> --}}
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
                            {{-- <th style="width: 10%;">Set Ach%</th> --}}
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
                            {{-- <td><input class="form-control form-control-sm" type="number" name="{{$item->name}}" step="0.01" min="0" onchange="changeAch(this)"></td> --}}
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
                            {{-- <td></td> --}}
                            <td></td>
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
        <div class="page-title-heading"></div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" onclick="approve(this)" >Approve</button>
            <button class="mb-2 mr-2 btn btn-warning" onclick="reject(this)" >Reject</button>
        </div>
    </div>
</div>

@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer></script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer></script>
<script defer>
    // variable
    const auth = {!!json_encode(Auth::id())!!}
    const evaluate = {!!json_encode($evaluate)!!}
</script>
<script src="{{asset('assets\js\kpi\evaluationReview\evaluate.js')}}" defer></script>
<script>
    const changeActualValue = (e) => {
        evaluateForm.detail.forEach((element,index) => {
            if (e.offsetParent.parentNode.cells[1].textContent === element.rules.name) {
                formulaRuleDetail(e,index)
            }
        });
    }

    const changeAch = (e) => {
        for (const [key, value] of Object.entries(evaluateForm)) {
            if (e.name.substr(0,3) === key.substr(4,3)) {
                evaluateForm,evaluateForm[key] = parseFloat(e.value)
            }
        }
    }

    const reject = async (e) => {
        // Save & reject
        // /kpi/evaluation-review/update
        
        const { value: text } = await Swal.fire({
            input: 'textarea',
            inputLabel: `Why evaluate reject!`,
            inputPlaceholder: 'Type your message here...',
            inputAttributes: {
                'aria-label': 'Type your message here'
            },
            showCancelButton: true
        })
        if (text) {
            evaluateForm.comment = text
            window.scrollTo(500, 0)
            setVisible(true)
            putEvaluateReview(evaluate.id,evaluateForm).then(res => {
                let status = document.getElementsByClassName('card-header')[0].querySelector('span')
                if (res.status === 200) {
                    status.textContent = res.data.data.status
                    pageDisable()
                    toast(`Evaluate-review Reject.`,'success')
                    toastClear()
                }
            })
            .catch(error => {
                toast(error.response.data.message,'error')
                toastClear()
            })
            .finally(() => setVisible(false))
        }
        
    }

    const approve = (e) => {
        evaluateForm.next = !evaluateForm.next
        // Save & approved
        // /kpi/evaluation-review/update
        window.scrollTo(500, 0)
        setVisible(true)
        console.log(evaluateForm.ach_kpi);
        debugger
        putEvaluateReview(evaluate.id,evaluateForm).then(res => {
            let status = document.getElementsByClassName('card-header')[0].querySelector('span')
            if (res.status === 200) {
                status.textContent = res.data.data.status
                pageDisable()
                toast(`evaluate-review Approved.`,'success')
                toastClear()
            }
        })
        .catch(error => {
            toast(error.response.data.message,'error')
            toastClear()
        })
        .finally( () => {
            evaluateForm.next = !evaluateForm.next
            setVisible(false)
        })
    }
</script>
@endsection