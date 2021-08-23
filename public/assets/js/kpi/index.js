var calculate = {
        POSITIVE: "Positive",
        NEGATIVE: "Negative",
        ZERO: "Zero Oriented KPI"
    },
    status = {
        NEW: "New",
        READY: "Ready",
        DRAFT: "Draft",
        ONPROCESS: "On Process",
        SUBMITTED: "Submitted",
        APPROVED: "Approved"
    },
    degree = {
        ONE: "N-1",
        TWO: "N-2",
        TREE: "N-3"
    },
    quarters = {
        AVERAGE: "Average",
        LAST_MONTH: "Last Month",
        SUM: "Sum"
    },
    category = {
        KPI: "kpi",
        KEYTASK: "key-task",
        OMG: "omg",
    },
    className = ['form-control', 'form-control-sm']
bg_color = 'color_weight',
month_q1 = [2, 3, 4],
month_q2 = [5, 6, 7],
month_q3 = [8, 9, 10],
month_q4 = [11, 0, 1]


class EvaluateForm {
    constructor(template = null,
        total_weight_kpi = 0.00,
        total_weight_key_task = 0.00,
        total_weight_omg = 0.00,
        kpi_reduce = 0.00,
        key_task_reduce = 0.00,
        omg_reduce = 0.00,
        comment = null,
        detail = [],
        remove = [],
        next = false,
        next_level = null,
        current_level = null,
        status = null) {
        this.template = template
        this.total_weight_kpi = total_weight_kpi
        this.total_weight_key_task = total_weight_key_task
        this.total_weight_omg = total_weight_omg
        this.kpi_reduce = kpi_reduce
        this.key_task_reduce = key_task_reduce
        this.omg_reduce = omg_reduce
        this.comment = comment
        this.detail = detail
        this.remove = remove
        this.next = next
        this.next_level = next_level
        this.current_level = current_level
        this.status = status
    }
}

class EvaluateDetail {
    constructor(evaluate_id = null,
        rule_id = null,
        rules = Object.create(null),
        target = 0.00,
        target_pc = 0.00,
        actual = 0.00,
        actual_pc = 0.00,
        max = 0.00,
        weight = 0.00,
        weight_category = 0.00,
        base_line = 0.00,
        ach = 0.00,
        cal = 0.00,
        remark = null
    ) {
        this.evaluate_id = evaluate_id
        this.rule_id = rule_id
        this.rules = rules
        this.target = target
        this.target_pc = target_pc
        this.actual = actual
        this.actual_pc = actual_pc
        this.max = max
        this.weight = weight
        this.weight_category = weight_category
        this.base_line = base_line
        this.ach = ach
        this.cal = cal
        this.remark = remark
    }
}
// KPI
// kpi Evaluate-Form Create
var setEvaluate = (datas) => {
    var evaluateForm = new EvaluateForm()
    evaluateForm.id = datas.id ?? null
    evaluateForm.template = datas.template_id

    evaluateForm.total_weight_kpi = datas.total_weight_kpi
    evaluateForm.total_weight_key_task = datas.total_weight_key_task
    evaluateForm.total_weight_omg = datas.total_weight_omg
    kpi_reduce = datas.kpi_reduce,
    key_task_reduce = datas.key_task_reduce,
    omg_reduce = datas.omg_reduce,
    // evaluateForm.cal_kpi = datas.cal_kpi
    // evaluateForm.cal_key_task = datas.cal_key_task
    // evaluateForm.cal_omg = datas.cal_omg

    evaluateForm.comment = datas.comment
    evaluateForm.status = datas.status
    evaluateForm.next_level = datas.next_level
    evaluateForm.current_level = datas.current_level
    evaluateForm.detail = setDetail(datas.detail).detail
    return evaluateForm
}

