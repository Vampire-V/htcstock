function status(response) {
    if (response.status >= 200 && response.status < 300) {
        return Promise.resolve(response)
    } else {
        return Promise.reject(new Error(response.statusText))
    }
}

function json(response) {
    return response.json()
}

const validationFormApi = (forms) => {
    let result = true
    Array.prototype.filter.call(forms, function (form) {
        if (form.checkValidity() === false) {
            result = false
        }
        form.offsetParent.classList.add('was-validated')
    })
    return result
}


const getAccessoriesId = id => fetch("/api/accessorie/" + id + "/checkstock").then(status).then(json)
// const getAccessoriesAvailable = () => fetch("/accessorie/available").then(status).then(json)



const clearModal = (modal) => {
    modal.find('.modal-body form')[0].reset()
    for (const _el of modal.find('.form-control')) {
        _el.disabled = false
    }
    modal.find('.modal-body form')[0].action = window.location.pathname
    modal.find('#methodPut').remove()
}


function ISOtoLongDate(isoString, locale = "en-US") {
    const options = {
        month: "long",
        day: "numeric",
        year: "numeric"
    };
    const date = new Date(isoString);
    return new Intl.DateTimeFormat(locale, options).format(date);
}

function ISOtoDate(isoString, locale = "en-US") {
    const options = {
        month: "numeric",
        day: "numeric",
        year: "numeric"
    };
    const date = new Date(isoString);
    return new Intl.DateTimeFormat(locale, options).format(date)
}


// api KPI-System

const getRuleDropdown = (group) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/rule-dropdown/${group.id}`
})

const getRule = (rule) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/rule-list/${rule}`
})

const postRuleTemplate = (template, form) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/template/${template}/edit/rule-template`,
    data: form
})

const getRuleTemplate = (template) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/template/${template}/edit/ruletemplate/bytemplate`
})

const switRuleTemplate = (template, form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/template/${template}/edit/ruletemplate/switch`,
    data: form
})

const deleteRuleTemplate = (template, form) => axios({
    method: 'DELETE',
    responseType: 'json',
    url: `/kpi/template/${template}/edit/ruletemplate/destroy`,
    data: form
})

const postEvaluateForm = (staff, period, form) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate`,
    data: form
})

const putEvaluateForm = (staff, period, evaluate, form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`,
    data: form
})

const getEvaluateForm = (staff, period, evaluate) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`
})

const putEvaluateSelf = (self_evaluation, form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/self-evaluation/${self_evaluation}`,
    data: form
})

const getEvaluateSelf = (staff, period, evaluate) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`
})

const putEvaluateReview = (review_evaluation, form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/evaluation-review/${review_evaluation}`,
    data: form
})

const putSetActual = (form, id) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/set-actual/${id}`,
    data: form
})

const putSetActualForEddy = (form, id) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/for-eddy/${id}`,
    data: form
})

const putSetAchForEddy = (form, id) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/for-eddy/${id}/updateAch`,
    data: form
})

const postRuleUpload = (form) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/rule-list/upload`,
    data: form
})

const postTemplate = (from) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/template/dynamic`,
    data: from
})

const putTemplate = (from, id) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/template/${id}/dynamic`,
    data: from
})

const postEvaluateSelf = (period, year, form) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/self-evaluation/evaluate`,
    data: {
        'period': period,
        'year': year,
        'form': form
    }
})

const getTemplate = (id) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/template/${id}`
})

const getOperationReportScore = (params) => axios.get('/kpi/operation/reportscore', params)

const postUploadFile = (form, config) => axios.post('/upload', form, config)
