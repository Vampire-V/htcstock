(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents

        $("#validationRuleName").select2({
            dropdownParent: $('#modal-add-rule'),
            placeholder: 'Select Rules',
            allowClear: true
        });
        getRuleTemplate(template.id)
            .then(res => {
                let kpi = document.getElementById('weight-kpi')
                kpi.value = template.weight_kpi.toFixed(2)
                let key_task = document.getElementById('weight-key-task')
                key_task.value = template.weight_key_task.toFixed(2)
                let omg = document.getElementById('weight-omg')
                omg.value = template.weight_omg.toFixed(2)

                let btn_add_kpi = kpi.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children
                turnOnAddRule(kpi.value, btn_add_kpi[1])
                let btn_add_key_task = key_task.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children
                turnOnAddRule(key_task.value, btn_add_key_task[1])
                let btn_add_omg = omg.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children
                turnOnAddRule(omg.value, btn_add_omg[1])
                temp_rules = res.data.data
                if (temp_rules.length > 0) {
                    createRow(temp_rules)
                }
            })

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
        console.log('load...');
    }, false);
})();


function formSubmit() {
    document.getElementById('form-template').submit()
}

const createRow = async (data) => {
    let tables = document.getElementById("all-table").querySelectorAll('table')
    await tables.forEach(intable => {
        intable.getElementsByTagName('tbody')[0].innerHTML = ''
        intable.getElementsByTagName('tfoot')[0].lastElementChild.cells[6].textContent = 0
    });
    await tables.forEach(intable => {
        let newArray = data.filter(value => value.rules.categorys.name === intable.id.substring(6))
        let sumWeight = newArray.reduce((accumulator, currentValue) => accumulator + currentValue.weight, 0)
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
            checkbox.setAttribute('onclick', 'turnOnDeleteRule(this)')

            let label = document.createElement('label')

            label.classList.add('custom-control-label')
            label.htmlFor = element.id
            div.appendChild(checkbox)
            div.appendChild(label)
            newCellCheck.appendChild(div)

            let newCellName = newRow.insertCell()
            newCellName.textContent = element.rules.name

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
            newCellParentRule.appendChild(makeOption(element, key, array))

            if (key === array.length - 1) {
                let footter = table.getElementsByTagName('tfoot')[0]
                let config_weight = document.getElementById(`weight-${element.rules.categorys.name}`)
                let btn_header = config_weight.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children

                turnOnAddRule(config_weight.value, btn_header[1])
                footter.children[0].children[newCellWeight.cellIndex].textContent = sumWeight.toFixed(2) + '%'
                // config_weight.value = element.weight_category.toFixed(2)
            }
        })
    })
}

const turnOnAddRule = (weight, btn) => {
    btn.disabled = parseFloat(weight) > 0.00 ? false : true
}
const changeWeight = (sel) => {
    let btn_header = sel.offsetParent.parentElement.parentElement.lastElementChild.firstElementChild.children;
    turnOnAddRule(sel.valueAsNumber, btn_header[1])
    let form = {
        kpi: template.weight_kpi,
        key_task: template.weight_key_task,
        omg: template.weight_omg
    }
    switch (sel.id) {
        case 'weight-kpi':
            form.kpi = parseFloat(sel.value)
            break;
        case 'weight-key-task':
            form.key_task = parseFloat(sel.value)
            break;
        case 'weight-omg':
            form.omg = parseFloat(sel.value)
            break;
        default:
            break;
    }
    putTemplate(form, template.id)
        .then(res => {
            toast(res.data.message, res.data.status)
        })
        .catch(error => {
            console.log(error.response.data)
            toast(error.response.data, res.data.status)
        })
        .finally(() => {
            toastClear()
        })
}


// Modal

$('#modal-add-rule').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    arr = rules.filter((value, index) => {
        let rre = true
        temp_rules.forEach(temp_rule => {
            if (temp_rule.rules.id === value.id) {
                rre = !rre;
            }
        })
        return rre
    })
    let rules_group = arr.filter(rule => rule.category_id === group.id)
    let select_rule = modal.find('.modal-body select[id="validationRuleName"]')[0]
    rules_group.forEach((rule, key) => {
        select_rule[key] = new Option(rule.name, rule.id)
    })
    modal.find('.modal-body input[name ="base_line"]').val(rules_group.find(item => item.id == select_rule.selectedOptions[select_rule.selectedIndex].value).base_line)
    modal.find('.modal-body input[name ="max_result"]').val(rules_group.find(item => item.id == select_rule.selectedOptions[select_rule.selectedIndex].value).max)
    let weight = modal.find('.modal-body input[name ="weight"]')[0]
    let maxW = setMaxWeight(group)
    if (maxW <= 0) {
        weight.disabled = true
        weight.previousElementSibling.textContent = `Weight limit ${maxW}%`
        modal.find('.modal-footer')[0].lastElementChild.disabled = true
        weight.max = maxW
    } else {
        weight.disabled = false
        weight.previousElementSibling.textContent = `Weight limit ${maxW}%`
        modal.find('.modal-footer')[0].lastElementChild.disabled = false
        weight.max = maxW
    }

    let row = document.getElementById(`table-${group.name}`).getElementsByTagName('tbody')[0].lastElementChild
    modal.find('.modal-body input[name ="parent_rule_template_id"]').val(getLastRowNum(row))
    modal.find('.modal-body input[name ="weight_category"]').val(document.getElementById(`weight-${group.name}`).value)

})

$('#modal-add-rule').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    document.getElementById('validationRuleName').innerHTML = ''
    let optionD = document.createElement("option")
    optionD.text = "Choose..."
    optionD.value = ""
    document.getElementById('validationRuleName').appendChild(optionD)
    var modal = $(this)
    document.getElementById('form-ruletemplate').reset()
})

const changerule = (e) => {
    document.getElementsByName('base_line')[0].value = rules.find(item => item.id === parseInt(e.value)).base_line
    document.getElementsByName('max_result')[0].value = rules.find(item => item.id === parseInt(e.value)).max
}

const getLastRowNum = (row) => {
    console.log(row);
    return row ? row.rowIndex + 1 : 1
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
        formData.append('template_id', template.id)
        postRuleTemplate(template.id, formData)
            .then(res => {
                if (res.status === 200) {
                    temp_rules = res.data.data
                    createRow(res.data.data)
                }
            })
            .catch(error => {
                console.log(error.response.data);
                error.response.data.messages.forEach(value => {
                    toast(value, 'error')
                })
            })
            .finally(() => {
                document.getElementById('modal-add-rule').getElementsByClassName("close")[0].click()
                toastClear()
            })
    }
}

const makeOption = (obj, key, array) => {
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

const switchRow = (e, group) => {
    let formSwitch = {
        rule_template_id: e.offsetParent.parentNode.children[0].children[0].children[0].id,
        rule_to_id: e.options[e.selectedIndex].value,
        group_id: group
    }
    switRuleTemplate(template.id, formSwitch)
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
    } else {
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

const deleterule = e => {
    let table = document.getElementById(`table-${e.dataset.group}`)
    let form = {
        rule: [],
        group: {
            id: e.dataset.groupId,
            name: e.dataset.group
        }
    }
    for (const row of table.tBodies[0].rows) {
        let element = row.firstChild.firstChild.firstChild
        if (element.checked) {
            form.rule.push(element.id)
        }
    }
    deleteRuleTemplate(template.id, form)
        .then(res => {
            createRow(res.data.data)
        }).catch(error => console.log(error.response.data)).finally()
}