var setDetail = (rule_temp) => {
    evaluateForm.detail = evaluateForm.detail.length > 0 ? [] : evaluateForm.detail
    rule_temp.forEach(element => {
        let detail = new EvaluateDetail()
        detail.id = element.id ?? null
        detail.evaluate_id = element.evaluate_id ?? null
        detail.rule_id = element.rule_id
        detail.rules = element.rules
        detail.target = element.target_config ?? element.target
        detail.actual = element.actual ?? 0.00
        detail.average_actual = []
        detail.average_target = []
        detail.max = element.max_result
        detail.weight = element.weight
        detail.weight_category = element.weight_category
        detail.base_line = element.base_line
        detail.remark = element.remark
        evaluateForm.detail.push(detail)
    })
    return evaluateForm
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
        let table = document.getElementById('table-calculation')
        let body = table.tBodies[0].rows
        let total_summary = 0.00
        for (let index = 0; index < body.length; index++) {
            const element = body[index]
            if (element.firstElementChild.textContent === evaluateForm.detail[key].rules.categorys.name) {
                let weight = element.cells[1].textContent.substring(0, element.cells[1].textContent.length - 1)
                element.cells[2].textContent = parseFloat(sumAch).toFixed(2) + '%'
                element.cells[3].textContent = (parseFloat(sumAch) * parseFloat(weight) / 100).toFixed(2) + '%'
            }
            total_summary += parseFloat(element.cells[3].textContent)
        }
        table.tFoot.rows[0].cells[table.tFoot.rows[0].childElementCount - 1].textContent = total_summary.toFixed(2) + '%'


    } else {
        evaluateForm.detail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
var findTargetPercent = (element, array) => {
    try {
        if (element.rules.parent) {
            let parent = array.find(item => item.rule_id === element.rules.parent)
            let target = element.target_config ?? element.target
            let parent_target = parent.target_config ?? parent.target
            if (parent) {
                let result = (target > parent_target) ? 0.00 : (target === 0.00) && (parent_target === 0.00) ? 0.00 : (target / parent_target) * 100
                element.target_pc = result
            }
        } else {
            element.target_pc = 100.00
        }
        return element.target_pc
    } catch (error) {
        console.error(error);
        toast(`${element.rules.name} parent not found`,'error')
        return `parent not found..`
    }
    
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
var findActualPercent = (element, array) => {
    let result = 0.00
    if (element.rules.parent) {
        let parent = array.find(item => item.rule_id === element.rules.parent)
        if (element.rules.calculate_type === calculate.POSITIVE) {
            result = element.actual > parent.actual ? 0.00 : element.actual === 0.00 ? 0.00 : (element.actual / parent.actual) * 100
        }
        if (element.rules.calculate_type === calculate.NEGATIVE) {
            result = parent.actual > element.actual ? (element.actual / parent.actual) * 100 : 0.00
        }
        if (element.rules.calculate_type === calculate.ZERO) {
            // ไม่มี
            result = element.actual <= parent.actual ? 100.00 : 0.00
        }
    } else {
        // result = (element.actual / (element.target === 0) ? 1 : element.target) * 100
        if (element.rules.calculate_type === calculate.POSITIVE) {
            result = element.actual > element.target ? 100.00 : element.actual === 0.00 ? 0.00 : (element.actual / element.target) * 100
        }
        if (element.rules.calculate_type === calculate.NEGATIVE) {
            result = element.actual > element.target ? ((element.actual / element.target) * 100) : 100.00
        }
        if (element.rules.calculate_type === calculate.ZERO) {
            result = element.actual <= element.target ? 100.00 : 0.00
        }
    }
    return element.actual_pc = result === Infinity || result === -Infinity || isNaN(result) ? 0.00 : result
}

var findAchValue = (obj) => {
    if (typeof obj === `object`) {
        if (!obj.rules.parent) {
            // ใช้ amount หา
            if (obj.rules.calculate_type === calculate.POSITIVE) {
                if (obj.target === 0.00 && obj.actual > obj.target) {
                    ach = obj.max
                } else if (obj.actual === 0.00) {
                    ach = 0.00
                } else {
                    ach = parseFloat((obj.actual / obj.target) * 100.00)
                }
                // ach = obj.actual >= obj.target ? obj.max : obj.actual === 0.00 ? 0.00 : parseFloat((obj.actual / obj.target) * 100.00)
            }
            if (obj.rules.calculate_type === calculate.NEGATIVE) {
                let dd = (obj.actual / obj.target)
                if (dd === -Infinity) {
                    dd = 0
                }
                // console.log(obj.actual);
                // if (obj.actual !== 0.00) {
                //     if (obj.actual < obj.target) {
                //         ach = obj.max_result ?? obj.max
                //     } else {
                ach = parseFloat((2 - dd) * 100.00)
                // console.log(obj.rules.name,ach);
                // }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual !== 0.00 ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 2
                // ach = obj.actual > obj.target ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 1
            }
            if (obj.rules.calculate_type === calculate.ZERO) {
                ach = obj.actual <= obj.target ? 100.00 : 0.00
            }
        } else {
            // ใช้ % หา
            if (obj.rules.calculate_type === calculate.POSITIVE) {
                if (obj.target_pc === 0.00 && obj.actual_pc > obj.target_pc) {
                    ach = obj.max
                } else if (obj.actual_pc === 0.00) {
                    ach = 0.00
                } else {
                    ach = parseFloat((obj.actual_pc / obj.target_pc) * 100.00)
                }
                // ach = obj.actual_pc >= obj.target_pc ? obj.max : parseFloat((obj.actual_pc / obj.target_pc) * 100)
            }
            if (obj.rules.calculate_type === calculate.NEGATIVE) {
                // console.log(obj.actual_pc , obj.target_pc);
                let dd = (obj.actual_pc / obj.target_pc)
                if (dd === -Infinity) {
                    dd = 0
                }
                ach = parseFloat((2 - dd) * 100.00)
                // if (obj.actual_pc !== 0.00) {
                //     if (obj.actual_pc < obj.target_pc) {
                //         ach = obj.max ?? obj.max_result
                //     } else {
                // ach = parseFloat((2 - dd ) * 100.00)
                // console.log(obj.rules.name,ach);
                //     }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual_pc !== 0.00 ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : 0.00  #version 2
                // ach = obj.actual_pc > obj.target_pc ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : obj.max  #version 1
            }
            if (obj.rules.calculate_type === calculate.ZERO) {
                ach = obj.actual_pc <= obj.target_pc ? 100.00 : 0.00
            }
        }
    }
    if (typeof obj === `number`) {
        ach = obj
    }
    return isNaN(ach) || (ach === Infinity || ach === -Infinity) ? 0.00 : ach
}

var findCalValue = (obj, ach) => {
    // console.log(obj,ach);
    if (ach < obj.base_line) {
        cal = 0.00
    } else {
        if ('max_result' in obj) {
            if (ach >= obj.max_result) {
                cal = parseFloat(obj.max_result) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }
        if ('max' in obj) {
            if (ach >= obj.max) {
                cal = parseFloat(obj.max) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }

    }
    return isNaN(cal) || (cal === Infinity) ? 0.00 : cal
}

/**
 * @params {element} document.getElementById
 * @params {EvaluateDetail} EvaluateDetail
 * @return void
 */
var setTooltipAch = (e, data) => {
    if (data.rules.calculate_type === calculate.POSITIVE) {
        if (data.target_pc === 100) {
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Positive : (actual amount / target amount) * 100",
                "data-placement": "top"
            })
        } else {
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Positive : (Actual % / Target %) * 100",
                "data-placement": "top"
            })
        }

    }
    if (data.rules.calculate_type === calculate.NEGATIVE) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Negative : (2 - (actual amount / target amount)) * 100",
            "data-placement": "top"
        })
    }
    if (data.rules.calculate_type === calculate.ZERO) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Zero Oriented KP : actual amount == target amount ? 100.00 : 0.00",
            "data-placement": "top"
        })
    }
}

