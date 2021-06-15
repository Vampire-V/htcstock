(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // OverlayScrollbars(document.getElementsByClassName('table-responsive'), {});
        $("#rule_name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#rule-modal')
        })
    })

    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)

        if (evaluate) {
            evaluateForm = await setEvaluate(evaluate)
        }
        if (evaluateForm.template) {
            getTemplate(evaluateForm.template)
                .then(res => {
                    if (res.status === 200) {
                        template = res.data.data
                    }
                })
                .catch(error => {
                    toast(error.response.data.message, error.response.data.status)
                    console.log(error.response.data)
                })
                .finally(() => {
                    let temp = []
                    for (var i = 0; i < evaluateForm.detail.length; i++) {
                        let item = evaluateForm.detail[i]
                        if (temp.length < 1) {
                            item.average_actual.push(item.actual)
                            item.average_target.push(item.target)
                            temp.push(item)
                        } else {
                            let t_index = temp.findIndex(t => t.rule_id === item.rule_id)
                            if (t_index === -1) {
                                item.average_actual.push(item.actual)
                                item.average_target.push(item.target)
                                temp.push(item)
                            } else {
                                temp[t_index].actual += item.actual
                                temp[t_index].target += item.target
                                // temp[t_index].base_line += item.base_line
                                temp[t_index].weight += item.weight
                                temp[t_index].average_actual.push(item.actual)
                                temp[t_index].average_target.push(item.target)
                            }
                        }
                    }
                    // console.log(temp);
                    for (let index = 0; index < temp.length; index++) {
                        const element = temp[index]
                        // element.base_line = element.base_line / 3
                        element.weight = element.weight / 3
                        element.target = average(element.average_target)
                        element.actual = average(element.average_actual)
                    }
                    evaluateForm.detail = temp
                    render_html()
                    // if (evaluate.status !== status.READY && evaluate.status !== status.DRAFT) {
                    pageDisable()
                    // }
                })
        }
        // if (detail_quarter) {

        //     evaluateForm.detail = setEvaluate($evaluate)
        //     render_html()
        // }

        // console.log(detail_quarter);
    }, false);
})();

var evaluateForm = new EvaluateForm()
var template
var rule = []
var summary = []

