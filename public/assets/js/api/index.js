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

const postEvaluateForm = (staff,period,form) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate`,
    data: form
})

const putEvaluateForm = (staff,period,evaluate,form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`,
    data: form
})

const getEvaluateForm = (staff,period,evaluate) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`
})


const putEvaluateSelf = (self_evaluation,form) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/kpi/self-evaluation/${self_evaluation}`,
    data: form
})

const getEvaluateSelf = (staff,period,evaluate) => axios({
    method: 'GET',
    responseType: 'json',
    url: `/kpi/evaluation-form/staff/${staff}/edit/period/${period}/evaluate/${evaluate}`
})
