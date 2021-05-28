(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationTemplate").select2({
            placeholder: 'Select Template...',
            allowClear: true
        });

        $("#rule-name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#rule-modal')
        });
        // $("#validationTemplate").val(evaluate.template_id);
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        console.log('tao... edit');
        getEvaluateForm(staff.id, period.id, evaluate.id)
            .then(async res => {
                if (res.status === 200) {
                    await setEvaluateForm(res.data.data)
                    await displayDetail(evaluateForm)
                }
            })
            .catch(error => {
                console.log(error.response.data.message)
                toast(error.response.data.message, 'error')
            })
            .finally(() => {
                if (evaluate.status === status.NEW || evaluate.status === status.SUBMITTED) {
                    pageEnable()
                } else {
                    pageDisable()
                }
                toastClear()
            })
    }, false);
})();
var evaluateForm = new EvaluateForm()


// dropdown
const changeTemplate = (e) => {
    evaluateForm.template = e.selectedIndex > 0 ? parseInt(e.options[e.selectedIndex].value) : null
    if (evaluateForm.template) {
        setVisible(true)
        getRuleTemplate(evaluateForm.template)
            .then(res => {
                if (res.status === 200) {
                    let rule_temp = res.data.data
                    displayDetail(setDetail(rule_temp))
                }
            })
            .catch(error => {
                toast(error.response.data.message, 'error')
                console.log(error.response.data)
            })
            .finally(() => {
                pageEnable()
                setVisible(false)
                toastClear()
            })
    } else {
        displayDetail([])
        pageDisable(`button,input`)
    }
}

const submitToUser = () => {

    validityForm()
    if (evaluateForm.template) {
        setVisible(true)
        evaluateForm.next = true
        putEvaluateForm(staff.id, period.id, evaluate.id, evaluateForm).then(res => {
            if (res.status === 201) {
                document.getElementsByClassName('app-main__inner')[0].querySelector('.badge').textContent = res.data.data.status
                if (res.data.data.status === status.READY || res.data.data.status === status.APPROVED) {
                    pageDisable()
                } else {
                    pageEnable()
                }
                toast(res.data.message, res.data.status)
            }
        }).catch(error => {
            toast(error.response.data.message, error.response.data.status)
            console.log(error.response.data)
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
        putEvaluateForm(staff.id, period.id, evaluate.id, evaluateForm).then(async res => {
            if (res.status === 201) {
                toast(res.data.message, res.data.status)
            }
        }).catch(error => {
            console.log(error.response.data)
            toast(error.response.data.message, error.response.data.status)
        }).finally(() => {
            setVisible(false)
            toastClear()
        })
    }
}

const deleteRuleTemp = (e) => {
    let table = e.offsetParent.offsetParent.querySelector('table'),
        body = table.tBodies[0]
    removeDetailIndex = []
    for (let index = 0; index < body.rows.length; index++) {
        const element = body.rows[index].lastChild.lastChild.firstChild
        if (element.checked) {
            let indexDetail = evaluateForm.detail.findIndex(object => object.rule_id === parseInt(element.id))
            removeDetailIndex.push(indexDetail)
        }
    }
    // remove detail temp

    evaluateForm.detail = evaluateForm.detail.filter((value, index) => removeDetailIndex.indexOf(index) == -1)
    displayDetail(evaluateForm)
}

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

const dropdownRule = (category, modal) => {
    let select = modal.find('.modal-body #rule-name')[0]
    let rule_keytask = evaluateForm.detail.filter(value => value.rules.category_id === category.id)
    getRuleDropdown(category)
        .then(res => {
            if (res.status === 200) {
                let rules = res.data.data.filter(obj => rule_keytask.some(r => r.rule_id === obj.id) ? null : obj)
                select.add(new Option('', '', false, false))
                for (let index = 0; index < rules.length; index++) {
                    const element = rules[index];
                    select.add(new Option(element.name, element.id, false, false))
                }
            }
        })
        .catch(error => {
            console.log(error.response.data);
            toast(error.response.data.message, error.response.data.status)
            toastClear()
        })
        .finally()
}

const addKeyTask = (e) => {
    let select = e.offsetParent.querySelector('select')
    // Fetch rule API and add to detail temp
    getRule(select.options[select.selectedIndex].value)
        .then(res => {
            if (res.status === 200) {
                let row = evaluateForm.detail.find(obj => obj.rules.category_id === res.data.data.category_id)
                let detail = new EvaluateDetail()
                detail.evaluate_id = row.evaluate_id
                detail.rule_id = res.data.data.id
                detail.rules = Object.create(res.data.data)
                detail.target = row.target
                detail.max = row.max
                detail.weight = row.weight
                detail.weight_category = row.weight_category
                detail.base_line = row.base_line
                evaluateForm.detail.push(detail)
            }
        })
        .catch(error => {
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            displayDetail(evaluateForm)
            e.offsetParent.querySelector('.close').click()
            toastClear()
        })


}
