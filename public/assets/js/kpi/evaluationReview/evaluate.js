(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
    })

    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        // console.log(status);
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
                    render_html()
                    if (current.user_approve === auth.id && evaluateForm.status === status.ONPROCESS) {
                        pageEnable()
                        // console.log('enable')
                    }else{
                        if (can_input) {
                            pageEnable()
                        }else{
                            pageDisable()
                        }
                    }
                })
        }
    }, false);
})();
var evaluateForm = new EvaluateForm()
var template
var rule = []
var summary = []
var disable_for = ['kpi','omg']

var render_html = () => {
    // create tr in table
    let tables = document.getElementById('group-table').getElementsByClassName('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let reduce = 0
        let reduce_hod = 0
        let temp_rules = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))

        if (table.tBodies[0].rows.length > 0) {
            removeAllChildNodes(table.tBodies[0])
        }

        for (let index = 0; index < temp_rules.length; index++) {
            const element = temp_rules[index]
            // ถ้าเป็นเจ้าของ rule หรือเป็นหน้า evaluation-review ไม่ต้อง readonly

            try {
                let newRow = table.tBodies[0].insertRow()
                if (element.weight <= 0.00) {
                    newRow.classList.add(bg_color)
                }
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

                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line.toFixed(2), '', `changeValue(this)`, true))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max.toFixed(2), '', `changeValue(this)`, true))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight.toFixed(2), '', `changeValue(this)`, true))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target.toFixed(2), '', `changeValue(this)`, !can_input))

                let cellTargetPC = newRow.insertCell()
                cellTargetPC.textContent = findTargetPercent(element,temp_rules).toFixed(2) + '%'


                let cellActual = newRow.insertCell()
                cellActual.appendChild(newInput('number', className, 'actual', element.actual.toFixed(2), '', `changeValue(this)`, !can_input))

                let cellActualPC = newRow.insertCell()
                cellActualPC.textContent = findActualPercent(element,temp_rules).toFixed(2) + '%'

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
                remark.appendChild(newInput('text', className, 'remark', element.remark ?? '', `remark_${element.rules.id}`, `remark(this)`, !can_input))
            } catch (error) {
                console.error(error)
                toast(error.response.data.message,'error')
            } finally {
                toastClear()
            }
        }

        if (temp_rules.length > 0) {
            let reduce_hod_input = table.offsetParent.firstElementChild.lastElementChild.querySelectorAll('input')[0]
            let reduce_input = table.offsetParent.firstElementChild.lastElementChild.querySelectorAll('input')[1]
            if (temp_rules[0].rules.categorys.name === category.KPI) {
                reduce = evaluate.kpi_reduce
                reduce_input.value = evaluate.kpi_reduce
                reduce_hod = evaluate.kpi_reduce_hod
                reduce_hod_input.value = evaluate.kpi_reduce_hod
            }
            if (temp_rules[0].rules.categorys.name === category.KEYTASK) {
                reduce = evaluate.key_task_reduce
                reduce_input.value = evaluate.key_task_reduce
                reduce_hod = evaluate.key_task_reduce_hod
                reduce_hod_input.value = evaluate.key_task_reduce_hod
            }
            if (temp_rules[0].rules.categorys.name === category.OMG) {
                reduce = evaluate.omg_reduce
                reduce_input.value = evaluate.omg_reduce
                reduce_hod = evaluate.omg_reduce_hod
                reduce_hod_input.value = evaluate.omg_reduce_hod
            }
        }

        let head_weight = table.offsetParent.firstElementChild.querySelector('input')
        if (head_weight && head_weight.name in template) {
            head_weight.value = template[head_weight.name]
        }

        let sum_weight = temp_rules.reduce((total, cur) => total += cur.weight, 0.00)
        let sum_cal = temp_rules.reduce((total, cur) => total += cur.cal, 0.00) - (reduce + reduce_hod)
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
                // หา %Ach
                let ach = findAchValue(rule)
                tr.cells[column + 1].firstChild.textContent = ach.toFixed(2) + '%'
                rule.ach = ach

                // หา %Cal
                let cal = findCalValue(rule, ach)
                tr.cells[column + 2].firstChild.textContent = cal.toFixed(2) + '%'
                tr.cells[column + 2].dataset.originalTitle = changeTooltipCal(tr.cells[column + 2].dataset.originalTitle, rule)
                rule.cal = cal

                // total %Ach & %Cal
                let sumCal = evaluateForm.detail
                    .filter(item => item.rules.categorys.name === rule.rules.categorys.name)
                    .reduce((total, currentValue) => total + currentValue.cal, 0)

                let sumAch = evaluateForm.detail
                    .filter(item => item.rules.categorys.name === rule.rules.categorys.name)
                    .reduce((total, currentValue) => total + currentValue.ach, 0)

                foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 2].textContent = parseFloat(sumCal).toFixed(2) + '%'
                foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 3].textContent = parseFloat(sumAch).toFixed(2) + '%'
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
                table.tFoot.rows[0].cells[2].textContent = `${total.toFixed(2)} %`
                table.tFoot.rows[0].cells[1].textContent = `${sum_weight.toFixed(2)} %`
            }
        }
    })
}

const set_reduce = e => {
    if (e.name in evaluateForm) {
        evaluateForm[e.name] = parseFloat(e.value)
    }
}
const remark = (e) => {
    evaluateForm.detail.forEach((element, key) => {
        if (e.offsetParent.parentNode.cells[1].textContent === element.rules.name) {
            // create new method formula
            element[e.name] = e.value
        }
    })
}

const reject = async (e) => {
    // Save & reject
    // /kpi/evaluation-review/update
    const {
        value: text
    } = await Swal.fire({
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
        setVisible(true)
        putEvaluateReview(evaluate.id, evaluateForm)
            .then(res => {
                let status = document.getElementsByClassName('card-header')[0].querySelector('span')
                if (res.status === 201) {
                    status.textContent = res.data.data.status
                    if (res.data.data.current_level.user_approve.id !== auth.id && res.data.data.status === status.ONPROCESS) {
                        pageEnable()
                    }else{
                        pageDisable()
                    }
                    toast(res.data.message, res.data.status)
                }
            })
            .catch(error => {
                toast(error.response.data.message, error.response.data.status)
            })
            .finally(() => {
                setVisible(false)
                toastClear()
            })
    }
}

const approve = (e) => {
    evaluateForm.next = !evaluateForm.next
    // Save & approved
    // /kpi/evaluation-review/update
    setVisible(true)
    putEvaluateReview(evaluate.id, evaluateForm).then(res => {
            let status = document.getElementsByClassName('card-header')[0].querySelector('span')
            if (res.status === 201) {
                status.textContent = res.data.data.status
                // console.log(res.data.data.next_level.user_approve.id , auth.id);
                // console.log(res.data.data.status , status.ONPROCESS);
                // if ((res.data.data.current_level.user_approve.id !== auth.id) && (res.data.data.status === status.ONPROCESS)) {
                //     pageEnable()
                // }else{
                //     pageDisable()
                // }
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            evaluateForm.next = !evaluateForm.next
            setVisible(false)
            toastClear()
            // debugger
            // window.location.reload()
        })
}

const save = async () => {
    // console.log(evaluateForm);
    try {
        let result = await putEvaluateReviewEdit(evaluate.id, evaluateForm)
        console.log(result.data);
        toast(result.data.message,result.data.status)
    } catch (error) {
        console.error(error)
        toast(error.response.data.message,'error')
    } finally {
        toastClear()
        window.location.reload()
    }
}
