(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // OverlayScrollbars(document.getElementsByClassName('table-responsive'), {});
        $("#rule_name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#switch-rule-modal')
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
            if (evaluate.status !== status.READY && evaluate.status !== status.DRAFT) {
                pageDisable()
            }
            if (auth.id !== evaluate.user_id) {
                pageDisable()
            }
            await getTemplate(evaluateForm.template)
                .then(res => {
                    if (res.status === 200) {
                        template = res.data.data
                        template.weight_kpi = weight_group[0]
                        template.weight_key_task = weight_group[1]
                        template.weight_omg = weight_group[2]
                    }
                })
                .catch(error => {
                    toast(error.response.data.message, error.response.data.status)
                    console.log(error.response.data)
                })
                .finally(() => {
                    render_html()
                })
            
            if (operation) {
                document.getElementById('submit').disabled = false
            }
        }
    }, false);
})();

var evaluateForm = new EvaluateForm()
var template
var rule = []
var summary = []



var render_html = () => {
    // create tr in table
    let tables = document.getElementById('group-table').getElementsByClassName('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let reduce = 0
        let temp_rules = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))

        if (table.tBodies[0].rows.length > 0) {
            removeAllChildNodes(table.tBodies[0])
        }

        for (let index = 0; index < temp_rules.length; index++) {
            const element = temp_rules[index]
            try {
                let newRow = table.tBodies[0].insertRow()
                if (element.weight <= 0.00) {
                    newRow.classList.add(bg_color)
                }
                let cellIndex = newRow.insertCell()
                if (auth.roles.find(item => item.slug === `super-admin`)) {
                    cellIndex.setAttribute('data-toggle', 'modal')
                    cellIndex.setAttribute('data-target', '#switch-rule-modal')
                    cellIndex.setAttribute('data-group', element.rules.category_id)
                    cellIndex.setAttribute('data-id', element.id)
                }

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
                
                // console.log(readonly);
                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line.toFixed(2), '', `changeValue(this)`, !operation))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max.toFixed(2), '', `changeValue(this)`, true))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight.toFixed(2), '', `changeValue(this)`, true))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target.toFixed(2), '', `changeValue(this)`, !operation))

                let cellTargetPC = newRow.insertCell()
                cellTargetPC.textContent = findTargetPercent(element, temp_rules).toFixed(2) + `%`

                let cellActual = newRow.insertCell()
                cellActual.appendChild(newInput('number', className, 'actual', element.actual.toFixed(2), '', `changeValue(this)`, !operation))

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

                let remark = newRow.insertCell()
                remark.appendChild(newInput('text', className, 'remark', element.remark ?? '', `remark_${element.rules.id}`, '', true))
            } catch (error) {
                console.error(error)
            }
        }

        if (temp_rules.length > 0) {
            let reduce_input = table.offsetParent.firstElementChild.lastElementChild.querySelector('input')
            if (temp_rules[0].rules.categorys.name === category.KPI) {
                reduce = evaluate.kpi_reduce
                reduce_input.value = evaluate.kpi_reduce
            }
            if (temp_rules[0].rules.categorys.name === category.KEYTASK) {
                reduce = evaluate.key_task_reduce
                reduce_input.value = evaluate.key_task_reduce
            }
            if (temp_rules[0].rules.categorys.name === category.OMG) {
                reduce = evaluate.omg_reduce
                reduce_input.value = evaluate.omg_reduce
            }
        }

        head_weight = table.offsetParent.firstElementChild.querySelector('input')
        if (head_weight && head_weight.name in template) {
            head_weight.value = template[head_weight.name].toFixed(2)
        }

        let sum_weight = temp_rules.reduce((total, cur) => total + cur.weight, 0.00)
        let sum_cal = temp_rules.reduce((total, cur) => total + cur.cal, 0.00) - reduce
        table.tFoot.lastElementChild.cells[5].textContent = `${sum_weight.toFixed(2)}%`
        table.tFoot.lastElementChild.cells[11].textContent = `${sum_cal.toFixed(2)}%`
        summary.push({
            'weight': parseFloat(head_weight.value),
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
    // console.log(evaluateForm.detail)
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
                if (res.data.data.status !== status.READY && res.data.data.status !== status.DRAFT) {
                    pageDisable()
                }
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data)
        })
        .finally(() => {
            evaluateForm.next = !evaluateForm.next
            setVisible(false)
            toastClear()
        })
}

const download = () => {
    window.open("/kpi/evaluation/" + evaluate.id + "/excel", "_blank");
}

const changerule = async () => {
    let r_id = $("#rule_name").val()
    let c_id = $("#current_item").val()
    let form = {
        form_id: evaluateForm.id,
        new_rule: r_id,
        form_detail_id: c_id
    }
    try {
        let result = await putRuleInEvaluate(form)
        if (result.status === 200) {
            toast(result.data.message, result.data.status)
            document.getElementById('switch-rule-modal').querySelector('.close').click()
        }
        console.log(result)
    } catch (error) {
        console.error(error)
    } finally {
        window.location.reload()
    }
}

// modal method
$('#switch-rule-modal').on('show.bs.modal', async function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    var id = button.data('id') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    let arr = []
    for (let elements of evaluate.detail.values()) {
        arr.push(elements.rule_id)
    }
    let form = {
        group: group,
        rules: arr
    }
    try {
        let rules = await postRulesNotIn(form)
        if (rules.status === 200) {
            document.getElementById('current_item').value = id
            let select = modal.find('.modal-body #rule_name')[0]
            for (let index = 0; index < rules.data.data.length; index++) {
                const rule = rules.data.data[index]
                select.add(new Option(rule.name, rule.id))
            }
        }
    } catch (error) {

    } finally {
        modal.find('.modal-body #reload').removeClass('reload')
    }
    // console.log(evaluateForm.detail.values());
    // setDropdowToModal(group, modal)
    // 
})

$('#switch-rule-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #rule_name')[0])
    document.getElementById('current_item').value = ""
    // modal.find('.modal-body #reload').addClass('reload')
})


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


$('#comment-modal').on('show.bs.modal', async function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    try {} catch (error) {
        console.error(error);
    } finally {
        modal.find('.modal-body #reload').removeClass('reload')
    }
    // console.log(evaluateForm.detail.values());
    // setDropdowToModal(group, modal)
    // 
})

$('#comment-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    modal.find('.modal-body #reload').addClass('reload')
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
