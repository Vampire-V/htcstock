var calculate = {
        POSITIVE: "Positive",
        NEGATIVE: "Negative",
        ZERO: "Zero Oriented KPI"
    },
    status = {
        NEW: "New",
        READY: "Ready",
        DRAFT: "Draft",
        SUBMITTED: "Submitted",
        APPROVED: "Approved"
    },
    className = ['form-control', 'form-control-sm']
class EvaluateForm {
    constructor(template = null,
        mainRule = null,
        minone = 0,
        maxone = 0,
        mintwo = 0,
        maxtwo = 0,
        mintree = 0,
        maxtree = 0,
        total_weight_kpi = 0.00,
        total_weight_key_task = 0.00,
        total_weight_omg = 0.00,
        comment = null,
        detail = [],
        remove = [],
        next = false, ) {
        this.template = template
        this.mainRule = mainRule
        this.minone = minone
        this.maxone = maxone
        this.mintwo = mintwo
        this.maxtwo = maxtwo
        this.mintree = mintree
        this.maxtree = maxtree
        this.total_weight_kpi = total_weight_kpi
        this.total_weight_key_task = total_weight_key_task
        this.total_weight_omg = total_weight_omg
        this.comment = comment
        this.detail = detail
        this.remove = remove
        this.next = next
    }
}
class EvaluateDetail {
    constructor(evaluate_id = null,
        rule_id = null,
        rules = Object.create(null),
        target = 0.00,
        actual = 0.00,
        max = 0.00,
        weight = 0.00,
        weight_category = 0.00,
        base_line = 0.00,
        ach = 0.00,
        cal = 0.00) {
        this.evaluate_id = evaluate_id
        this.rule_id = rule_id
        this.rules = rules
        this.target = target
        this.actual = actual
        this.max = max
        this.weight = weight
        this.weight_category = weight_category
        this.base_line = base_line
        this.ach = ach
        this.cal = cal
    }
}
// KPI
// kpi Evaluate-Form Create
var setEvaluate = (datas) => {
    var evaluateForm = new EvaluateForm()
    evaluateForm.template = datas.template_id
    evaluateForm.mainRule = datas.main_rule_id
    evaluateForm.minone = datas.main_rule_condition_1_min
    evaluateForm.maxone = datas.main_rule_condition_1_max
    evaluateForm.mintwo = datas.main_rule_condition_2_min
    evaluateForm.maxtwo = datas.main_rule_condition_2_max
    evaluateForm.mintree = datas.main_rule_condition_3_min

    evaluateForm.total_weight_kpi = datas.total_weight_kpi
    evaluateForm.total_weight_key_task = datas.total_weight_key_task
    evaluateForm.total_weight_omg = datas.total_weight_omg
    evaluateForm.comment = datas.comment
    evaluateForm.detail = setDetail(datas.detail).detail
    return evaluateForm
}

var setDetail = (rule_temp) => {
    evaluateForm.detail = evaluateForm.detail.length > 0 ? [] : evaluateForm.detail
    rule_temp.forEach(element => {
        let detail = new EvaluateDetail()
        detail.evaluate_id = typeof element.evaluate_id === 'undefined' ? null : element.evaluate_id
        detail.rule_id = element.rule_id
        detail.rules = Object.create(element.rules)
        detail.target = typeof element.target === 'undefined' ? element.target_config : element.target
        detail.actual = typeof element.actual === 'undefined' ? 0.00 : element.actual
        detail.max = element.max_result
        detail.weight = element.weight
        detail.weight_category = element.weight_category
        detail.base_line = element.base_line
        evaluateForm.detail.push(detail)
    })
    return evaluateForm
}

