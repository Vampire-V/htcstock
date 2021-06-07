(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationTemplate").select2({
            placeholder: 'Select Template...',
            allowClear: true
        });

        $("#rule-name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#rule-modal')
        });
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();
var evaluateForm = new EvaluateForm()

const changeValue = (e) => {
    
    let object = evaluateForm.detail.find(obj => obj.rules.name === e.offsetParent.parentNode.cells[1].textContent)
    for (const key in object) {
        object[key] = key === e.name ? parseFloat(e.value) : object[key]
        if (object.rules.parent) {
            console.log(object.rules.parent);
        }
    }
    let table = e.offsetParent.offsetParent
    let sum = evaluateForm.detail.reduce((total, cur) => cur.rules.category_id === object.rules.category_id ? total += cur.weight : total, 0.00)
    e.offsetParent.parentNode.parentNode.parentNode.tFoot.lastElementChild.cells[5].textContent = `${sum.toFixed(2)}%`

    evaluateForm.total_weight_kpi = table.id.substring(6) === 'kpi' ? sum : evaluateForm.total_weight_kpi
    evaluateForm.total_weight_key_task = table.id.substring(6) === 'key-task' ? sum : evaluateForm.total_weight_key_task
    evaluateForm.total_weight_omg = table.id.substring(6) === 'omg' ? sum : evaluateForm.total_weight_omg
}

// dropdown
const changeTemplate = (e) => {
    evaluateForm.template = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
    if (evaluateForm.template) {
        setVisible(true)
        getRuleTemplate(evaluateForm.template)
            .then(res => {
                if (res.status === 200) {
                    evaluateForm.detail = evaluateForm.detail.length > 0 ? [] : evaluateForm.detail
                    res.data.data.forEach(element => {
                        let detail = new EvaluateDetail()
                        detail.evaluate_id = typeof element.evaluate_id === 'undefined' ? null : element.evaluate_id
                        detail.rule_id = element.rule_id
                        detail.rules = Object.create(element.rules)
                        detail.target = typeof element.target === 'undefined' ? element.target_config : element.target
                        detail.target_pc = findTargetPercent(element,res.data.data).toFixed(2)
                        detail.actual = typeof element.actual === 'undefined' ? 0.00 : element.actual
                        detail.max = element.max_result
                        detail.weight = element.weight
                        detail.weight_category = element.weight_category
                        detail.base_line = element.base_line
                        evaluateForm.detail.push(detail)
                        // console.log(detail.target_pc);
                    })
                }
            })
            .catch(error => {
                toast(error.response.data.message, 'error')
                toastClear()
                console.log(error.response.data)
            })
            .finally(() => {
                pageEnable()
                display_template()
                setVisible(false)
            })
    } else {
        display_template()
        pageDisable(`button,input`)
    }
}

const submitToUser = () => {
    validityForm()
    if (evaluateForm.template) {
        setVisible(true)
        evaluateForm.next = true
        postEvaluateForm(staff.id, period.id, evaluateForm).then(res => {
            if (res.status === 201) {
                toast(res.data.message, res.data.status)
                setTimeout(function () {
                    window.location.replace(`${origin}${window.location.pathname.replace("create",res.data.data.id)}/edit`)
                }, 3000)
            }
        }).catch(error => {
            toast(error.response.data.message, error.response.data.status)
        }).finally(() => {
            setVisible(false)
            evaluateForm.next = false
            toastClear()
        })
    }
}

const submit = () => {
    validityForm()
    if (evaluateForm.template) {
        setVisible(true)
        postEvaluateForm(staff.id, period.id, evaluateForm).then(async res => {
            console.log(res);
            if (res.status === 201) {
                toast(res.data.message, res.data.status)
                setTimeout(function () {
                    window.location.replace(`${origin}${window.location.pathname.replace("create",res.data.data.id)}/edit`)
                }, 3000)
            }
        }).catch(error => {
            toast(error.response.data.message, error.response.data.status)
        }).finally(() => {
            setVisible(false)
            toastClear()
        })
    }
}

const deleteRuleTemp = (e) => {
    let table = e.offsetParent.offsetParent.querySelector('table'),
        body = table.tBodies[0]
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
    display_template()
}

// modal method

$('#rule-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    dropdownRule(group, modal)
})

$('#rule-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #rule-name')[0])
})

