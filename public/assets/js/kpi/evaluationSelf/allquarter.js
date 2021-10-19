(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // OverlayScrollbars(document.getElementsByClassName('table-responsive'), {});
        // $("#rule_name").select2({
        //     placeholder: 'Select RuleTemplate',
        //     allowClear: true,
        //     dropdownParent: $('#rule-modal')
        // })
    })

    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        let d = new Date()
        if (evaluate) {
            // evaluateForm = await setEvaluate(evaluate)
            let temp = []
            for (var i = 0; i < evaluate.evaluateDetail.length; i++) {
                let item = evaluate.evaluateDetail[i]
                item.weigth_list = []
                item.average_max = []
                item.average_actual = []
                item.average_target = []
                if (temp.length < 1) {
                    item.weigth_list.push(item.weight)
                    item.average_max.push(item.max_result)
                    item.average_actual.push(item.actual)
                    item.average_target.push(item.target)
                    temp.push(item)
                } else {
                    let t_index = temp.findIndex(t => t.rule_id === item.rule_id)
                    if (t_index === -1) {
                        item.weigth_list.push(item.weight)
                        item.average_max.push(item.max_result)
                        item.average_actual.push(item.actual)
                        item.average_target.push(item.target)
                        temp.push(item)
                    } else {
                        temp[t_index].actual += item.actual
                        temp[t_index].target += item.target
                        temp[t_index].weight += item.weight
                        temp[t_index].weigth_list.push(item.weight)
                        temp[t_index].average_max.push(item.max_result)
                        temp[t_index].average_actual.push(item.actual)
                        temp[t_index].average_target.push(item.target)
                    }
                }
            }
            let month_now = 12
            let quarter = 4
            if (d.getFullYear() === parseInt(evaluate.targetperiod.year)) {
                month_now = d.getDate() > 12 ? (d.getMonth() + 1) - 1 : (d.getMonth() + 1) - 2
                quarter = getQuarterForHaier(d)
            }

            // console.log(getQuarterForHaier(d));
            for (let index = 0; index < temp.length; index++) {
                const element = temp[index]
                // สิ้นปี อาจมีปัญหา
                element.max_result = element.average_max[element.average_max.length - 1]
                element.weight = element.rule.category.name === `omg` ? element.weight / quarter : element.weight / month_now
                element.target = quarter_cal_target(element)
                element.actual = quarter_cal_amount(element)

            }
            evaluate.evaluate_detail = temp
            render_html()
            pageDisable()
        }
    }, false);
})();

var evaluateForm = new EvaluateForm()
// var template
var rule = []
var summary = []

var render_html = () => {
    // create tr in table
    let tables = document.getElementById('group-table').getElementsByClassName('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let reduce = 0
        let temp_rules = evaluate.evaluate_detail.filter(item => item.rule.category.name === table.id.substring(6))
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
                cellName.textContent = element.rule.name
                setAttributes(cellName, {
                    "data-toggle": "tooltip",
                    "title": `${element.rule.name}`,
                    "data-placement": "top"
                })
                cellName.classList.add('truncate')

                let cellDesc = newRow.insertCell()
                cellDesc.textContent = element.rule.description
                setAttributes(cellDesc, {
                    "data-toggle": "tooltip",
                    "title": `${element.rule.description}`,
                    "data-placement": "top"
                })
                cellDesc.classList.add('truncate')
                // ถ้าเป็นเจ้าของ rule หรือเป็นหน้า evaluation-review ไม่ต้อง readonly
                let readonly = true
                // console.log(readonly);
                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line.toFixed(2), '', `changeValue(this)`, readonly))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max_result.toFixed(2), '', `changeValue(this)`, readonly))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight.toFixed(2), '', `changeValue(this)`, readonly))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target.toFixed(2), '', `changeValue(this)`, readonly))

                let cellTargetPC = newRow.insertCell()
                cellTargetPC.textContent = score_findTargetPercent(element, temp_rules).toFixed(2) + `%`

                let cellActual = newRow.insertCell()
                cellActual.appendChild(newInput('number', className, 'actual', element.actual.toFixed(2), '', `changeValue(this)`, readonly))

                let cellActualPC = newRow.insertCell()
                cellActualPC.textContent = score_findActualPercent(element, temp_rules).toFixed(2) + `%`

                let cellAch = newRow.insertCell()
                element.ach = score_findAchValue(element)
                cellAch.style = 'cursor: default;'
                score_setTooltipAch(cellAch, element)
                cellAch.textContent = element.ach.toFixed(2) + '%'


                let cellCal = newRow.insertCell()
                element.cal = score_findCalValue(element, element.ach)
                cellCal.style = 'cursor: default;'
                score_setTooltipCal(cellCal, element)
                cellCal.textContent = element.cal.toFixed(2) + '%'

                let cellCheck = newRow.insertCell()
                let div = document.createElement('div')
                // div.className = 'custom-checkbox custom-control'

                // let checkbox = document.createElement('input')
                // checkbox.type = `checkbox`
                // checkbox.name = `rule-${element.rule_id}`
                // checkbox.className = `custom-control-input`
                // checkbox.id = element.rule_id
                // checkbox.setAttribute('onclick', 'selectToRemove(this)')

                // let label = document.createElement('label')
                // label.classList.add('custom-control-label')
                // label.htmlFor = element.rule_id

                // div.appendChild(checkbox)
                // div.appendChild(label)
                cellCheck.appendChild(div)
            } catch (error) {
                console.log(error)
            }
        }

        if (temp_rules.length > 0) {
            if (temp_rules[0].rule.category.name === category.KPI) {
                reduce = evaluate.kpi_reduce
            }
            if (temp_rules[0].rule.category.name === category.KEYTASK) {
                reduce = evaluate.key_task_reduce
            }
            if (temp_rules[0].rule.category.name === category.OMG) {
                reduce = evaluate.omg_reduce
            }
        }

        let sum_weight = temp_rules.reduce((total, cur) => total + cur.weight, 0.00)
        let sum_cal = temp_rules.reduce((total, cur) => total + cur.cal, 0.00) - (reduce / 12)
        table.tFoot.lastElementChild.cells[5].textContent = `${sum_weight.toFixed(2)}%`
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

const download = () => {
    window.open(`/kpi/evaluation/user/${evaluate.user_id}/year/${evaluate.targetperiod.year}/excel`, "_blank");
}