var displayDetail = (evaluateForm) => {
    // create tr in table
    let tables = document.getElementsByClassName('app-main__inner')[0].querySelectorAll('table')
    let weightForSum = []
    let achForSum = []
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let data_category = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))
        let sum_weight = 0.00
        let sum_ach = 0.00
        let sum_cal = 0.00
        if (window.location.pathname.search("self-evaluation") > 0 || window.location.pathname.search("evaluation-review") > 0) {
            if (table.id !== "table-calculation") {
                removeAllChildNodes(table.tBodies[0])
                for (let index = 0; index < data_category.length; index++) {
                    const element = data_category[index]
                    let newRow = table.tBodies[0].insertRow()

                    let cellIndex = newRow.insertCell()
                    cellIndex.textContent = index + 1

                    let cellName = newRow.insertCell()
                    cellName.textContent = element.rules.name

                    let cellBaseLine = newRow.insertCell()
                    cellBaseLine.textContent = element.base_line

                    let cellMax = newRow.insertCell()
                    cellMax.textContent = element.max

                    let cellWeight = newRow.insertCell()
                    cellWeight.textContent = element.weight + '%'

                    let cellTarget = newRow.insertCell()
                    cellTarget.textContent = element.target

                    let cellActual = newRow.insertCell()
                    cellActual.appendChild(newInput('number', className, 'actual', element.actual, '', `changeActualValue(this)`))

                    let cellAch = newRow.insertCell()
                    element.ach = findAchValue(element)
                    cellAch.textContent = element.ach.toFixed(2) + '%'
                    setTooltipAch(cellAch, element)

                    let cellCal = newRow.insertCell()
                    element.cal = findCalValue(element, element.ach)
                    cellCal.textContent = element.cal.toFixed(2) + '%'
                    setTooltipCal(cellCal, element)
                }
                sum_weight = data_category.reduce((total, cur) => total += cur.weight, sum_weight)
                table.tFoot.lastElementChild.cells[4].textContent = `${sum_weight.toFixed(2)}%`

                sum_ach = data_category.reduce((total, cur) => total += cur.ach, sum_ach)
                table.tFoot.lastElementChild.cells[7].textContent = `${sum_ach.toFixed(2)}%`

                sum_cal = data_category.reduce((total, cur) => total += cur.cal, sum_cal)
                table.tFoot.lastElementChild.cells[8].textContent = `${sum_cal.toFixed(2)}%`

                weightForSum.push(parseFloat(sum_weight))
                achForSum.push(parseFloat(sum_ach))
            }

            if (table.id === "table-calculation") {
                let body_cal = table.tBodies[0].rows
                let main_rule = evaluateForm.detail.find(value => value.rule_id === evaluateForm.mainRule)
                let sum_total = 0.00
                table.offsetParent.firstElementChild.querySelector('#mainRule').value = main_rule.rules.name
                table.offsetParent.firstElementChild.querySelector('#Cal').value = parseFloat(main_rule.cal).toFixed(2) + '%'
                for (let index = 0; index < body_cal.length; index++) {
                    const row = body_cal[index]
                    row.cells[1].textContent = weightForSum[index].toFixed(2) + '%'
                    row.cells[2].textContent = achForSum[index].toFixed(2) + '%'
                    let total = (parseFloat(achForSum[index]) * parseFloat(weightForSum[index]) / 100)
                    row.cells[3].textContent = total.toFixed(2) + '%'
                    sum_total += total
                }
                table.tFoot.lastElementChild.cells[3].textContent = sum_total.toFixed(2) + '%'
            }
        }
        if (window.location.pathname.search("evaluation-form") > 0) {
            if (table.id) {
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

                        let cellDesc = newRow.insertCell()
                        cellDesc.textContent = element.rules.calculate_type

                        let cellBase_line = newRow.insertCell()
                        cellBase_line.appendChild(newInput('number', className, 'base_line', element.base_line, '', `changeValueRule(this)`))

                        let cellMax = newRow.insertCell()
                        cellMax.appendChild(newInput('number', className, 'max', element.max, '', `changeValueRule(this)`))

                        let cellWeight = newRow.insertCell()
                        cellWeight.appendChild(newInput('number', className, 'weight', element.weight, '', `changeValueRule(this)`))

                        let cellTarget = newRow.insertCell()
                        cellTarget.appendChild(newInput('number', className, 'target', element.target, '', `changeValueRule(this)`))

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
            } else {
                let select = table.offsetParent.querySelector('#mainRule')
                if (select) {
                    removeAllChildNodes(select)
                    if (evaluateForm.detail.length > 0) {
                        select.add(new Option('', '', false, false))
                        for (let k = 0; k < evaluateForm.detail.length; k++) {
                            const element = evaluateForm.detail[k];
                            if (evaluateForm.mainRule === element.rule_id) {
                                select.add(new Option(element.rules.name, element.rule_id, false, true))
                            } else {
                                select.add(new Option(element.rules.name, element.rule_id, false, false))
                            }
                        }
                    }
                }
            }
        }

    }

}