var average = (number) => number.reduce((a, b) => (a + b)) / number.length
var render_html = () => {
    // create tr in table
    let tables = document.getElementById('group-table').getElementsByClassName('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let temp_rules = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))

        if (table.tBodies[0].rows.length > 0) {
            removeAllChildNodes(table.tBodies[0])
        }

        for (let index = 0; index < temp_rules.length; index++) {
            const element = temp_rules[index]
            try {
                let newRow = table.tBodies[0].insertRow()

                let cellIndex = newRow.insertCell()
                cellIndex.textContent = index + 1

                let cellName = newRow.insertCell()
                cellName.textContent = element.rules.name
                setAttributes(cellName, {
                    "data-toggle": "tooltip",
                    "title": `${element.rules.name}`,
                    "data-placement": "top"
                })
                cellName.classList.add('truncate')

                let cellDesc = newRow.insertCell()
                cellDesc.textContent = element.rules.description
                setAttributes(cellDesc, {
                    "data-toggle": "tooltip",
                    "title": `${element.rules.description}`,
                    "data-placement": "top"
                })
                cellDesc.classList.add('truncate')
                // ถ้าเป็นเจ้าของ rule หรือเป็นหน้า evaluation-review ไม่ต้อง readonly
                let readonly = auth.id === element.rules.user_actual.id || auth.roles.find(item => item.slug === `admin-kpi`) ? false : true
                // console.log(readonly);
                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line.toFixed(2), '', `changeValue(this)`, readonly))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max.toFixed(2), '', `changeValue(this)`, readonly))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight.toFixed(2), '', `changeValue(this)`, readonly))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target.toFixed(2), '', `changeValue(this)`, readonly))

                let cellTargetPC = newRow.insertCell()
                cellTargetPC.textContent = findTargetPercent(element, temp_rules).toFixed(2) + `%`

                let cellActual = newRow.insertCell()
                cellActual.appendChild(newInput('number', className, 'actual', element.actual.toFixed(2), '', `changeValue(this)`, readonly))

                let cellActualPC = newRow.insertCell()
                cellActualPC.textContent = findActualPercent(element, temp_rules).toFixed(2) + `%`

                let cellAch = newRow.insertCell()
                element.ach = findAchValue(element)
                cellAch.style = 'cursor: default;'
                setTooltipAch(cellAch, element)
                cellAch.textContent = element.ach.toFixed(2) + '%'


                let cellCal = newRow.insertCell()
                element.cal = findCalValue(element, element.ach)
                cellCal.style = 'cursor: default;'
                setTooltipCal(cellCal, element)
                cellCal.textContent = element.cal.toFixed(2) + '%'

                let cellCheck = newRow.insertCell()
                let div = document.createElement('div')
                div.className = 'custom-checkbox custom-control'

                let checkbox = document.createElement('input')
                checkbox.type = `checkbox`
                checkbox.name = `rule-${element.rule_id}`
                checkbox.className = `custom-control-input`
                checkbox.id = element.rule_id
                checkbox.setAttribute('onclick', 'selectToRemove(this)')

                let label = document.createElement('label')
                label.classList.add('custom-control-label')
                label.htmlFor = element.rule_id

                div.appendChild(checkbox)
                div.appendChild(label)
                cellCheck.appendChild(div)
            } catch (error) {
                console.log(error)
            }
        }

        let sum_weight = temp_rules.reduce((total, cur) => total += cur.weight, 0.00)
        let sum_ach = temp_rules.reduce((total, cur) => total += cur.ach, 0.00)
        let sum_cal = temp_rules.reduce((total, cur) => total += cur.cal, 0.00)
        table.tFoot.lastElementChild.cells[5].textContent = `${sum_weight.toFixed(2)}%`
        // table.tFoot.lastElementChild.cells[10].textContent = `${sum_ach.toFixed(2)}%`
        table.tFoot.lastElementChild.cells[11].textContent = `${sum_cal.toFixed(2)}%`
        summary.push({
            'weight': parseFloat(weight_quarter[i]),
            'cal': parseFloat(sum_cal),
            'category': table.id.substring(6)
        })
    }

    let summary_table = document.getElementById('table-calculation')
    let total = 0.00
    let sum_weight = 0.00
    for (let index = 0; index < summary_table.tBodies[0].rows.length; index++) {
        const row = summary_table.tBodies[0].rows[index]
        sum_weight += summary[index].weight
        total += (summary[index].cal * summary[index].weight) / 100
        row.cells[1].textContent = `${summary[index].weight.toFixed(2)} %`
        row.cells[2].textContent = `${((summary[index].cal * summary[index].weight) / 100).toFixed(2)} %`
        setAttributes(row.cells[2], {
            "data-toggle": "tooltip",
            "title": `(${summary[index].cal.toFixed(2)} * ${summary[index].weight.toFixed(2)}) / 100`,
            "data-placement": "top"
        })
    }
    summary_table.tFoot.rows[0].cells[2].textContent = `${total.toFixed(2)} %`
    summary_table.tFoot.rows[0].cells[1].textContent = `${sum_weight.toFixed(2)} %`
    $('[data-toggle="tooltip"]').tooltip()
}

