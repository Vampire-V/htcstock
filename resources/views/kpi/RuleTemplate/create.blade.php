@extends('layouts.app')
@section('sidebar')
@include('includes.sidebar.kpi');
@stop
@section('style')
<style>
    .bs-example {
        margin: 20px;
    }

    /* select2 UI */
    .select2-hidden-accessible {
        position: inherit !important;
    }

    .select2,
    .select2-containe {
        display: inherit !important;
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
            <div>Rule Template Detail Management
                <div class="page-title-subheading">This is an example rule management created using
                    build-in elements and components.
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            {{-- <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom"
                class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-star"></i>
            </button> --}}
            <div class="d-inline-block dropdown">
            </div>
        </div>
    </div>
</div>
{{-- end title  --}}
<div class="col-lg-12">
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Rule Management</h5>
            <div class="position-relative form-group">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="ruleTemplateName">Rule template name :</label>
                        <input type="text" class="form-control form-control-sm" id="validationTemplate" name="name"
                            value="{{$template->name}}" placeholder="Rule template name" readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Rule template.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="department">Department :</label>
                        <input type="text" class="form-control form-control-sm" id="validationDepartment"
                            name="department" value="{{$template->department->name}}" placeholder="Rule template name"
                            readonly>
                        <div class="invalid-feedback">
                            Please provide a valid Department.
                        </div>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        {{-- <button class="mb-2 mr-2 btn btn-primary mt-4" type="submit">Save</button> --}}
                    </div>
                </div>

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
                <div class="card-header">
                    <label for="department" class="mb-2 mr-2">Weight :</label>
                    <div class="btn-actions-pane">
                        <div role="group" class="btn-group-sm btn-group">
                            <input class="mb-2 mr-2 form-control-sm form-control" type="number" min="0.00" step="0.01"
                                max="100" id="weight-{{$group->name}}" name="weight_{{$group->name}}" value="0.00"
                                onchange="changeWeight(this)">
                        </div>
                    </div>
                    <label for="department" class="mb-2 mr-2">%</label>
                    <div class="btn-actions-pane-right">
                        <div role="group" class="btn-group-sm btn-group">
                            <button class="mb-2 mr-2 btn btn-danger" onclick="deleterule(this)"
                                data-group="{{$group->name}}" data-group-id="{{$group->id}}" disabled>Delete Selected
                                Rule</button>
                            <button class="mb-2 mr-2 btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                                data-group="{{$group}}" disabled>Add new rule</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="mb-0 table table-sm" id="table-{{$group->name}}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Rule Name</th>
                                <th>Measurement</th>
                                <th>Base line</th>
                                <th>Max</th>
                                <th>Target config</th>
                                <th>Weight %</th>
                                <th>Numbers</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total Weight :</td>
                                <td></td>
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

{{-- Button --}}
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
        </div>
        <div class="page-title-actions">
            {{-- <button class="mb-2 mr-2 btn btn-success">Save</button> --}}
            {{-- <button class="mb-2 mr-2 btn btn-danger">Delete</button> --}}
            <a href="{{route('kpi.template.index')}}" class="mb-2 mr-2 btn btn-warning">Go Back</a>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Rule to : </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-ruletemplate">
                    <input type="hidden" name="parent_rule_template_id" value="">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="rule-name" class="">Rule Name :</label>

                                <select id="validationRuleName" class="form-control-sm form-control" name="rule_id"
                                    required placeholder="placeholder">
                                    <option value="">Choose...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="base-line" class="">Base line
                                    : %</label><input name="base_line" id="validationBaseLine" placeholder="BaseLine"
                                    type="number" min="0" step="0.1" class="form-control form-control-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="max-result" class="">Max
                                    : %</label>
                                <input name="max_result" id="validationMax" placeholder="Max result" type="number"
                                    min="0" step="0.1" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="target-config" class="">Target
                                    config
                                    :</label><input name="target_config" id="validationTargetConfig"
                                    placeholder="Target config" type="number" min="0" step="0.1"
                                    class="form-control form-control-sm" required></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="weight" class="">Weight limit
                                    100%:</label>
                                <input name="weight" id="validationWeight" placeholder="Weight" type="number" min="0"
                                    step="0.1" max="100" class="form-control form-control-sm" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="weight-category" class="">Weight
                                    category
                                    : %</label><input name="weight_category" id="validationWeightCategory"
                                    placeholder="Weight category" type="number" min="0" step="0.1"
                                    class="form-control form-control-sm" readonly></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="subMitForm()">Add</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('second-script')

<script src="{{asset('assets\js\kpi\ruleTemplate\create.js')}}" defer></script>
<script>
    var template = {!!json_encode($template)!!}
    
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        setOptionModal(group)
        let weight = modal.find('.modal-body input[name ="weight"]')[0]
        let maxW = setMaxWeight(group)
        if (maxW <= 0) {
            weight.disabled = true
            weight.previousElementSibling.textContent = `Weight limit ${maxW}%`
            modal.find('.modal-footer')[0].lastElementChild.disabled = true
            weight.max = maxW
        }else{
            weight.disabled = false
            weight.previousElementSibling.textContent = `Weight limit ${maxW}%`
            modal.find('.modal-footer')[0].lastElementChild.disabled = false
            weight.max = maxW
        }
        
        let row = document.getElementById(`table-${group.name}`).getElementsByTagName('tbody')[0].lastChild
        modal.find('.modal-body input[name ="parent_rule_template_id"]').val(getLastRowNum(row))
        modal.find('.modal-body input[name ="weight_category"]').val(document.getElementById(`weight-${group.name}`).value)
        
    })

    $('#exampleModal').on('hide.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var group = button.data('group') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        document.getElementById('validationRuleName').innerHTML=''
        let optionD = document.createElement("option")
            optionD.text = "Choose..."
            optionD.value = ""
            document.getElementById('validationRuleName').appendChild(optionD)
        var modal = $(this)
        document.getElementById('form-ruletemplate').reset()
    })

    const setOptionModal = (group) => {
        let table = document.getElementById(`table-${group.name}`)
        rows = table.tBodies[0].rows
        getRuleDropdown(group).then(result => {
            for (const row of rows) {
                for (let i = 0; i < result.data.data.length; i++) {
                    const element = result.data.data[i]
                    if (element.name === row.children[1].textContent) {
                        result.data.data.splice(i,1)
                        i--
                    }
                }
            }
            
            result.data.data.forEach(element => {
                let option = document.createElement("option")
                option.text = element.name+" - "+element.calculate_type
                option.value = element.id
                document.getElementById('validationRuleName').appendChild(option)
            })
        }).catch(error => console.log(error.response)).finally(() => {
            // console.log(datas);
        })
    }

    const changeWeight = (sel) => {
        let btn_header = sel.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children;
        turnOnAddRule(sel.valueAsNumber,btn_header[1])
    }

    const setMaxWeight = (group) => {
        let table = document.getElementById(`table-${group.name}`)
        rows = table.tBodies[0].rows
        let acc = 0
        for (const iterator of rows) {
            acc += parseInt(iterator.cells[6].textContent)
        }
        return 100 - acc
    }
    
    const subMitForm = () => {
        let form = document.getElementById('form-ruletemplate')
        if (form.checkValidity()) {
            let formData = new FormData(form)
            formData.append('template_id',template.id)
            postRuleTemplate(template.id,formData).then(res => {
                createRow(res.data.data)
            }).catch(error => {
                error.response.data.messages.forEach(value => {
                    toast(value,'error')
                })
                toastClear()
            }).finally( () => {
                document.getElementById('exampleModal').getElementsByClassName("close")[0].click()
            })
        }
    }

    const createRow = async (data) => {
        let tables = document.getElementById("all-table").querySelectorAll('table')
        await tables.forEach(intable => {
            intable.getElementsByTagName('tbody')[0].innerHTML = ''
            intable.getElementsByTagName('tfoot')[0].lastElementChild.cells[6].textContent = 0
        });
        await tables.forEach(intable => {
            let newArray = data.filter(value => value.rules.categorys.name === intable.id.substring(6))
            let sumWeight = newArray.reduce((accumulator, currentValue) => accumulator + currentValue.weight,0)
            newArray.forEach((element, key, array) => {
                let table = document.getElementById(intable.id)
                let body = table.getElementsByTagName('tbody')[0]
                let newRow = body.insertRow()
                let newCellCheck = newRow.insertCell()
                let div = document.createElement('div')

                div.className = 'custom-checkbox custom-control'

                let checkbox = document.createElement('input')

                checkbox.type = `checkbox`
                checkbox.name = `rule-${element.id}`
                checkbox.className = `custom-control-input`
                checkbox.id = element.id
                checkbox.setAttribute('onclick','turnOnDeleteRule(this)')

                let label = document.createElement('label')

                label.classList.add('custom-control-label')
                label.htmlFor = element.id
                div.appendChild(checkbox)
                div.appendChild(label)
                newCellCheck.appendChild(div)

                let newCellName = newRow.insertCell()
                newCellName.textContent = element.rules.name +" - "+element.rules.calculate_type

                let newCellMeasurement = newRow.insertCell()
                newCellMeasurement.textContent = element.rules.measurement

                let newCellBaseLine = newRow.insertCell()
                newCellBaseLine.textContent = element.base_line.toFixed(2)

                let newCellMax = newRow.insertCell()
                newCellMax.textContent = element.max_result.toFixed(2)

                let newCellTarget = newRow.insertCell()
                newCellTarget.textContent = element.target_config.toFixed(2)

                let newCellWeight = newRow.insertCell()
                newCellWeight.textContent = element.weight.toFixed(2)

                let newCellParentRule = newRow.insertCell()
                newCellParentRule.appendChild(makeOption(element,key,array))

                if (key === array.length - 1){ 
                    let footter = table.getElementsByTagName('tfoot')[0]
                    let config_weight = document.getElementById(`weight-${element.rules.categorys.name}`)
                    let btn_header = config_weight.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children

                    turnOnAddRule(element.weight_category,btn_header[1])
                    footter.children[0].children[newCellWeight.cellIndex].textContent = sumWeight.toFixed(2) + '%'
                    config_weight.value = element.weight_category.toFixed(2)
                }
            })
        })
    }

    const getLastRowNum = (row) =>  row ? row.rowIndex + 1: 1
    
    const makeOption = (obj,key,array) => {
        let select = document.createElement('select')
        select.id = `id-${obj.id}`
        select.name = `id-${obj.id}`
        select.className = `form-control form-control-sm`
        array.forEach(element => {
                let option = document.createElement('option')
                option.text = (parseInt(element.parent_rule_template_id))
                option.value = element.id
                option.defaultSelected = obj.parent_rule_template_id === element.parent_rule_template_id ? true : false
                select.appendChild(option)
        });
        select.setAttribute(`onchange`, `switchRow(this,${obj.rules.category_id})`)
        return select
    }

    const switchRow = (e,group) => {
        let formSwitch = {
            rule_template_id : e.offsetParent.parentNode.children[0].children[0].children[0].id,
            rule_to_id : e.options[e.selectedIndex].value,
            group_id : group
        }
        switRuleTemplate(template.id,formSwitch)
        .then(res => {
            createRow(res.data.data)
        })
        .catch(error => console.log(error.response.data))
    }

    const turnOnDeleteRule = (e) => {
        let btn = e.offsetParent.offsetParent.lastElementChild.children[1].lastElementChild.firstElementChild.firstElementChild
        let bodie = e.offsetParent.parentNode.parentNode.parentNode
        if (e.checked) {
            btn.disabled = e.checked ? false : true
        }else{
            if (bodie.rows.length > 1) {
                let enable = false
                for (let index = 0; index < bodie.rows.length; index++) {
                    const element = bodie.rows[index].firstChild.firstChild.firstChild;
                    enable = element.checked ? true : false
                }
                btn.disabled = enable ? false : true
            }
        }
    }

    const turnOnAddRule = (weight ,btn) => btn.disabled = weight > 0.00 ? false : true

    const deleterule = e => {
        let table = document.getElementById(`table-${e.dataset.group}`)
        let form = {
            rule : [],
            group : {
                id:e.dataset.groupId,
                name:e.dataset.group
            }
        }
        for (const row of table.tBodies[0].rows) {
            let element = row.firstChild.firstChild.firstChild
            if (element.checked) {
                form.rule.push(element.id)
            }
        }
        deleteRuleTemplate(template.id,form)
        .then(res => {
            createRow(res.data.data)
        }).catch(error => console.log(error.response.data)).finally()
    }
</script>
@endsection