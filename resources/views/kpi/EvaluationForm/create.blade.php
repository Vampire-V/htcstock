@extends('layouts.app')
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    .select2-hidden-accessible {
        position: inherit !important;
    }

    .select2,
    .select2-containe {
        display: inherit !important;
    }
</style>
@endsection
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
                <h5 class="card-title">{{$period->name}} {{$period->year}}</h5>
                <div class="btn-actions-pane">
                    <div role="group" class="btn-group-sm btn-group">
                    </div>
                </div>
                <div class="btn-actions-pane-right">
                    <div role="group" class="btn-group-sm btn-group">
                        <h5>Status <span class="badge badge-info"></span></h5>
                    </div>
                </div>
            </div>
            <div class="position-relative form-group">
                <form class="needs-validation" novalidate>
                    <div class="form-row">
                        <div class="col-md-3 mb-3">
                            <label for="staffName">Staff Name</label>
                            <input type="text" class="form-control form-control-sm" id="staffName"
                                placeholder="Staff Name" value="{{$user->name}}" disabled>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                            <div class="invalid-feedback">
                                Please choose a Staff Name.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Department">Department</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="Department"
                                    value="{{$user->department->name}}" placeholder="Department"
                                    aria-describedby="inputGroupPrepend" disabled>
                                <div class="invalid-feedback">
                                    Please choose a Department.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="Position">Position</label>
                            <input type="text" class="form-control form-control-sm" id="Position" placeholder="Position"
                                value="{{$user->positions->name}}" disabled>
                            <div class="invalid-feedback">
                                Please provide a valid Position.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="template">Template</label>
                            <select id="validationTemplate" class="form-control-sm form-control" name="template_id"
                                required onchange="changeTemplate(this)">
                                <option value="">Choose...</option>
                                @isset($templates)
                                @foreach ($templates as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Template.
                            </div>
                            <div class="valid-feedback">
                                Looks good!
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
<div id="all-table">
    @foreach ($category as $group)
    <div class="col-lg-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="card-header">
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            <h5 class="card-title">{{$group->name}}</h5>
                        </div>
                    </div>
                    <div class="btn-actions-pane-right">
                        <div role="group" class="btn-group-sm btn-group">
                            @if ($group->name === 'key-task')
                            <button class="mb-2 mr-2 btn btn-danger" id="rule-remove-modal"
                                onclick="deleteRuleTemp(this)" disabled>Delete Selected
                                Rule</button>
                            <button class="mb-2 mr-2 btn btn-primary" data-group="{{$group}}" data-toggle="modal"
                                data-target="#rule-modal" id="rule-add-modal" disabled>Add
                                New Rule</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="mb-0 table table-sm" id="table-{{$group->name}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rule Name</th>
                                <th>Description</th>
                                <th>Base Line</th>
                                <th>Max</th>
                                <th>Weight %</th>
                                <th>Target</th>
                                @if ($group->name === 'key-task')
                                <th>#</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right;">Total Weight :</td>
                                <td>0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endisset

{{-- Main Rule Config --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Main Rule Config</h5>
            <div class="position-relative form-group">
                <form class="needs-validation " novalidate>
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="mainRule">Main Rule :</label>
                            <select id="mainRule" class="form-control-sm form-control" name="rule_id" required
                                onchange="changeMainRule(this)">

                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid Main Rule.
                            </div>
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
                            <th>Range Name</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Condition Range 1</th>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="minone" name="minone" value="0" onchange="changeValueMainRule(this)"></td>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="maxone" name="maxone" value="0" onchange="changeValueMainRule(this)"></td>
                        </tr>
                        <tr>
                            <th scope="row">Condition Range 2</th>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="mintwo" name="mintwo" value="0" onchange="changeValueMainRule(this)"></td>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="maxtwo" name="maxtwo" value="0" onchange="changeValueMainRule(this)"></td>
                        </tr>
                        <tr>
                            <th scope="row">Condition Range 3</th>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="mintree" name="mintree" value="0" onchange="changeValueMainRule(this)"></td>
                            <td><input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="1"
                                    id="maxtree" name="maxtree" value="0" onchange="changeValueMainRule(this)"></td>
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
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            <button class="mb-2 mr-2 btn btn-primary" id="submit" onclick="submit()" disabled>Save</button>
            <button class="mb-2 mr-2 btn btn-success" id="submit-to-user" onclick="submitToUser()" disabled>Submit to
                staff</button>
            {{-- <button class="mb-2 mr-2 btn btn-danger">Delete</button> --}}
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="rule-modal" tabindex="-1" role="dialog" aria-labelledby="rule-modal-label"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rule-modal-label">New Rule to : Key-Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-rule">
                    <input type="hidden" name="parent_rule_template_id" value="">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="rule-name" class="">Rule Name
                                    :</label>
                                <select id="rule-name" class="form-control form-control-sm" name="rule_id_add">
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addKeyTask(this)">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script src="{{asset('assets\js\index.js')}}" defer>
    // all method 
</script>
<script src="{{asset('assets\js\kpi\index.js')}}" defer>
    // all method KPI 
</script>
<script defer>
    // variable
    const  staff = {!!json_encode($user)!!}, period = {!!json_encode($period)!!}
</script>
<script src="{{asset('assets\js\kpi\evaluationForm\create.js')}}" defer>
    // new form object evaluateForm
</script>

<script>
    // dropdown
    const changeTemplate = (e) => {
        evaluateForm.template = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
        if (evaluateForm.template) {
            setVisible(true);
            getRuleTemplate(evaluateForm.template)
            .then( res => {
                if (res.status === 200) {
                    let rule_temp = res.data.data
                    displayDetail(setDetail(rule_temp))
                }
            })
            .catch(error => {
                toast(error.response.data.message,'error')
                toastClear()
                console.log(error.response.data)
            })
            .finally(() => {
                pageEnable()
                setVisible(false);
                console.log(evaluateForm)
            })
        }
        else{
            displayDetail([])
            pageDisable(`button,input`)
        }
    }

    const changeMainRule = (e) => {
        evaluateForm.mainRule = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
    }

    const submitToUser = () => {
        validityForm()
        if (evaluateForm.template && evaluateForm.mainRule) {
            setVisible(true)
            evaluateForm.next = true
            // window.scroll({top: 0, behavior: "smooth"})
            postEvaluateForm(staff.id,period.id,evaluateForm).then(res => {
                if (res.status === 201) {
                    toast(`create evaluate-form : ${res.data.data.period.name} - ${res.data.data.period.year}`,'success')
                    toastClear()
                    setTimeout(function(){ 
                        window.location.replace(`${origin}${window.location.pathname.replace("create",res.data.data.id)}/edit`)
                    }, 3000)
                }
            }).catch(error => {
                toast(error.response.data.message,'error')
                toastClear()
                console.log(error.response.data)
            }).finally(() => {
                setVisible(false)
                evaluateForm.next = false
            })
        }
    }

    const submit = () => {
        validityForm()
        if (evaluateForm.template && evaluateForm.mainRule) {
            setVisible(true)
            // window.scroll({top: 0, behavior: "smooth"})
            postEvaluateForm(staff.id,period.id,evaluateForm).then( async res => {
                if (res.status === 201) {
                    toast(`create evaluate-form : ${res.data.data.period.name} - ${res.data.data.period.year}`,'success')
                    toastClear()
                    setTimeout(function(){ 
                        window.location.replace(`${origin}${window.location.pathname.replace("create",res.data.data.id)}/edit`)
                    }, 3000)
                }
            }).catch(error => {
                console.log(error.response.data)
                toast(error.response.data.message,'error')
                toastClear()
            }).finally(() => {
                setVisible(false)
            })
        }
    }

    const deleteRuleTemp = (e) => {
        let table = e.offsetParent.offsetParent.querySelector('table'), body = table.tBodies[0]
        removeDetailIndex = []
        for (let index = 0; index < body.rows.length; index++) {
            const element = body.rows[index].lastChild.lastChild.firstChild
            if (element.checked) {
                let indexDetail = evaluateForm.detail.findIndex(object => object.rule_id === parseInt(element.id))
                removeDetailIndex.push(indexDetail)
            }
        }
        // remove detail temp
        evaluateForm.detail = evaluateForm.detail.filter((value, index) => removeDetailIndex.indexOf(index) == -1)
        displayDetail(evaluateForm)
    }
    
    // modal method

    $('#rule-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        // fetch rules filter
        dropdownRule(group,modal)
    })

    $('#rule-modal').on('hide.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var modal = $(this)
        // var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        removeAllChildNodes(modal.find('.modal-body #rule-name')[0])
    })

    const dropdownRule = (category,modal) => {
        let select = modal.find('.modal-body #rule-name')[0]
        let rule_keytask = evaluateForm.detail.filter(value => value.rules.category_id === category.id)
        getRuleDropdown(category)
        .then(res => {
            if (res.status === 200) {
                let rules = res.data.data.filter(obj => rule_keytask.some( r => r.rule_id === obj.id) ? null : obj)
                select.add(new Option('', '', false, false))
                for (let index = 0; index < rules.length; index++) {
                    const element = rules[index];
                    select.add(new Option(element.name, element.id, false, false))
                }
            }
        })
        .catch(error => {
            console.log(error.response.data);
            toast(error.response.data.message,'error')
            toastClear() 
        })
        .finally()
    }

    const addKeyTask = (e) => {
        let select = e.offsetParent.querySelector('select')
        // Fetch rule API and add to detail temp
        getRule(select.options[select.selectedIndex].value)
        .then(res => {
            let row = evaluateForm.detail.find(obj => obj.rules.category_id === res.data.data.category_id)
            let detail = new EvaluateDetail()
            detail.rule_id = res.data.data.id
            detail.rules = Object.create(res.data.data)
            detail.target = row.target
            detail.max = row.max
            detail.weight = row.weight
            detail.weight_category = row.weight_category
            detail.base_line = row.base_line
            evaluateForm.detail.push(detail)
            e.offsetParent.querySelector('.close').click()
        })
        .catch(error => {
            console.log(error.response.data)
        })
        .finally(() => {
            displayDetail(evaluateForm)
        })
        
    }
</script>
@endsection