const changeValue = (e) => {
    // console.log(e.parentNode.nextElementSibling)
    evaluateForm.detail.forEach((element, key) => {
        if (e.offsetParent.parentNode.cells[1].textContent === element.rules.name) {
            // create new method formula 
            element[e.name] = parseFloat(e.value)
            let rule = evaluateForm.detail[key],
                column = e.offsetParent.cellIndex,
                tr = e.offsetParent.parentNode,
                foot = tr.parentNode.parentNode.tFoot,
                sumary_index = summary.findIndex(item => item.category === rule.rules.categorys.name)

            if (rule.rules.calculate_type !== null) {
                // หา target %
                let targetPC = findTargetPercent(rule, evaluateForm.detail)
                tr.cells[7].firstChild.textContent = targetPC.toFixed(2) + '%'
                // หา actual %
                let actualPC = findActualPercent(rule, evaluateForm.detail)
                tr.cells[9].firstChild.textContent = actualPC.toFixed(2) + '%'
                // หา %Ach
                let ach = findAchValue(rule)
                tr.cells[10].firstChild.textContent = ach.toFixed(2) + '%'
                rule.ach = ach

                // หา %Cal
                let cal = findCalValue(rule, ach)
                tr.cells[11].firstChild.textContent = cal.toFixed(2) + '%'
                tr.cells[11].dataset.originalTitle = changeTooltipCal(tr.cells[column + 2].dataset.originalTitle, rule)
                rule.cal = cal

                // total %Ach & %Cal
                let sumCal = evaluateForm.detail
                    .filter(item => item.rules.categorys.name === rule.rules.categorys.name)
                    .reduce((total, currentValue) => total + currentValue.cal, 0)

                let sumAch = evaluateForm.detail
                    .filter(item => item.rules.categorys.name === rule.rules.categorys.name)
                    .reduce((total, currentValue) => total + currentValue.ach, 0)

                // foot.lastElementChild.cells[10].textContent = parseFloat(sumAch).toFixed(2) + '%'
                foot.lastElementChild.cells[11].textContent = parseFloat(sumCal).toFixed(2) + '%'
                summary[sumary_index].cal = parseFloat(sumCal)


                /**  table-calculation */
                let table = document.getElementById('table-calculation')
                let total = 0.00
                let sum_weight = 0.00

                for (let index = 0; index < table.tBodies[0].rows.length; index++) {
                    const element = table.tBodies[0].rows[index]
                    sum_weight += summary[index].weight
                    let calculator = (summary[index].weight * summary[index].cal) / 100
                    element.cells[2].textContent = `${parseFloat(calculator).toFixed(2)} %`
                    total += parseFloat(calculator)
                    element.cells[2].dataset.originalTitle = `(${summary[index].cal.toFixed(2)} * ${summary[index].weight.toFixed(2)}) / 100`
                }
                table.tFoot.rows[0].cells[1].textContent = `${sum_weight.toFixed(2)} %`
                table.tFoot.rows[0].cells[2].textContent = `${total.toFixed(2)} %`

            }
        }
    })
}

const submit = () => {
    setVisible(true)
    putEvaluateSelf(evaluate.id, evaluateForm).then(res => {
            if (res.status === 201) {
                status.textContent = res.data.data.status
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data.message)
        })
        .finally(() => {
            setVisible(false)
            toastClear()
        })
}

const submitToManager = () => {
    evaluateForm.next = !evaluateForm.next
    setVisible(true)
    // Save & send to manager 
    putEvaluateSelf(evaluate.id, evaluateForm).then(res => {
            let label_status = document.getElementsByClassName('card-header')[0].querySelector('span')
            if (res.status === 201) {
                label_status.textContent = res.data.data.status
                if (res.data.data.status === status.SUBMITTED) {
                    pageDisable()
                }
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data.message)
        })
        .finally(() => {
            evaluateForm.next = !evaluateForm.next
            setVisible(false)
            toastClear()
        })
}

// modal method

$('#rule-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    // dropdownRule(group,modal)
    modal.find('#rule-modal-label')[0].textContent = group.name
    setDropdowToModal(group, modal)
})

$('#rule-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #rule_name')[0])
    rule = []
})

// dropdown
const setDropdowToModal = (group, modal) => {
    let select = modal.find('.modal-body #rule_name')[0]
    getRuleDropdown(group)
        .then(res => {
            if (res.status === 200) {
                rule = res.data.data.filter(item => !evaluateForm.detail.map(r => r.rule_id).includes(item.id))
                for (let index = 0; index < rule.length; index++) {
                    const element = rule[index]
                    select.add(new Option(element.name, element.id, false, false))
                }
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data)
        })
}

const addRule = (e) => {
    let rule_name = document.getElementById('rule_name')
    let value = rule.find(element => element.id === parseInt(rule_name.value))
    let weight = document.getElementById(`weight-${value.categorys.name}`)
    let detail = new EvaluateDetail()
    detail.rule_id = value.id
    detail.rules = value
    detail.weight_category = parseFloat(weight.value)
    evaluateForm.detail.push(detail)
    render_html()
    e.offsetParent.querySelector('.close').click()
}

const selectToRemove = (e) => {
    if (e.checked) {
        evaluateForm.remove.push(parseInt(e.id))
    }
    if (!e.checked) {
        if (evaluateForm.remove.length > 0) {
            evaluateForm.remove.splice(evaluateForm.remove.indexOf(parseInt(e.id)), 1)
            parseInt(e.id)
        }
    }
}

const removeInSelected = (e) => {
    evaluateForm.detail = evaluateForm.detail.filter(item => !evaluateForm.remove.includes(item.rule_id))
    render_html()
}
