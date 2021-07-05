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

// Dashboard report
const getOperationReportScore = (filter) => axios.get('/kpi/operation/reportscore', filter)

const getWeigthConfig = (filter) => axios.get('/kpi/weigth/config', filter)

const postUploadFile = (form, config) => axios.post('/upload', form, config)

const evaluate_excel = (filter) => axios.get('/kpi/evaluate-report', filter)

const getReportStaffEvaluate = (year) => axios.get(`/kpi/dashboard/staff-evaluate-of-year/${year}/report`)

const getReportRuleOfYear = (year) => axios.get(`/kpi/dashboard/rule-of-year/${year}/report`)
// 

// for eddy page 
const getDeadLine = () => axios.get(`/kpi/for-eddy/config/deadline/dropdown`)

const getUserSettingAction = (id) => axios.get(`/kpi/for-eddy/user/actions/${id}`)

const getOperation = () => axios.get(`/operations`)

const postAttachAction = (form,id) => axios.post(`/kpi/for-eddy/attach/action/${id}`,form)

const postDetachAction = (form,id) => axios.post(`/kpi/for-eddy/detach/action/${id}`,form)

const getUserEvaluate = (filter) => axios.get(`/kpi/for-eddy/user/evaluates`,filter)

// Operation

// API
const addRoleApi = (user, roles) => {
    return axios.post(`/admin/${user}/addrole`, {
        roles: roles
    })
}

const removeRoleApi = (user, role) => {
    return axios.delete(`/admin/${user}/removerole`, {
        data: {
            role: role
        }
    })
}

const addSystemApi = (user, system) => {
    console.log(system)
    return axios.post(`/admin/${user}/addsystem`, {
        system: system
    })
}

const removeSystemApi = (user, system) => {
    return axios.delete(`/admin/${user}/removesystem`, {
        data: {
            system: system
        }
    })
}

const postUserApproveKPI = (form,id) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/admin/create/user/${id}/approve`,
    data: form
})

const putUserApproveKPI = (form,id) => axios({
    method: 'PUT',
    responseType: 'json',
    url: `/admin/update/user/${id}/approve`,
    data: form
})

const deleteUserApproveKPI = (form, id) => axios({
    method: 'DELETE',
    responseType: 'json',
    url: `/admin/delete/user/${id}/approve`,
    data: form
})

const postUserCopyApproveKPI = (form,id) => axios({
    method: 'POST',
    responseType: 'json',
    url: `/admin/copy/user/${id}/approve`,
    data: form
})

// dropdows
const getusers = () => axios.get(`/users/dropdown`)