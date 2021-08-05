(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#rule_name").select2({
            placeholder: 'Select Rule',
            allowClear: true,
            dropdownParent: $('#modal-add-rule')
        });

        if (template) {
            // document.getElementById('all-table').classList.remove('hide')
            let elements = document.querySelectorAll('.hide')
            Array.from(elements).forEach((el) => el.classList.remove('hide'))
            render_table()
        }

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);
})();

var rename_template = async () => {
    let form = {
        name: document.getElementById('validationTemplate').value
    }
    try {
        let result = await putTemplateName(form, template.id)
        console.log(result.data.message)
        toast(result.data.message, result.data.status)
    } catch (error) {
        console.error(error)
        toast(error, 'error')
    } finally {
        toastClear()
    }
}

var render_table = () => {
    let tables = document.getElementById('all-table').querySelectorAll('table')
    for (let i = 0; i < tables.length; i++) {
        const table = tables[i]
        rule_temps = template.ruleTemplate.filter(item => item.rules.categorys.name === table.id.substr(6))

        removeAllChildNodes(table.tBodies[0])
        rule_temps.sort((a, b) => a.parent_rule_template_id - b.parent_rule_template_id)
        for (let r = 0; r < rule_temps.length; r++) {
            const rule = rule_temps[r]
            let row = table.tBodies[0].insertRow()
            let index = row.insertCell()
            index.textContent = r + 1

            let name = row.insertCell()
            name.textContent = rule.rules.name

            let base_line = row.insertCell()
            base_line.textContent = rule.rules.base_line.toFixed(2)

            let max_result = row.insertCell()
            max_result.textContent = rule.rules.max.toFixed(2)

            let check_box = row.insertCell()
            check_box.appendChild(make_check_box(rule))

            let sort = row.insertCell()
            sort.appendChild(make_select(rule, rule_temps))
        }
    }
}

let add_rule = () => {
    let form = {
        rule_id: $('.modal-body #rule_name').val()
    }
    postRuleTemplate(template.id, form)
        .then(res => {
            if (res.status === 200) {
                template = res.data.data
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            console.log(error);
            console.log(error, error.response.data)
            error.response.data.messages.forEach(value => {
                toast(value, 'error')
            })
        })
        .finally(() => {
            document.getElementById('modal-add-rule').getElementsByClassName("close")[0].click()
            render_table()
            toastClear()
        })
}

let remove = (table_id) => {
    let table = document.getElementById(table_id)
    let detach = []
    for (let r = 0; r < table.tBodies[0].rows.length; r++) {
        const row = table.tBodies[0].rows[r]
        if (row.cells[4].lastElementChild.firstElementChild.checked) {
            detach.push(parseInt(row.cells[4].lastElementChild.firstElementChild.id))
        }
    }
    deleteRuleTemplate(template.id, {
            rule: detach,
            group: {
                id: template.ruleTemplate.find(item => item.id === detach[0]).rules.category_id,
                name: template.ruleTemplate.find(item => item.id === detach[0]).rules.categorys.name
            }
        })
        .then(res => {
            if (res.status === 200) {
                template = res.data.data
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            console.log(error.response.data)
            error.response.data.messages.forEach(value => {
                toast(value, 'error')
            })
        })
        .finally(() => {
            render_table()
            toastClear()
        })
}

let make_check_box = (rule) => {
    let div = document.createElement('div'),
        checkbox = document.createElement('input'),
        label = document.createElement('label')

    div.className = 'custom-checkbox custom-control'
    checkbox.type = `checkbox`
    checkbox.name = `rule-${rule.id}`
    checkbox.className = `custom-control-input`
    checkbox.id = rule.id
    // checkbox.setAttribute('onclick', 'turnOnDeleteRule(this)')

    label.classList.add('custom-control-label')
    label.htmlFor = rule.id
    div.appendChild(checkbox)
    div.appendChild(label)
    return div
}

let make_select = (element, rule_temps) => {
    let select = document.createElement('select')
    select.name = `select-${element.id}`
    select.style = `text-align: center;`
    select.className = `form-control form-control-sm`
    select.setAttribute(`onchange`, `switchRow(this)`)
    for (let i = 0; i < rule_temps.length; i++) {
        const rule = rule_temps[i]
        let selected = rule.parent_rule_template_id === element.parent_rule_template_id
        select.appendChild(new Option(rule.parent_rule_template_id, rule.id, null, selected))
    }
    return select
}

let switchRow = (e) => {
    let from = template.ruleTemplate.find(item => item.id === parseInt(e.name.substr(7)))
    let to = parseInt(e.value)
    let formSwitch = {
        rule_template_id: from.id,
        rule_to_id: to,
        group_id: from.rules.category_id
    }

    switRuleTemplate(template.id, formSwitch)
        .then(res => {
            if (res.status === 200) {
                template = res.data.data
                toast(res.data.message, res.data.status)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            render_table()
            toastClear()
        })
}

// modal method
$('#modal-add-rule').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    let reload = modal.find('.modal-body #reload'),
        select = modal.find('.modal-body #rule_name'),
        new_rules = []
    getRuleDropdown(group)
        .then(response => {
            if (response.status === 200) {
                if (template.ruleTemplate.length > 0) {
                    new_rules = response.data.data.filter(value => {
                        let rre = true
                        template.ruleTemplate.forEach(item => {
                            if (item.rule_id === value.id) {
                                rre = !rre;
                            }
                        })
                        return rre
                    })
                } else {
                    new_rules = response.data.data
                }
            }
        })
        .catch(error => {
            console.log(error);
            console.log(error.response.data)
        })
        .finally(() => {
            for (let i = 0; i < new_rules.length; i++) {
                const rule = new_rules[i];
                select[0].add(new Option(rule.name, rule.id))
            }
            reload.removeClass('reload')
        })

    // fetch rules filter
})

$('#modal-add-rule').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)

    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #rule_name')[0])
    modal.find('.modal-body #reload').addClass('reload')
})
