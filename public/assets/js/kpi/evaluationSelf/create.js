(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $('#template').select2({
            placeholder: 'Select Template...',
            tags: true,
            allowClear: true
        }).on('select2:close', function () {
            var element = $(this)
            var new_category = $.trim(element.val())
            if (isNaN(new_category)) {
                postTemplate({
                        name: new_category
                    })
                    .then(res => {
                        template = res.data.data
                        element.append('<option value="' + template.id + '">' + template.name + '</option>').val(template.id)
                        evaluateForm.template = template.id
                    })
                    .catch(error => {
                        console.log(error.response.data)
                    })
                    .finally(() => {
                        if (template && evaluateForm.template) {
                            getDataByTemplate(evaluateForm.template)
                        }
                    })
            }
        })

        if ($('#template').val()) {
            document.getElementsByClassName('collapse')[0].classList.add('show')
            getTemplate($('#template').val())
                .then(res => {
                    if (res.status === 200) {
                        template = res.data.data
                        evaluateForm.template = template.id
                    }
                })
                .catch(error => {
                    toast(error.response.data.message, error.response.data.status)
                    console.log(error.response.data)
                })
                .finally(() => {
                    if (template && evaluateForm.template) {
                        getDataByTemplate(evaluateForm.template)
                    }
                })
        }


        $("#period").select2({
            placeholder: 'Select Period name...',
            allowClear: true
        })

        $("#year").select2({
            placeholder: 'Select Year...',
            allowClear: true
        })

        $("#rule_name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#rule-modal')
        })


    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation')
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false)

})()


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

                let cellBaseLine = newRow.insertCell()
                cellBaseLine.appendChild(newInput('number', className, 'base_line', element.base_line, '', `changeValue(this)`))

                let cellMax = newRow.insertCell()
                cellMax.appendChild(newInput('number', className, 'max', element.max, '', `changeValue(this)`))

                let cellWeight = newRow.insertCell()
                cellWeight.appendChild(newInput('number', className, 'weight', element.weight, '', `changeValue(this)`))

                let cellTarget = newRow.insertCell()
                cellTarget.appendChild(newInput('number', className, 'target', element.target, '', `changeValue(this)`))

                let cellActual = newRow.insertCell()
                // ถ้าเป็นเจ้าของ rule หรือเป็นหน้า evaluation-review ไม่ต้อง readonly
                // let readonly = auth === element.rules.user_actual.id || window.location.pathname.includes("evaluation-review") ? false : true
                cellActual.appendChild(newInput('number', className, 'actual', element.actual, '', `changeValue(this)`))

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
        table.tFoot.lastElementChild.cells[8].textContent = `${sum_ach.toFixed(2)}%`
        table.tFoot.lastElementChild.cells[9].textContent = `${sum_cal.toFixed(2)}%`
        summary.push({
            'weight': parseFloat(head_weight.value),
            'cal': parseFloat(sum_cal),
            'category': table.id.substring(6)
        })

        $('[data-toggle="tooltip"]').tooltip()
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
    }
    summary_table.tFoot.rows[0].cells[2].textContent = `${total.toFixed(2)} %`
    summary_table.tFoot.rows[0].cells[1].textContent = `${sum_weight.toFixed(2)} %`
}

var getDataByTemplate = (template_id) => {
    setVisible(true)
    getRuleTemplate(template_id)
        .then(res => {
            if (res.status === 200) {
                setDetail(res.data.data)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data)
        })
        .finally(() => {
            // pageEnable()
            setVisible(false)
            toastClear()
            render_html()
        })
}

const changeTemplate = (e) => {
    if (e.selectedIndex > 0) {
        document.getElementsByClassName('collapse')[0].classList.add('show')
        if (!isNaN(parseInt(e.options[e.selectedIndex].value))) {
            evaluateForm.template = parseInt(e.options[e.selectedIndex].value)
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
                    if (template && evaluateForm.template) {
                        getDataByTemplate(evaluateForm.template)
                    }
                })
        }
    } else {
        document.getElementsByClassName('collapse')[0].classList.remove('show')
        evaluateForm.template = null
    }
}

const submitToManager = () => {
    let validation = validationFormApi(document.getElementById('create-evaluate'))
    if (validation) {
        setVisible(true)
        evaluateForm.next = true
        postEvaluateSelf($("#period").val(), $("#year").val(), evaluateForm)
            .then(async res => {
                console.log(res)
                if (res.status === 200) {
                    toast(res.data.message, res.data.status)
                    setTimeout(function () {
                        window.location.replace(`${origin}${window.location.pathname.replace("evaluate",res.data.data.id)}/edit`)
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
    let validation = validationFormApi(document.getElementById('create-evaluate'))
    if (validation) {
        setVisible(true)
        postEvaluateSelf($("#period").val(), $("#year").val(), evaluateForm)
            .then(async res => {
                console.log(res)
                if (res.status === 200) {
                    toast(res.data.message, res.data.status)
                    setTimeout(function () {
                        window.location.replace(`${origin}${window.location.pathname.replace("evaluate",res.data.data.id)}/edit`)
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
                }
                table.tFoot.rows[0].cells[2].textContent = `${total.toFixed(2)} %`
                table.tFoot.rows[0].cells[1].textContent = `${sum_weight.toFixed(2)} %`

            }
        }
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
