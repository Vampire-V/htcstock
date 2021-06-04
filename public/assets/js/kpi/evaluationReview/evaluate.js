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
                    // if (evaluate.status !== status.READY && evaluate.status !== status.DRAFT) {
                    //     pageDisable()
                    // }
                })
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
                let readonly = true
                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line, '', `changeValue(this)`, readonly))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max, '', `changeValue(this)`, readonly))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight, '', `changeValue(this)`, readonly))

                let cellAmount = newRow.insertCell()
                cellAmount.appendChild(newInput('number', className, 'amount', element.amount, '', `changeValue(this)`, readonly))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target, '', `changeValue(this)`, readonly))

                let cellActual = newRow.insertCell()
                cellActual.appendChild(newInput('number', className, 'actual', element.actual, '', `changeValue(this)`, false))

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

        head_weight = table.offsetParent.firstElementChild.querySelector('input')
        if (head_weight && head_weight.name in template) {
            head_weight.value = template[head_weight.name]
        }
        let sum_weight = temp_rules.reduce((total, cur) => total += cur.weight, 0.00)
        let sum_ach = temp_rules.reduce((total, cur) => total += cur.ach, 0.00)
        let sum_cal = temp_rules.reduce((total, cur) => total += cur.cal, 0.00)
        table.tFoot.lastElementChild.cells[5].textContent = `${sum_weight.toFixed(2)}%`
        table.tFoot.lastElementChild.cells[9].textContent = `${sum_ach.toFixed(2)}%`
        table.tFoot.lastElementChild.cells[10].textContent = `${sum_cal.toFixed(2)}%`
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
                    pageDisable()
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
                pageDisable()
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
        })
}