/**
 * @params {element} document.getElementById
 * @params {EvaluateDetail} EvaluateDetail
 * @return void
 */
var setTooltipCal = (e, data) => {
    if (data.ach < data.base_line) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Ach% < Base Line : Cal = 0.00",
            "data-placement": "top"
        })
    } else {
        if (data.ach >= data.max_result) {
            // cal = parseFloat(obj.max_result) * parseFloat(obj.weight) / 100
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Ach% >= Max  = (Max * Weight) / 100",
                "data-placement": "top"
            })
        } else {
            // cal = ach * parseFloat(obj.weight) / 100
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "(Ach% * Weight) / 100",
                "data-placement": "top"
            })
        }
    }
}

/**
 * @params {text} sdfsdfds
 * @params {EvaluateDetail} EvaluateDetail
 * @return void
 */
var changeTooltipCal = (befor, data) => {
    let newTitle = befor
    if (data.ach < data.base_line) {
        newTitle = "Ach% < Base Line : Cal = 0.00"
    }
    if (data.ach > data.base_line) {
        newTitle = "Ach% > Base Line : (Base Line * Weight) / 100"
    }
    if (data.ach === data.base_line) {
        newTitle = "Ach% = Base Line : (Ach% * Weight) / 100"
    }
    return newTitle
}

var make_link = (url, text) => {
    let x = document.createElement("A"),
        t = document.createTextNode(text)
    x.setAttribute("href", url)
    x.setAttribute("target", "_blank")
    x.setAttribute("rel", "noopener")
    x.set
    x.appendChild(t)
    return x
}

const quarter_cal_target = (item) => {
    if (item.rule.quarter_cal === quarters.AVERAGE) {
        return item.average_target.reduce((a, b) => (a + b)) / item.average_target.length
    }
    if (item.rule.quarter_cal === quarters.LAST_MONTH) {
        return item.average_target[item.average_target.length - 1]
    }
    if (item.rule.quarter_cal === quarters.SUM) {
        return item.average_target.reduce((a, b) => (a + b))
    }
}