var changeValueRule = (e) => {
    let object = evaluateForm.detail.find(obj => obj.rules.name === e.offsetParent.parentNode.cells[1].textContent)
    for (const key in object) {
        object[key] = key === e.name ? parseFloat(e.value) : object[key]
    }
    let table = e.offsetParent.offsetParent
    let sum = evaluateForm.detail.reduce((total, cur) => cur.rules.category_id === object.rules.category_id ? total += cur.weight : total, 0.00)
    e.offsetParent.parentNode.parentNode.parentNode.tFoot.lastElementChild.cells[5].textContent = `${sum.toFixed(2)}%`

    evaluateForm.total_weight_kpi = table.id.substring(6) === 'kpi' ? sum : evaluateForm.total_weight_kpi
    evaluateForm.total_weight_key_task = table.id.substring(6) === 'key-task' ? sum : evaluateForm.total_weight_key_task
    evaluateForm.total_weight_omg = table.id.substring(6) === 'omg' ? sum : evaluateForm.total_weight_omg
}

var changeValueMainRule = (e) => {
    for (const key in evaluateForm) {
        evaluateForm[key] = (key === e.name) ? parseFloat(e.value) : evaluateForm[key]
    }
}

var removeAllChildNodes = (parent) => {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

const validityForm = () => {
    let forms = document.getElementsByClassName('app-main__inner')[0].querySelectorAll('form'),
        status = true
    forms.forEach(form => {
        if (!form.checkValidity()) {
            form.classList.add('was-validated')
            status = false
        }
    })
    return status
}
// kpi Evaluate-Form Update
var setEvaluateForm = (evaluate) => {
    evaluateForm.template = evaluate.template_id
    evaluateForm.mainRule = evaluate.main_rule_id
    evaluateForm.minone = evaluate.main_rule_condition_1_min
    evaluateForm.maxone = evaluate.main_rule_condition_1_max
    evaluateForm.mintwo = evaluate.main_rule_condition_2_min
    evaluateForm.maxtwo = evaluate.main_rule_condition_2_max
    evaluateForm.mintree = evaluate.main_rule_condition_3_min
    evaluateForm.maxtree = evaluate.main_rule_condition_3_max
    evaluateForm.total_weight_kpi = evaluate.total_weight_kpi
    evaluateForm.total_weight_key_task = evaluate.total_weight_key_task
    evaluateForm.total_weight_omg = evaluate.total_weight_omg
    evaluateForm.comment = evaluate.comment
    // evaluateForm.detail = evaluate.detail
    setDetail(evaluate.detail)
}
// kpi Evaluate-Self
var formulaRuleDetail = (e, key) => {
    let index = e.offsetParent.cellIndex
    let tr = e.offsetParent.parentNode
    let foot = tr.parentNode.parentNode.tFoot
    evaluateForm.detail[key].actual = parseFloat(e.value)

    if (evaluateForm.detail[key].rules.calculate_type !== null) {
        // หา %Ach
        let ach = findAchValue(evaluateForm.detail[key])
        tr.cells[index + 1].firstChild.textContent = ach.toFixed(2) + '%'
        evaluateForm.detail[key].ach = ach

        // หา %Cal
        let cal = findCalValue(evaluateForm.detail[key], ach)
        tr.cells[index + 2].firstChild.textContent = cal.toFixed(2) + '%'
        tr.cells[index + 2].dataset.originalTitle = changeTooltipCal(tr.cells[index + 2].dataset.originalTitle, evaluateForm.detail[key])
        evaluateForm.detail[key].cal = cal

        // total %Ach & %Cal
        let sumCal = evaluateForm.detail.filter(item => {
            return item.rules.categorys.name === evaluateForm.detail[key].rules.categorys.name
        }).reduce(function (total, currentValue) {
            return total + currentValue.cal
        }, 0)

        let sumAch = evaluateForm.detail.filter(item => {
            return item.rules.categorys.name === evaluateForm.detail[key].rules.categorys.name
        }).reduce(function (total, currentValue) {
            return total + currentValue.ach
        }, 0)
        foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 1].textContent = parseFloat(sumCal).toFixed(2) + '%'
        foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 2].textContent = parseFloat(sumAch).toFixed(2) + '%'

        // Calculation Summary
        if (evaluateForm.mainRule === evaluateForm.detail[key].rule_id) {
            document.getElementById('Cal').value = parseFloat(evaluateForm.detail[key].cal).toFixed(2) + '%'
        }
        let table = document.getElementById('table-calculation')
        let body = table.tBodies[0].rows
        let total_summary = 0.00
        for (let index = 0; index < body.length; index++) {
            const element = body[index];
            let total_text = element.cells[element.childElementCount - 1].textContent
            if (element.firstElementChild.textContent === evaluateForm.detail[key].rules.categorys.name) {
                let weight = element.cells[1].textContent.substring(0, element.cells[1].textContent.length - 1)
                element.cells[2].textContent = parseFloat(sumAch).toFixed(2) + '%'
                element.cells[3].textContent = (parseFloat(sumAch) * parseFloat(weight) / 100).toFixed(2) + '%'
            }
            total_summary += parseFloat(total_text.substring(0, total_text.length - 1))
        }
        table.tFoot.rows[0].cells[table.tFoot.rows[0].childElementCount - 1].textContent = total_summary.toFixed(2) + '%'


    } else {
        evaluateForm.detail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }
}