const dropdownRule = (category, modal) => {
    let select = modal.find('.modal-body #rule-name')[0]
    let rule_keytask = evaluateForm.detail.filter(value => value.rules.category_id === category.id)
    getRuleDropdown(category)
        .then(res => {
            if (res.status === 200) {
                let rules = res.data.data.filter(obj => rule_keytask.some(r => r.rule_id === obj.id) ? null : obj)
                for (let index = 0; index < rules.length; index++) {
                    const element = rules[index];
                    select.add(new Option(element.name, element.id, false, false))
                }
            }
        })
        .catch(error => {
            console.log(error.response.data);
            toast(error.response.data.message, 'error')
            toastClear()
        })
        .finally()
}

const addKeyTask = (e) => {
    let select = e.offsetParent.querySelector('select')
    // Fetch rule API and add to detail temp
    getRule(select.options[select.selectedIndex].value)
        .then(res => {
            if (res.status === 200) {
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
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            display_template()
            toastClear()
        })
}

const display_template = () => {
    let tables = document.getElementById('all-table').querySelectorAll('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let data_category = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))
        if (data_category.length > 0) {
            removeAllChildNodes(table.tBodies[0])
            for (let index = 0; index < data_category.length; index++) {
                const element = data_category[index]
                let newRow = table.tBodies[0].insertRow()

                let cellIndex = newRow.insertCell()
                cellIndex.textContent = index + 1

                let cellName = newRow.insertCell()
                cellName.textContent = element.rules.name
                cellName.classList.add('truncate')
                setAttributes(cellName, {
                    "data-toggle": "tooltip",
                    "title": `${element.rules.name}`,
                    "data-placement": "top"
                })

                let cellDesc = newRow.insertCell()
                cellDesc.textContent = element.rules.description
                cellDesc.classList.add('truncate')
                setAttributes(cellDesc, {
                    "data-toggle": "tooltip",
                    "title": `${element.rules.description}`,
                    "data-placement": "top"
                })


                let cellBase_line = newRow.insertCell()
                cellBase_line.appendChild(newInput('number', className, 'base_line', element.base_line.toFixed(2), '', `changeValue(this)`))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max.toFixed(2), '', `changeValue(this)`))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight.toFixed(2), '', `changeValue(this)`))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target.toFixed(2), '', `changeValue(this)`))

                let cellTargetPC = newRow.insertCell()
                // console.log(element);
                cellTargetPC.textContent = element.target_pc

                if (table.id.substring(6) === `key-task`) {
                    let cellDelete = newRow.insertCell()
                    let div = document.createElement('div')
                    div.className = 'custom-checkbox custom-control'

                    let checkbox = newInput('checkbox', 'custom-control-input', `check${element.rule_id}`, '', element.rule_id)

                    let label = document.createElement('label')
                    label.classList.add('custom-control-label')
                    label.htmlFor = element.rule_id
                    div.appendChild(checkbox)
                    div.appendChild(label)
                    cellDelete.appendChild(div)
                }
            }
            let sum_weight = data_category.reduce((total, cur) => total += cur.weight, 0.00)
            evaluateForm.total_weight_kpi = table.id.substring(6) === 'kpi' ? sum_weight : evaluateForm.total_weight_kpi
            evaluateForm.total_weight_key_task = table.id.substring(6) === 'key-task' ? sum_weight : evaluateForm.total_weight_key_task
            evaluateForm.total_weight_omg = table.id.substring(6) === 'omg' ? sum_weight : evaluateForm.total_weight_omg
            table.tFoot.lastElementChild.cells[5].textContent = `${sum_weight.toFixed(2)}%`
            table.offsetParent.querySelector('.card-title').textContent = `${data_category[0].rules.categorys.name} : ${data_category[0].weight_category}%`
        } else {
            evaluateForm.total_weight_kpi = table.id.substring(6) === 'kpi' ? 0.00 : evaluateForm.total_weight_kpi
            evaluateForm.total_weight_key_task = table.id.substring(6) === 'key-task' ? 0.00 : evaluateForm.total_weight_key_task
            evaluateForm.total_weight_omg = table.id.substring(6) === 'omg' ? 0.00 : evaluateForm.total_weight_omg
            table.tFoot.lastElementChild.cells[5].textContent = `0.00%`
            removeAllChildNodes(table.tBodies[0])
            table.offsetParent.querySelector('.card-title').textContent = table.id.substring(6)
        }
    }
    $('[data-toggle="tooltip"]').tooltip()
}