const quarter_cal_amount = (item) => {
    if (item.rule.quarter_cal === quarters.AVERAGE) {
        return item.average_actual.reduce((a, b) => (a + b)) / item.average_actual.length
    }
    if (item.rule.quarter_cal === quarters.LAST_MONTH) {
        return item.average_actual[item.average_actual.length - 1]
    }
    if (item.rule.quarter_cal === quarters.SUM) {
        return item.average_actual.reduce((a, b) => (a + b))
    }
}



const score_quarter_cal_target = (rule) => {
    if (rule.rule.quarter_cal === quarters.AVERAGE) {
        return rule.average_target.reduce((a, b) => (a + b)) / rule.average_target.length
    }
    if (rule.rule.quarter_cal === quarters.LAST_MONTH) {
        return rule.average_target[rule.average_target.length - 1]
    }
    if (rule.rule.quarter_cal === quarters.SUM) {
        return rule.average_target.reduce((a, b) => (a + b))
    }
}

const score_quarter_cal_amount = (rule) => {
    if (rule.rule.quarter_cal === quarters.AVERAGE) {
        return rule.average_actual.reduce((a, b) => (a + b)) / rule.average_actual.length
    }
    if (rule.rule.quarter_cal === quarters.LAST_MONTH) {
        return rule.average_actual[rule.average_actual.length - 1]
    }
    if (rule.rule.quarter_cal === quarters.SUM) {
        return rule.average_actual.reduce((a, b) => (a + b))
    }
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
var score_findActualPercent = (element, array) => {
    let result = 0.00
    if (element.rule.parent) {
        let parent = array.find(item => item.rule_id === element.rule.parent)
        if (element.rule.calculate_type === calculate.POSITIVE) {
            result = element.actual > parent.actual ? 0.00 : element.actual === 0.00 ? 0.00 : (element.actual / parent.actual) * 100
        }
        if (element.rule.calculate_type === calculate.NEGATIVE) {
            result = parent.actual > element.actual ? (element.actual / parent.actual) * 100 : 0.00
        }
        if (element.rule.calculate_type === calculate.ZERO) {
            // ไม่มี
            result = element.actual <= parent.actual ? 100.00 : 0.00
        }
    } else {
        // result = (element.actual / (element.target === 0) ? 1 : element.target) * 100
        if (element.rule.calculate_type === calculate.POSITIVE) {
            result = element.actual > element.target ? 100.00 : element.actual === 0.00 ? 0.00 : (element.actual / element.target) * 100
        }
        if (element.rule.calculate_type === calculate.NEGATIVE) {
            result = element.actual > element.target ? ((element.actual / element.target) * 100) : 100.00
        }
        if (element.rule.calculate_type === calculate.ZERO) {
            result = element.actual <= element.target ? 100.00 : 0.00
        }
    }
    return element.actual_pc = result
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
var score_findTargetPercent = (element, array) => {

    if (element.rule.parent) {
        let parent = array.find(item => item.rule_id === element.rule.parent)
        let target = element.target_config ?? element.target
        let parent_target = parent.target_config ?? parent.target
        if (parent) {
            let result = target > parent_target ? 0.00 : target === 0.00 && parent_target === 0.00 ? 0.00 : (target / parent_target) * 100
            element.target_pc = result
        }
    } else {
        element.target_pc = 100.00
    }
    return element.target_pc
}


const score_findAchValue = (obj) => {
    if (typeof obj === `object`) {
        if (!obj.rule.parent) {
            // ใช้ amount หา
            if (obj.rule.calculate_type === calculate.POSITIVE) {
                if (obj.target === 0.00 && obj.actual > obj.target) {
                    ach = obj.max
                } else if (obj.actual === 0.00) {
                    ach = 0.00
                } else {
                    ach = parseFloat((obj.actual / obj.target) * 100.00)
                }
                // ach = obj.actual >= obj.target ? obj.max : obj.actual === 0.00 ? 0.00 : parseFloat((obj.actual / obj.target) * 100.00)
            }
            if (obj.rule.calculate_type === calculate.NEGATIVE) {
                let dd = (obj.actual / obj.target)
                if (dd === -Infinity) {
                    dd = 0
                }
                // console.log(obj.actual);
                // if (obj.actual !== 0.00) {
                //     if (obj.actual < obj.target) {
                //         ach = obj.max_result ?? obj.max
                //     } else {
                ach = parseFloat((2 - dd) * 100.00)
                // console.log(obj.rules.name,ach);
                // }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual !== 0.00 ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 2
                // ach = obj.actual > obj.target ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 1
            }
            if (obj.rule.calculate_type === calculate.ZERO) {
                ach = obj.actual <= obj.target ? 100.00 : 0.00
            }
        } else {
            // ใช้ % หา
            if (obj.rule.calculate_type === calculate.POSITIVE) {
                if (obj.target_pc === 0.00 && obj.actual_pc > obj.target_pc) {
                    ach = obj.max
                } else if (obj.actual_pc === 0.00) {
                    ach = 0.00
                } else {
                    ach = parseFloat((obj.actual_pc / obj.target_pc) * 100.00)
                }
                // ach = obj.actual_pc >= obj.target_pc ? obj.max : parseFloat((obj.actual_pc / obj.target_pc) * 100)
            }
            if (obj.rule.calculate_type === calculate.NEGATIVE) {
                // console.log(obj.actual_pc , obj.target_pc);
                let dd = (obj.actual_pc / obj.target_pc)
                if (dd === -Infinity) {
                    dd = 0
                }
                ach = parseFloat((2 - dd) * 100.00)
                // if (obj.actual_pc !== 0.00) {
                //     if (obj.actual_pc < obj.target_pc) {
                //         ach = obj.max ?? obj.max_result
                //     } else {
                // ach = parseFloat((2 - dd ) * 100.00)
                // console.log(obj.rules.name,ach);
                //     }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual_pc !== 0.00 ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : 0.00  #version 2
                // ach = obj.actual_pc > obj.target_pc ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : obj.max  #version 1
            }
            if (obj.rule.calculate_type === calculate.ZERO) {
                ach = obj.actual_pc <= obj.target_pc ? 100.00 : 0.00
            }
        }
    }
    if (typeof obj === `number`) {
        ach = obj
    }
    return isNaN(ach) || (ach === Infinity || ach === -Infinity) ? 0.00 : ach
}

var score_findCalValue = (obj, ach) => {
    // console.log(obj,ach);
    if (ach < obj.base_line) {
        cal = 0.00
    } else {
        if ('max_result' in obj) {
            if (ach >= obj.max_result) {
                cal = parseFloat(obj.max_result) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }
        if ('max' in obj) {
            if (ach >= obj.max) {
                cal = parseFloat(obj.max) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }

    }
    return isNaN(cal) || (cal === Infinity) ? 0.00 : cal
}


/**
 * @params {element} document.getElementById
 * @params {EvaluateDetail} EvaluateDetail
 * @return void
 */
var score_setTooltipAch = (e, data) => {
    if (data.rule.calculate_type === calculate.POSITIVE) {
        if (data.target_pc === 100) {
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Positive : (actual amount / target amount) * 100",
                "data-placement": "top"
            })
        } else {
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Positive : (Actual % / Target %) * 100",
                "data-placement": "top"
            })
        }

    }
    if (data.rule.calculate_type === calculate.NEGATIVE) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Negative : (2 - (actual amount / target amount)) * 100",
            "data-placement": "top"
        })
    }
    if (data.rule.calculate_type === calculate.ZERO) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Zero Oriented KP : actual amount == target amount ? 100.00 : 0.00",
            "data-placement": "top"
        })
    }
}