var findAchValue = (obj) => {
    if (obj.rules.calculate_type === calculate.POSITIVE) {
        ach = parseFloat((obj.actual / obj.target) * 100)
    }
    if (obj.rules.calculate_type === calculate.NEGATIVE) {
        ach = parseFloat((2 - (obj.actual / obj.target)) * 100)
    }
    if (obj.rules.calculate_type === calculate.ZERO) {
        ach = obj.actual <= obj.target ? 100.00 : 0.00
    }
    return ach
}

var findCalValue = (obj, ach) => {
    if (ach < 70.00) {
        cal = 0.00
    } else if (ach > obj.base_line) {
        let c = parseFloat(obj.base_line) * parseFloat(obj.weight) / 100
        cal = c
    } else {
        let d = ach * parseFloat(obj.weight) / 100
        cal = d
    }
    return cal
}

var setTooltipAch = (e, data) => {
    if (data.rules.calculate_type === calculate.POSITIVE) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "rules calculate type = Positive : (actual / target) * 100",
            "data-placement": "top"
        })
    }
    if (data.rules.calculate_type === calculate.NEGATIVE) {
        Setattributes(e, {
            "Data-Toggle": "Tooltip",
            "Title": "Rules Calculate Type = Negative : (2 - (actual / target)) * 100",
            "data-placement": "top"
        })
    }
    if (data.rules.calculate_type === calculate.ZERO) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "rules calculate type = Zero Oriented KP : actual <= target ? 100.00 : 0.00",
            "data-placement": "top"
        })
    }
}

var setTooltipCal = (e, data) => {
    if (data.ach < 70.00) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Ach% < 70.00 : Cal = 0.00",
            "data-placement": "top"
        })
    } else if (data.ach > data.base_line) {
        Setattributes(e, {
            "Data-Toggle": "Tooltip",
            "Title": "Ach% >= 70.00 && Ach% > Base Line  = (Base Line * Weight) / 100",
            "data-placement": "top"
        })
    } else {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "(Ach% * Weight) / 100",
            "data-placement": "top"
        })
    }
}

var changeTooltipCal = (befor, data) => {
    let newTitle = befor
    if (data.ach < 70.00) {
        newTitle = "Ach% < 70.00 : Cal = 0.00"
    } else if (data.ach > data.base_line) {
        newTitle = "Ach% >= 70.00 && Ach% > Base Line  = (Base Line * Weight) / 100"
    } else {
        newTitle = "(Ach% * Weight) / 100"
    }
    return newTitle
}
