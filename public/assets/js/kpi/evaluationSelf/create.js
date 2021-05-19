(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // $("#template").select2({
        //     placeholder: 'Select Template...',
        //     allowClear: true
        // });
        $('#template').select2({
            placeholder: 'Select Template...',
            tags: true,
            allowClear: true
        }).on('select2:close', function () {
            var element = $(this);
            var new_category = $.trim(element.val());
            if (isNaN(new_category)) {
                postTemplate({
                        name: new_category
                    })
                    .then(res => {
                        element.append('<option value="' + res.data.data.id + '">' + res.data.data.name + '</option>').val(res.data.data.id);
                    })
                    .catch(error => {
                        console.log(error.data.message);
                    })
            }

        });

        if ($('#template').val()) {
            document.getElementsByClassName('collapse')[0].classList.add('show')
            getDataByTemplate($('#template').val())
        }


        $("#period").select2({
            placeholder: 'Select Period name...',
            allowClear: true
        });

        $("#year").select2({
            placeholder: 'Select Year...',
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
    }, false);

})();
var evaluateForm = new EvaluateForm()

var render_html = (data) => {
    // create tr in table
    let tables = document.getElementsByClassName('table') //document.getElementsByClassName('app-main__inner')[0].querySelectorAll('table')
    let weightForSum = []
    let achForSum = []

    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        let temp_rules = evaluateForm.detail.filter(value => value.rules.categorys.name === table.id.substring(6))
        let sum_weight = 0.00
        let sum_ach = 0.00
        let sum_cal = 0.00
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
                cellName.classList.add('truncate')
                setAttributes(cellName, {
                    "data-toggle": "tooltip",
                    // "title": element.rules.name,
                    "data-placement": "top",
                    "data-original-title": `${element.rules.name}`
                })

                let cellDesc = newRow.insertCell()
                cellDesc.textContent = element.rules.description
                cellDesc.classList.add('truncate')
                setAttributes(cellDesc, {
                    "data-toggle": "tooltip",
                    // "title": `${element.rules.description}`,
                    "data-placement": "top",
                    "data-original-title": `${element.rules.description}`
                })

                let cellBaseLine = newRow.insertCell()
                cellBaseLine.textContent = element.base_line
                // cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line))

                let cellMax = newRow.insertCell()
                cellMax.textContent = element.max
                // cellMax.appendChild(newInput('number', className, 'max', element.max))

                let cellWeight = newRow.insertCell()
                cellWeight.textContent = element.weight + '%'
                // cellWeight.appendChild(newInput('number', className, 'weight', element.weight))

                let cellTarget = newRow.insertCell()
                cellTarget.textContent = element.target
                // cellTarget.appendChild(newInput('number', className, 'target', element.target))

                let cellActual = newRow.insertCell()
                // ถ้าเป็นเจ้าของ rule หรือเป็นหน้า evaluation-review ไม่ต้อง readonly
                // let readonly = auth === element.rules.user_actual.id || window.location.pathname.includes("evaluation-review") ? false : true
                cellActual.appendChild(newInput('number', className, 'actual', element.actual, '', `changeActualValue(this)`))

                let cellAch = newRow.insertCell()
                element.ach = findAchValue(element)
                cellAch.textContent = element.ach.toFixed(2) + '%'
                setTooltipAch(cellAch, element)

                let cellCal = newRow.insertCell()
                element.cal = findCalValue(element, element.ach)
                cellCal.textContent = element.cal.toFixed(2) + '%'
                setTooltipCal(cellCal, element)

                let cellCheck = newRow.insertCell()
                cellCheck.textContent = ""
            } catch (error) {
                console.log(error);
            }


        }
        // sum_weight = temp_rules.reduce((total, cur) => total += cur.weight, sum_weight)
        // table.tFoot.lastElementChild.cells[4].textContent = `${sum_weight.toFixed(2)}%`

        // sum_ach = temp_rules.reduce((total, cur) => total += cur.ach, sum_ach)
        // table.tFoot.lastElementChild.cells[7].textContent = `${sum_ach.toFixed(2)}%`

        // sum_cal = temp_rules.reduce((total, cur) => total += cur.cal, sum_cal)
        // table.tFoot.lastElementChild.cells[8].textContent = `${sum_cal.toFixed(2)}%`

        // weightForSum.push(parseFloat(sum_weight))
        // achForSum.push(parseFloat(sum_ach))

    }
}

var getDataByTemplate = (template) => {
    setVisible(true);
    getRuleTemplate(template)
        .then(res => {
            if (res.status === 200) {
                render_html(setDetail(res.data.data))
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data)
        })
        .finally(() => {
            // pageEnable()
            setVisible(false);
            toastClear()
        })
}

// dropdown
const changeTemplate = (e) => {

    if (e.selectedIndex > 0) {
        document.getElementsByClassName('collapse')[0].classList.add('show')
    } else {
        document.getElementsByClassName('collapse')[0].classList.remove('show')
    }
    evaluateForm.template = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
    if (evaluateForm.template) {
        getDataByTemplate(evaluateForm.template)
    } else {
        render_html([])
        // pageDisable(`button,input`)
    }
}

const submitToManager = () => {
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
        postEvaluateSelf(staff.id, period.id, evaluateForm)
            .then(async res => {
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

const changeActualValue = (e) => {
    console.log(e.parentNode.nextElementSibling);
    evaluateForm.detail.forEach((element, index) => {
        if (e.offsetParent.parentNode.cells[1].textContent === element.rules.name) {
            // create new method formula 
            formulaRuleDetail2(e, index)
        }
    })
}

var formulaRuleDetail2 = (e, key) => {
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
        // let table = document.getElementById('table-calculation')
        // let body = table.tBodies[0].rows
        // let total_summary = 0.00
        // for (let index = 0; index < body.length; index++) {
        //     const element = body[index]
        //     if (element.firstElementChild.textContent === evaluateForm.detail[key].rules.categorys.name) {
        //         let weight = element.cells[1].textContent.substring(0, element.cells[1].textContent.length - 1)
        //         element.cells[2].textContent = parseFloat(sumAch).toFixed(2) + '%'
        //         element.cells[3].textContent = (parseFloat(sumAch) * parseFloat(weight) / 100).toFixed(2) + '%'
        //     }
        //     total_summary += parseFloat(element.cells[3].textContent)
        // }
        // table.tFoot.rows[0].cells[table.tFoot.rows[0].childElementCount - 1].textContent = total_summary.toFixed(2) + '%'


    } else {
        evaluateForm.detail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }
}