/**
 * @params {element} document.getElementById
 * @params {EvaluateDetail} EvaluateDetail
 * @return void
 */
var score_setTooltipCal = (e, data) => {
    if (data.ach < data.base_line) {
        setAttributes(e, {
            "data-toggle": "tooltip",
            "title": "Ach% < Base Line : Cal = 0.00",
            "data-placement": "top"
        })
    } else {
        if (data.ach >= data.max_result) {
            // cal = parseFloat(obj.max_result) * parseFloat(obj.weight) / 100
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "Ach% >= Max  = (Max * Weight) / 100",
                "data-placement": "top"
            })
        } else {
            // cal = ach * parseFloat(obj.weight) / 100
            setAttributes(e, {
                "data-toggle": "tooltip",
                "title": "(Ach% * Weight) / 100",
                "data-placement": "top"
            })
        }
    }
}

var getQuarter = (date) => {
    var month = date.getMonth()
    return (Math.ceil(month / 3))
}

var getQuarterForHaier = (date) => {
    if (month_q1.includes(date.getMonth())) {
        return 1
    }
    if (month_q2.includes(date.getMonth())) {
        return 2
    }
    if (month_q3.includes(date.getMonth())) {
        return 3
    }
    if (month_q4.includes(date.getMonth())) {
        return 4
    }
    return 0
}
