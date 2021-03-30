(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();



// KPI
var formulaRuleDetail = (e, key) => {
    let index = e.offsetParent.cellIndex
    let tr = e.offsetParent.parentNode
    let foot = tr.parentNode.parentNode.tFoot
    let actual = parseFloat(tr.cells[index].firstChild.value)
    let target = parseFloat(tr.cells[index - 1].firstChild.textContent)

    if (formEvaluate.form.evaluate_detail[key].rule.calculate_type !== null) {
        let ach = parseFloat((actual / target) * 100)
        formEvaluate.form.evaluate_detail[key].actual = e.value
        // รอ ตัวแปรเพิ่มจากพี่ปัด เพื่อเขียนสูตร
        tr.cells[index + 1].firstChild.textContent = ach.toFixed(2) + '%'
        formEvaluate.form.evaluate_detail[key].ach = ach

        if (formEvaluate.form.evaluate_detail[key].rule.calculate_type === 'Amount') {
            tr.cells[index + 1].firstChild.textContent = actual <= actual ? `100%` : `0.00%`
            // ach.toFixed(2) + '%'
            formEvaluate.form.evaluate_detail[key].ach = actual <= actual ? 100 : 0
        }
        if (formEvaluate.form.evaluate_detail[key].rule.calculate_type === 'Percent') {
            tr.cells[index + 1].firstChild.textContent = (2 - (actual/target) ) * 100
            // actual.toFixed(2) + '%'
            formEvaluate.form.evaluate_detail[key].ach = (2 - (actual/target) ) * 100
        }

        if (formEvaluate.form.evaluate_detail[key].ach < 70) {
            tr.cells[index + 2].firstChild.textContent = '0.00%'
            formEvaluate.form.evaluate_detail[key].cal = 0
        } else if (formEvaluate.form.evaluate_detail[key].ach > formEvaluate.form.evaluate_detail[key].base_line) {
            let c = parseFloat(formEvaluate.form.evaluate_detail[key].base_line) * parseFloat(formEvaluate.form.evaluate_detail[key].weight) / 100
            tr.cells[index + 2].firstChild.textContent = c.toFixed(2) + '%'
            formEvaluate.form.evaluate_detail[key].cal = c
        } else {
            let d = formEvaluate.form.evaluate_detail[key].ach * parseFloat(formEvaluate.form.evaluate_detail[key].weight) / 100
            tr.cells[index + 2].firstChild.textContent = d.toFixed(2) + '%'
            formEvaluate.form.evaluate_detail[key].cal = d
        }

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

        for (let index = 0; index < body.length; index++) {
            const element = body[index];
            if (element.firstElementChild.textContent === formEvaluate.form.evaluate_detail[key].rule.category.name) {
                let weight = element.cells[1].textContent
                element.cells[2].textContent = parseFloat(sumAch).toFixed(2) + '%'
                element.cells[3].textContent = (parseFloat(sumAch) * parseFloat(weight.substring(0,weight.length-1)) / 100).toFixed(2) + '%'
            }
        }

    } else {
        formEvaluate.form.evaluate_detail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }


    // console.log(formEvaluate.form.evaluate_detail[key].rule)
    // console.log(tr.cells[index],tr.cells[index-1]);
}
