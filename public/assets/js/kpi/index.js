const calculate = {
    POSITIVE: "Positive",
    NEGATIVE: "Negative",
    ZERO: "Zero Oriented KPI"
}


// KPI
var formulaRuleDetail = (e, key) => {
    let index = e.offsetParent.cellIndex
    let tr = e.offsetParent.parentNode
    let foot = tr.parentNode.parentNode.tFoot
    let actual = parseFloat(tr.cells[index].firstChild.value)
    let target = parseFloat(tr.cells[index - 1].firstChild.textContent)
    formEvaluate.form.evaluate_detail[key].actual = e.value

    if (formEvaluate.form.evaluate_detail[key].rule.calculate_type !== null) {
        let ach = 0.00
        let cal = 0.00
        // หา %Ach
        if (formEvaluate.form.evaluate_detail[key].rule.calculate_type === calculate.POSITIVE) {
            ach = parseFloat((actual / target) * 100)
        }
        if (formEvaluate.form.evaluate_detail[key].rule.calculate_type === calculate.NEGATIVE) {
            ach = parseFloat((2 - (actual / target)) * 100)
        }
        if (formEvaluate.form.evaluate_detail[key].rule.calculate_type === calculate.ZERO) {
            ach = actual <= target ? 100.00 : 0.00
        }
        tr.cells[index + 1].firstChild.textContent = ach.toFixed(2) + '%'
        formEvaluate.form.evaluate_detail[key].ach = ach

        // หา %Cal
        if (ach < 70.00) {
            cal = 0.00
        } else if (ach > formEvaluate.form.evaluate_detail[key].base_line) {
            let c = parseFloat(formEvaluate.form.evaluate_detail[key].base_line) * parseFloat(formEvaluate.form.evaluate_detail[key].weight) / 100
            cal = c
        } else {
            let d = ach * parseFloat(formEvaluate.form.evaluate_detail[key].weight) / 100
            cal = d
        }
        tr.cells[index + 2].firstChild.textContent = cal.toFixed(2) + '%'
        formEvaluate.form.evaluate_detail[key].cal = cal
        

        // total %Ach & %Cal
        let sumCal = formEvaluate.form.evaluate_detail.filter(item => {
            return item.rule.category.name === formEvaluate.form.evaluate_detail[key].rule.category.name
        }).reduce(function (total, currentValue) {
            return total + currentValue.cal
        }, 0)

        let sumAch = formEvaluate.form.evaluate_detail.filter(item => {
            return item.rule.category.name === formEvaluate.form.evaluate_detail[key].rule.category.name
        }).reduce(function (total, currentValue) {
            return total + currentValue.ach
        }, 0)

        foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 1].textContent = parseFloat(sumCal).toFixed(2) + '%'
        foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 2].textContent = parseFloat(sumAch).toFixed(2) + '%'

        // Calculation Summary
        if (main.id === formEvaluate.form.evaluate_detail[key].id) {
            document.getElementById('Cal').value = parseFloat(formEvaluate.form.evaluate_detail[key].cal).toFixed(2) + '%'
        }
        let table = document.getElementById('table-calculation')
        let body = table.tBodies[0].rows
        let total_summary = 0.00
        for (let index = 0; index < body.length; index++) {
            const element = body[index];
            let total_text = element.cells[element.childElementCount-1].textContent
            if (element.firstElementChild.textContent === formEvaluate.form.evaluate_detail[key].rule.category.name) {
                let weight = element.cells[1].textContent.substring(0, element.cells[1].textContent.length - 1)
                element.cells[2].textContent = parseFloat(sumAch).toFixed(2) + '%'
                element.cells[3].textContent = (parseFloat(sumAch) * parseFloat(weight) / 100).toFixed(2) + '%'
            }
            total_summary += parseFloat(total_text.substring(0,total_text.length - 1))
        }
        table.tFoot.rows[0].cells[table.tFoot.rows[0].childElementCount-1].textContent = total_summary + '%'

    } else {
        formEvaluate.form.evaluate_detail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }
}