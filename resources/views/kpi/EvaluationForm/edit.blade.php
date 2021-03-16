@extends('layouts.app')
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    .select2-hidden-accessible {
        position: inherit !important;
    }

    .select2 ,.select2-containe {
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
            {{-- <div class="d-inline-block dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    class="btn-shadow dropdown-toggle btn btn-info">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-business-time fa-w-20"></i>
                    </span>
                    Buttons
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-inbox"></i>
                                <span>
                                    Inbox
                                </span>
                                <div class="ml-auto badge badge-pill badge-secondary">86</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-book"></i>
                                <span>
                                    Book
                                </span>
                                <div class="ml-auto badge badge-pill badge-danger">5</div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-link-icon lnr-picture"></i>
                                <span>
                                    Picture
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a disabled href="javascript:void(0);" class="nav-link disabled">
                                <i class="nav-link-icon lnr-file-empty"></i>
                                <span>
                                    File Disabled
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>
{{-- Display user detail --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">{{$period->name}} {{$period->year}}</h5>
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
                                required onchange="selectTemplate(this)">
                                <option value="">Choose...</option>
                                @isset($templates)
                                @foreach ($templates as $item)
                                <option value="{{$item->id}}" {{$evaluate->template_id === $item->id ? "selected" : ""}}>
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
                <h5 class="card-title">{{$group->name}}</h5>
                @if ($group->name === 'key-task')
                <div class="card-header">
                    {{-- <label for="department" class="mb-2 mr-2">Weight :</label> --}}
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            {{-- <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0" step="0.01"
                                id="weight-{{$group->name}}" name="weight_{{$group->name}}"> --}}
                        </div>
                    </div>
                    <div class="btn-actions-pane-right">
                        <div role="group" class="btn-group-sm btn-group">
                            <button class="mb-2 mr-2 btn btn-danger" onclick="deleteRuleTemp()">Delete Selected
                                Rule</button>
                            <button class="mb-2 mr-2 btn btn-primary" data-toggle="modal" data-target="#ruleModal">Add
                                new rule</button>
                        </div>
                    </div>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="mb-0 table table-sm" id="table-{{$group->name}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rule Name</th>
                                <th>Description</th>
                                <th>Base Line</th>
                                <th>Max</th>
                                <th>Weight</th>
                                <th>Target</th>
                                @if ($group->name === 'key-task')
                                <th>#</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <th scope="row"></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> --}}
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
                                onchange="selectMainRule(this)">
                                {{-- <option value="">Please select</option> --}}
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
                <table class="mb-0 table table-sm" id="table-mainrule">
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
            <button class="mb-2 mr-2 btn btn-primary" onclick="submit()">Save</button>
            <button class="mb-2 mr-2 btn btn-success" onclick="submitToUser()">Submit to staff</button>
            <button class="mb-2 mr-2 btn btn-danger">Delete</button>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="ruleModal" tabindex="-1" role="dialog" aria-labelledby="ruleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ruleModalLabel">New Rule to : Key-Task</h5>
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
                                <select id="validationRuleName" class="form-control form-control-sm" name="rule_id_add"
                                    onchange="setRuleToTemp(this)">
                                    <option value="">Choose...</option>
                                    @isset($rules)
                                    @foreach ($rules as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="newRuleToTemp()">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')
<script>
    const staff = {!!json_encode($user)!!},
            period = {!!json_encode($period)!!},
            evaluate = {!!json_encode($evaluate)!!},
            detail = {!!json_encode($evaluate->evaluateDetail)!!},
            formEvaluate = {
                template : null,
                mainRule : null,
                minone : 0,
                maxone : 0,
                mintwo : 0,
                maxtwo : 0,
                mintree : 0,
                maxtree : 0,
                total_weight_kpi : 0,
                total_weight_key_task: 0,
                total_weight_omg: 0,
                detail : [],
                next : false
            },
            className = ['form-control','form-control-sm']

</script>
<script src="{{asset('assets\js\kpi\evaluationForm\edit.js')}}" defer></script>

<script>
    const clearData = () => {
        formEvaluate.template = null
        formEvaluate.mainRule = null
        formEvaluate.minone = 0
        formEvaluate.maxone = 0
        formEvaluate.mintwo = 0
        formEvaluate.maxtwo = 0
        formEvaluate.mintree = 0
        formEvaluate.maxtree = 0
        formEvaluate.total_weight_kpi = 0
        formEvaluate.total_weight_key_task = 0
        formEvaluate.total_weight_omg = 0
        formEvaluate.detail = []
        formEvaluate.next = false

        let tables = document.getElementById("all-table").querySelectorAll('table')
        document.getElementById('mainRule').innerHTML = ''
        tables.forEach(intable => {
            intable.getElementsByTagName('tbody')[0].innerHTML = ''
            intable.getElementsByTagName('tfoot')[0].lastElementChild.cells[5].textContent = 0
        })
        let inputs = document.getElementById('table-mainrule').querySelectorAll('input')
        inputs.forEach(element => {
            element.value = 0
        });
    }

    const setData = (data) => {
        let inputs = document.getElementById('table-mainrule').querySelectorAll('input')
        if (Array.isArray(data)) {
            formEvaluate.template = null
            formEvaluate.mainRule = null
            formEvaluate.minone = 0
            formEvaluate.maxone = 0
            formEvaluate.mintwo = 0
            formEvaluate.maxtwo = 0
            formEvaluate.mintree = 0
            formEvaluate.maxtree = 0
            formEvaluate.total_weight_kpi = data.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'kpi' ? accumulator + currentValue.weight : accumulator ,0)
            formEvaluate.total_weight_key_task = data.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'key-task' ? accumulator + currentValue.weight : accumulator ,0)
            formEvaluate.total_weight_omg = data.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'omg' ? accumulator + currentValue.weight : accumulator ,0)
            formEvaluate.detail = data
            formEvaluate.next = false
            inputs.forEach(element => {
                element.value = 0
            })
        } else {
            formEvaluate.template = data.template_id
            formEvaluate.mainRule = data.main_rule_id
            formEvaluate.minone = data.main_rule_condition_1_min
            formEvaluate.maxone = data.main_rule_condition_1_max
            formEvaluate.mintwo = data.main_rule_condition_2_min
            formEvaluate.maxtwo = data.main_rule_condition_2_max
            formEvaluate.mintree = data.main_rule_condition_3_min
            formEvaluate.maxtree = data.main_rule_condition_3_max
            formEvaluate.total_weight_kpi = data.total_weight_kpi
            formEvaluate.total_weight_key_task = data.total_weight_key_task
            formEvaluate.total_weight_omg = data.total_weight_omg
            formEvaluate.detail = data.detail
            formEvaluate.next = false

            inputs.forEach(element => {
                for (const key in formEvaluate) {
                    if (Object.hasOwnProperty.call(formEvaluate, key)) {
                        const condition = formEvaluate[key];
                        if (key === element.name) {
                            element.value = condition
                        }
                    }
                }
            })
        }
        
    }

    const validityForm = () => {
        let forms = document.getElementsByClassName('app-main__inner')[0].querySelectorAll('form'), status = true
        forms.forEach(form => {
            if (!form.checkValidity()) {
                form.classList.add('was-validated')
                status = false
            }
        })
        return status
    }

    const submitToUser = () => {
        if (validityForm() && Array.isArray(formEvaluate.detail) && formEvaluate.detail.length > 0) {

            setTotalWeight()
            formEvaluate.next = true
            putEvaluate(staff.id,period.id,evaluate.id,formEvaluate).then(res => {
                if (res.status === 200) {
                    toastSuccess(`update evaluate-form : ${res.data.data.period.name} - ${res.data.data.period.year}`)
                    toastClear()
                }
            }).catch(error => {
                    console.log(error);
            }).finally(() => {
                
            })
        }
    }

    const submit = () => {
        if (validityForm() && Array.isArray(formEvaluate.detail) && formEvaluate.detail.length > 0) {

            setTotalWeight()
            console.log(formEvaluate);
            putEvaluate(staff.id,period.id,evaluate.id,formEvaluate).then(res => {
                if (res.status === 200) {
                    createRowEvaluate(res.data.data.detail)
                    setData(res.data.data)
                    setMainRule(res.data.data)
                    toastSuccess(`update evaluate-form : ${res.data.data.period.name} - ${res.data.data.period.year}`)
                    toastClear()
                }
            }).catch(error => {
                    console.log(error);
            }).finally(() => {
                
            })
        }
    }

    const setTotalWeight = () => {
        if (formEvaluate.detail.length > 0) {
            // let kpi = formEvaluate.detail.filter(value => value.rules.categorys.name === 'kpi')
            // let task = formEvaluate.detail.filter(value => value.rules.categorys.name === 'key-task')
            // let omg = formEvaluate.detail.filter(value => value.rules.categorys.name === 'omg')
            formEvaluate.total_weight_kpi = formEvaluate.detail.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'kpi' ? accumulator + currentValue.weight : accumulator ,0)
            formEvaluate.total_weight_key_task = formEvaluate.detail.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'key-task' ? accumulator + currentValue.weight : accumulator ,0)
            formEvaluate.total_weight_omg = formEvaluate.detail.reduce((accumulator, currentValue) => currentValue.rules.categorys.name === 'omg' ? accumulator + currentValue.weight : accumulator ,0)
        }
    }

    const changeValue = async (e) => {
        let object = formEvaluate.detail.find(obj => obj.rules.name === e.offsetParent.parentNode.dataset.id)
        for (const key in object) {
            object[key] = (key === e.name) ? parseFloat(e.value) : object[key]
        }
        if (e.name === 'weight') {
            let sum = formEvaluate.detail.reduce((total,cur) => {
                if (cur.rules.category_id === object.rules.category_id) {
                    return total += cur.weight
                }else{
                    return total
                }
            },0)
            // change total weight
            e.offsetParent.parentNode.parentNode.parentNode.tFoot.lastElementChild.cells[e.offsetParent.cellIndex].textContent = sum.toFixed(2)
        }
    }

    const changeValueMainRule = async (e) => {
        for (const key in formEvaluate) {
            formEvaluate[key] = (key === e.name) ? parseFloat(e.value) : formEvaluate[key]
        }
    }

    const selectTemplate = async (e) => {
        if (e.selectedIndex > 0) {
            if (parseInt(e.options[e.selectedIndex].value) === evaluate.template_id) {
                displayForEvaluate(evaluate.id)
            }else{
                displayForTemplate(e.options[e.selectedIndex].value)
            }
        }else{
            clearData()
        }
    }

    const selectMainRule = async (e) => {
        if (e.selectedIndex > 0) {
            formEvaluate.mainRule = parseInt(e.options[e.selectedIndex].value)
        }else{
            formEvaluate.mainRule = null
        }
    }
    
    const createRowEvaluate = async (data) => {
        
        let tables = document.getElementById("all-table").querySelectorAll('table')
        await tables.forEach(intable => {
            intable.getElementsByTagName('tbody')[0].innerHTML = ''
            intable.getElementsByTagName('tfoot')[0].lastElementChild.cells[5].textContent = 0
        });
        await tables.forEach(inta => {
            let newArray = data.filter(value => value.rules.categorys.name === inta.id.substring(6))
            let sumWeight = newArray.reduce((accumulator, currentValue) => accumulator + currentValue.weight,0)
            
            newArray.forEach((element, key, array) => {
                let table = document.getElementById(inta.id)
                let body = table.getElementsByTagName('tbody')[0]
                let newRow = body.insertRow()
                newRow.setAttribute("data-id", element.rules.name)

                let newCellIndex = newRow.insertCell()
                newCellIndex.textContent = key+1

                let newCellName = newRow.insertCell()
                newCellName.textContent = element.rules.name

                let newCellDescription = newRow.insertCell()
                newCellDescription.textContent = element.rules.description

                let newCellBaseLine = newRow.insertCell()
                let inputBaseLine = document.createElement(`input`)
                inputBaseLine.setAttribute(`onchange`,'changeValue(this)')
                newCellBaseLine.appendChild(createInput(inputBaseLine,'number',className,`base_line`,element.base_line.toFixed(2)))

                let newCellMax = newRow.insertCell()
                let inputMax = document.createElement(`input`)
                inputMax.setAttribute(`onchange`,'changeValue(this)')
                newCellMax.appendChild(createInput(inputMax,'number',className,`max_result`,element.max_result.toFixed(2)))

                let newCellWeight = newRow.insertCell()
                let inputWeight = document.createElement(`input`)
                inputWeight.setAttribute(`onchange`,'changeValue(this)')
                newCellWeight.appendChild(createInput(inputWeight,'number',className,`weight`,element.weight.toFixed(2)))

                let newCellTarget = newRow.insertCell()
                let inputTarget = document.createElement(`input`)
                inputTarget.setAttribute(`onchange`,'changeValue(this)')
                newCellTarget.appendChild(createInput(inputTarget,'number',className,`target`,'target_config' in element ? element.target_config.toFixed(2) : element.target.toFixed(2) ))

                if (element.rules.categorys.name === 'key-task') {
                    let newCellCheck = newRow.insertCell()
                    let div = document.createElement('div')
                    div.className = 'custom-checkbox custom-control'

                    let checkbox = document.createElement('input')
                    checkbox.type = `checkbox`
                    checkbox.name = `rule-${element.rules.name}`
                    checkbox.className = `custom-control-input`
                    checkbox.id = element.rules.name

                    let label = document.createElement('label')

                    label.classList.add('custom-control-label')
                    label.htmlFor = element.rules.name
                    div.appendChild(checkbox)
                    div.appendChild(label)
                    newCellCheck.appendChild(div)
                }
                

                if (key === array.length - 1){ 
                    let footter = table.getElementsByTagName('tfoot')[0]
                    footter.children[0].children[newCellWeight.cellIndex].textContent = sumWeight.toFixed(2)
                }
            })
        })
    }

    const deleteRuleTemp = () => {
        let table = document.getElementById(`table-key-task`),
        rows = table.tBodies[0],
        removeDetailIndex = []
        for (const row of rows.rows) {
            if (row.lastChild.lastChild.firstChild.checked) {
                let indexDetail = formEvaluate.detail.findIndex(object => object.rules.name === row.dataset.id)
                removeDetailIndex.push(indexDetail)
                // remove row in table
                rows.deleteRow(row.rowIndex - 1)
            }
        }
         // remove detail temp
        formEvaluate.detail = formEvaluate.detail.filter((value, index) => removeDetailIndex.indexOf(index) == -1)
    }

    const displayForTemplate = async (template) => {
            getRuleTemplate(template).then( res => {
                    createRowEvaluate(res.data.data)
                    setData(res.data.data)
                    setMainRule(res.data.data)
            }).catch(error => {
                console.log(error);
            }).finally(() => {
                // console.log("displayForTemplate after",formEvaluate);
            })
    }

    const displayForEvaluate = (evaluate) => {
        getEvaluate(staff.id,period.id,evaluate).then(res => {
                createRowEvaluate(res.data.data.detail)
                setData(res.data.data)
                setMainRule(res.data.data)
        }).catch(error => {
            console.log(error);
        }).finally(() => {
            // console.log("displayForEvaluate after",formEvaluate);
        })
    }

    const setMainRule = (datas) => {
        let mainRule = document.getElementById('mainRule')
        mainRule.innerHTML = ""
        if (Array.isArray(datas)) {
            mainRule.add(new Option("","",false,false))
            datas.forEach(element => {
                mainRule.add(new Option(element.rules.name,element.rules.id,false,false))
            })
        }else{
            mainRule.add(new Option("","",false,false))
            datas.detail.forEach(element => {
                mainRule.add(new Option(element.rules.name,element.rules.id,false,datas.main_rule_id === element.rules.id ? true : false))
            })
        }
        
        
    }
    
// modal method
    var ruleTemp = null

    const setRuleToTemp = e => {
        ruleTemp = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
    }
    const newRuleToTemp = () => {
        // Fetch rule API and add to detail temp
        getRule(ruleTemp).then(res => {
            let obj = res.data.data
            let ruleKPI = formEvaluate.detail.filter(value => value.rules.categorys.name === 'key-task')
            let json = {
                base_line: 0,
                field: null,
                // id: null,​​​
                max_result: 0,
                parent_rule_template_id: Math.max.apply(null,ruleKPI.map(o => o.parent_rule_template_id ))+1,
                rule_id: obj.id,
                rules: obj,
                target_config: 0,
                template_id: null,
                weight: 0,
                weight_category: 0
            }
            

            let tables = document.getElementById(`table-key-task`)
            let newRow = tables.tBodies[0].insertRow()
                newRow.setAttribute("data-id", json.rules.name)

            let newCellIndex = newRow.insertCell()
                newCellIndex.textContent = ruleKPI.length + 1

            let newCellName = newRow.insertCell()
                newCellName.textContent = json.rules.name

            let newCellDescription = newRow.insertCell()
                newCellDescription.textContent = json.rules.description

            let newCellBaseLine = newRow.insertCell()
            let inputBaseLine = document.createElement(`input`)
                inputBaseLine.setAttribute(`onchange`,'changeValue(this)')
                newCellBaseLine.appendChild(createInput(inputBaseLine,'number',className,`base_line`,json.base_line.toFixed(2)))

            let newCellMax = newRow.insertCell()
            let inputMax = document.createElement(`input`)
                inputMax.setAttribute(`onchange`,'changeValue(this)')
                newCellMax.appendChild(createInput(inputMax,'number',className,`max_result`,json.max_result.toFixed(2)))

            let newCellWeight = newRow.insertCell()
            let inputWeight = document.createElement(`input`)
                inputWeight.setAttribute(`onchange`,'changeValue(this)')
                newCellWeight.appendChild(createInput(inputWeight,'number',className,`weight`,json.weight.toFixed(2)))

            let newCellTarget = newRow.insertCell()
            let inputTarget = document.createElement(`input`)
                inputTarget.setAttribute(`onchange`,'changeValue(this)')
                newCellTarget.appendChild(createInput(inputTarget,'number',className,`target_config`,json.target_config.toFixed(2)))

            let newCellCheck = newRow.insertCell()
            let div = document.createElement('div')
                div.className = 'custom-checkbox custom-control'

            let checkbox = document.createElement('input')
                checkbox.type = `checkbox`
                checkbox.name = `rule-${json.rules.name}`
                checkbox.className = `custom-control-input`
                checkbox.id = json.rules.name

            let label = document.createElement('label')

                label.classList.add('custom-control-label')
                label.htmlFor = json.rules.name
                div.appendChild(checkbox)
                div.appendChild(label)
                
                newCellCheck.appendChild(div)
            formEvaluate.detail.push(json)
            document.getElementById('ruleModal').getElementsByClassName("close")[0].click()
        }).catch(error => {
            console.log(error)
        })
    }

    $('#ruleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        // var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        // let row = document.getElementById(`table-${group.name}`).getElementsByTagName('tbody')[0].lastChild
        // modal.find('.modal-title').text('New Rule to : ' + recipient)
        // modal.find('.modal-body input[name ="parent_rule_template_id"]').val(getLastRowNum(row))
        // modal.find('.modal-body input[name ="field"]').val(getLastRowNum(row))
        // modal.find('.modal-body input[name ="weight_category"]').val(document.getElementById(`weight-${group.name}`).value)
    })

    $('#ruleModal').on('hide.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        // var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        // modal.find('.modal-body input[name ="base_line"]').val('')
        document.getElementById('form-rule').reset()
    })
</script>
@endsection