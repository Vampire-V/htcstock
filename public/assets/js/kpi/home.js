document.addEventListener("DOMContentLoaded", function () {
    //The first argument are the elements to which the plugin shall be initialized
    //The second argument has to be at least a empty object or a object with your desired options

    OverlayScrollbars(document.getElementsByClassName('table-responsive'), {
        className: "os-theme-dark",
        resize: "both",
        sizeAutoCapable: true,
        clipAlways: true,
        normalizeRTL: true,
        paddingAbsolute: false,
        autoUpdate: null,
        autoUpdateInterval: 33,
        updateOnLoad: ["img"],
        nativeScrollbarsOverlaid: {
            showNativeScrollbars: false,
            initialize: true
        },
        overflowBehavior: {
            x: "scroll",
            y: "scroll"
        },
        scrollbars: {
            visibility: "auto",
            autoHide: "never",
            autoHideDelay: 800,
            dragScrolling: true,
            clickScrolling: false,
            touchSupport: true,
            snapHandle: false
        },
        textarea: {
            dynWidth: false,
            dynHeight: false,
            inheritedAttrs: ["style", "class"]
        },
        callbacks: {
            onInitialized: null,
            onInitializationWithdrawn: null,
            onDestroyed: null,
            onScrollStart: null,
            onScroll: null,
            onScrollStop: null,
            onOverflowChanged: null,
            onOverflowAmountChanged: null,
            onDirectionChanged: null,
            onContentSizeChanged: null,
            onHostSizeChanged: null,
            onUpdated: null
        }
    });

    let active_tab = localStorage.getItem('tab-dashboard')
    if (!active_tab) {
        window.localStorage.setItem('tab-dashboard', `tab-c-1`)
        active_tab = localStorage.getItem('tab-dashboard')
    }
    let ele_active = document.getElementById(active_tab)
    let scope = ele_active.parentNode.parentNode.parentNode
    for (let index = 0; index < scope.firstElementChild.children.length; index++) {
        const element = scope.firstElementChild.children[index];
        if (element.firstElementChild.id === active_tab) {
            element.firstElementChild.classList.add('active')
            content_id = element.firstElementChild.href.substring(element.firstElementChild.href.search("#") + 1, element.firstElementChild.href.length)
        } else {
            element.firstElementChild.classList.remove('active')
        }
    }
    if (content_id) {
        let contents = document.getElementById(content_id).parentElement
        for (let index = 0; index < contents.children.length; index++) {
            const element = contents.children[index];
            if (content_id === element.id) {
                element.classList.add('active')
            } else {
                element.classList.remove('active')
            }
        }
    }
    if (active_tab === 'tab-c-0') {
        make_options()
        search_score()
    } else {
        render_rule()
        render_staff_evaluate()
    }
});

let weigth_template = []

const tabActive = (e) => {
    window.localStorage.setItem('tab-dashboard', e.id)
    let active_tab = localStorage.getItem('tab-dashboard')
    if (active_tab === `tab-c-0`) {
        make_options()
        search_score()
    }
    if (active_tab === `tab-c-1`) {
        render_rule()
        render_staff_evaluate()

    }
}

// tab-c-0 method
document.getElementById('customSwitch1').addEventListener('click', () => {
    search_score()
})

const search_score = async () => {
    month_quarter()
    let score = []
    let checked = document.getElementById('customSwitch1').checked
    let param
    if (checked) {
        param = {
            quarter: $("#quarter").val() === '' ? [1, 2, 3, 4] : [$("#quarter").val()],
            year: [$("#year").val()],
            degree: [$("#degree").val()]
        }
    } else {
        param = {
            month: [$("#period").val()],
            year: [$("#year").val()],
            degree: [$("#degree").val()]
        }
    }
    let table = document.getElementById('table-report-score')
    table.previousElementSibling.classList.add('reload')
    try {
        let fetch_data = await getOperationReportScore({
            params: param
        })
        score = await combine_information(fetch_data.data.data).sort((a, b) => b.score - a.score)
    } catch (error) {
        console.error(error)
        toast(error, 'error')
    } finally {
        render_score(score)
        toastClear()
    }
}

let combine_information = (fetch_data) => {
    let data = [],
        average_omg

    fetch_data.sort(function (a, b) {
        var keyA = a.period_id,
            keyB = b.period_id;
        // Compare the 2 dates
        if (keyA < keyB) return -1;
        if (keyA > keyB) return 1;
        return 0;
    })

    try {
        if (document.getElementById('customSwitch1').checked) {
            if ($("#quarter").val() === '') {
                average_omg = getQuarterForHaier(new Date())
            }
            let item_unique,
                kpi_reduce_point,
                keytask_reduce_point,
                omg_reduce_point

            item_unique = []
            kpi_reduce_point = 0
            keytask_reduce_point = 0
            omg_reduce_point = 0
            for (let index = 0; index < fetch_data.length; index++) {
                const evaluate = fetch_data[index]

                kpi_reduce_point += evaluate.kpi_reduce
                keytask_reduce_point += evaluate.key_task_reduce
                omg_reduce_point += evaluate.omg_reduce
                if (item_unique.length < 1) {
                    item_unique.push(evaluate)
                } else {
                    let i = item_unique.findIndex(t => t.user_id === evaluate.user_id)
                    if (i < 0) {
                        item_unique.push(evaluate)
                    } else {
                        item_unique[i].evaluate_detail = item_unique[i].evaluate_detail.concat(evaluate.evaluate_detail)
                    }
                }
            }

            for (let index = 0; index < item_unique.length; index++) {
                const element = item_unique[index]
                let kpi = element.evaluate_detail.filter(item => item.rule.category.name === `kpi`)
                let key_task = element.evaluate_detail.filter(item => item.rule.category.name === `key-task`)
                let omg = element.evaluate_detail.filter(item => item.rule.category.name === `omg`)

                let total_kpi, total_key, total_omg, sum_total
                total_kpi = 0
                total_key = 0
                total_omg = 0
                sum_total = 0


                total_kpi = total_quarter(kpi).reduce((a, c) => a + c.cal, 0) - kpi_reduce_point
                total_key = total_quarter(key_task).reduce((a, c) => a + c.cal, 0) - keytask_reduce_point

                let cal_o = total_quarter(omg).reduce((a, c) => a + c.cal, 0) - omg_reduce_point
                total_omg = average_omg ? cal_o / average_omg : cal_o

                sum_total = (total_kpi * weigth_template[0]) + (total_key * weigth_template[1]) + (total_omg * weigth_template[2])

                data.push({
                    evaluate: element,
                    kpi: total_kpi,
                    key_task: total_key,
                    omg: total_omg,
                    score: sum_total / 100
                })
            }
        } else {
            for (let index = 0; index < fetch_data.length; index++) {
                let total_kpi, total_key, total_omg, sum_total
                const evaluate = fetch_data[index]
                total_kpi = evaluate.evaluate_detail.filter(item => item.rule.category.name === `kpi`).reduce((a, c) => a + c.cal, 0) - evaluate.kpi_reduce
                total_key = evaluate.evaluate_detail.filter(item => item.rule.category.name === `key-task`).reduce((a, c) => a + c.cal, 0) - evaluate.key_task_reduce
                total_omg = 0.00
                sum_total = 0.00

                sum_total = (total_kpi * weigth_template[0]) + (total_key * weigth_template[1])

                data.push({
                    evaluate: evaluate,
                    kpi: total_kpi,
                    key_task: total_key,
                    omg: total_omg,
                    score: sum_total / 100
                })
            }
        }
        return data
    } catch (error) {
        console.error(error)
    }
}

let total_quarter = (objArr) => {
    const d = new Date();
    let temp = [],
        quarter_all = $("#quarter").val() === '' ? (d.getMonth() + 1) - 1 : 3
    //(d.getMonth()+1) - 1 จะมีปัญหา สิ้นปี
    try {
        for (var i = 0; i < objArr.length; i++) {
            let item = objArr[i]
            if ((item.rule_id === 271) || (item.rule_id === 272) || item.rule_id === 273 || item.rule_id === 274) {
                // console.log(item.evaluate_id);
            }
            item.average_max = []
            item.average_weight = []
            item.average_actual = []
            item.average_target = []
            if (temp.length < 1) {
                item.average_max.push(item.max_result)
                item.average_weight.push(item.weight)
                item.average_actual.push(item.actual)
                item.average_target.push(item.target)
                temp.push(item)
            } else {
                let t_index = temp.findIndex(t => t.rule_id === item.rule_id)
                if (t_index === -1) {
                    item.average_max.push(item.max_result)
                    item.average_weight.push(item.weight)
                    item.average_actual.push(item.actual)
                    item.average_target.push(item.target)
                    temp.push(item)
                } else {
                    temp[t_index].average_max.push(item.max_result)
                    temp[t_index].average_weight.push(item.weight)
                    temp[t_index].average_actual.push(item.actual)
                    temp[t_index].average_target.push(item.target)
                }
            }
        }
    } catch (error) {
        console.error(error)
    }

    try {
        for (let index = 0; index < temp.length; index++) {
            const element = temp[index]
            element.max_result = element.average_max[element.average_max.length - 1]
            element.weight = element.rule.category.name === `omg` ? element.average_weight.reduce((a, b) => a + b, 0) : element.average_weight.reduce((a, b) => a + b, 0) / quarter_all
            element.target = score_quarter_cal_target(element)
            element.actual = score_quarter_cal_amount(element)
            element.actual_pc = score_findActualPercent(element, temp)
            element.target_pc = score_findTargetPercent(element, temp)
            element.ach = score_findAchValue(element)
            element.cal = score_findCalValue(element, element.ach)
        }
    } catch (error) {
        console.error(error);
    }
    return temp
}


const render_score = (score) => {
    let table = document.getElementById('table-report-score'),
        body = table.tBodies[0]
    removeAllChildNodes(body)
    // head = table.tHead
    if (weigth_template.length > 0) {
        for (let index = 0; index < table.tHead.rows[1].cells.length; index++) {
            const element = table.tHead.rows[1].cells[index]
            element.textContent = weigth_template[index]
        }
    }

    if (score.length > 0) {
        for (let index = 0; index < score.length; index++) {
            const element = score[index]
            let uri = '#'
            if (document.getElementById('customSwitch1').checked) {
                if ($("#quarter").val() === '') {
                    uri = `${window.origin}/kpi/self-evaluation/user/${element.evaluate.user_id}/year/${$("#year").val()}`
                } else {
                    uri = `${window.origin}/kpi/self-evaluation/user/${element.evaluate.user_id}/quarter/${$("#quarter").val()}/year/${$("#year").val()}`
                }
            } else {
                uri = `${window.origin}/kpi/self-evaluation/${element.evaluate.id}/edit`
            }
            const rank_rate = calculator_score(element.score)
            let newRow = body.insertRow()
            let name = newRow.insertCell()
            name.style = `text-align: left;`

            let a = make_link(uri, element.evaluate.user.name)
            a.style = `padding-left: 20%`
            name.appendChild(a)

            let division = newRow.insertCell()
            division.style = `text-align: left;`
            division.textContent = element.evaluate.user.divisions.name

            let kpi = newRow.insertCell()
            kpi.textContent = element.kpi.toFixed(2) + `%`

            let task = newRow.insertCell()
            task.textContent = element.key_task.toFixed(2) + `%`

            let omg = newRow.insertCell()
            omg.textContent = element.omg.toFixed(2) + `%`

            let cscore = newRow.insertCell()
            cscore.textContent = element.score.toFixed(2) + `%`

            let rank = newRow.insertCell()
            rank.textContent = index + 1

            let rate = newRow.insertCell()
            rate.textContent = rank_rate
        }
    } else {
        let newRow = body.insertRow()
        let cell = newRow.insertCell()
        cell.setAttribute("colspan", 8)
        cell.textContent = 'No information...'
    }
    table.previousElementSibling.classList.remove('reload')
}

const make_options = () => {
    let selectMonth = document.getElementById('period'),
        selectYearh = document.getElementById('year'),
        selectQuarter = document.getElementById('quarter'),
        max = 5,
        date = new Date(),
        year = new Date().getFullYear()

    removeAllChildNodes(selectYearh)
    removeAllChildNodes(selectMonth)
    removeAllChildNodes(selectQuarter)
    // year
    do {
        let text_year = year--
        max--
        selectYearh.add(new Option(text_year, text_year), null);
    } while (max > 0);
    // month
    for (m = 1; m <= 12; m++) {
        let monthName = new Date(date.getFullYear(), m - 1).toLocaleString('en-US', {
            month: 'long'
        })
        let selected = date.getMonth() === (m - 1)
        let value = m < 10 ? `0${m}` : m
        selectMonth.add(new Option(monthName, value, false, selected))
    }
    // quarter
    selectQuarter.add(new Option(`All`, '', true, false))
    for (let q = 1; q <= 4; q++) {
        selectQuarter.add(new Option(`Quarter ${q}`, q, true, false))
    }
}

const month_quarter = () => {
    let check = document.getElementById('customSwitch1').checked
    let period = document.getElementById('period')
    let quarter = document.getElementById('quarter')
    let degree = document.getElementById('degree')
    let config = {
        params: {
            is_quarter: check,
            period: period.value,
            degree: degree.value
        },
    }
    if (check) {
        period.previousElementSibling.textContent = 'Quarter'
        quarter.style.display = 'block'
        period.style.display = 'none'
    } else {
        period.previousElementSibling.textContent = 'Month'
        quarter.style.display = 'none'
        period.style.display = 'block'
    }
    getWeigthConfig(config)
        .then(res => {
            if (res.status === 200) {
                // console.log(res.data.data);
                weigth_template = res.data.data
            }
        })
        .catch(error => {
            console.log(error.response.data.message)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {

            toastClear()
        })
}

let calculator_score = (number) => {
    if (number >= 110.00) {
        return "A"
    } else
    if (number >= 100.00 && number < 110.00) {
        return "B+"
    } else
    if (number >= 90.00 && number < 100.00) {
        return "B"
    } else
    if (number >= 70.00 && number < 90.00) {
        return "C"
    } else
    if (number < 70.00) {
        return "D"
    }
}

//## tab-c-1 method

const search_rule_table = (e) => {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = e
    filter = input.value.toUpperCase()
    table = input.offsetParent.querySelector('table')
    tr = table.tBodies[0].rows
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = ""
            } else {
                tr[i].style.display = "none"
            }
        }
    }
}

const search_staff_table = (e) => {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue, col;
    input = e
    filter = input.value.toUpperCase()
    table = input.offsetParent.querySelector('table')
    tr = table.tBodies[0].rows
    if (input.name === `full_name`) {
        col = 2
    }
    if (input.name === `department`) {
        col = 1
    }
    if (input.name === `degree_tab2`) {
        col = 0
    }
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[col]
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = ""
            } else {
                tr[i].style.display = "none"
            }
        }
    }
}


const render_rule = async () => {
    if (show_rules) {
        let table = document.getElementById('table-rule-evaluation')
        try {
            let d = new Date()
            let result = await getReportRuleOfYear(d.getFullYear())
            // console.log(result.data.data);
            await rules_data_to_table(result.data.data)

        } catch (error) {
            console.error(error)
            // toast(error)
        } finally {
            $('[data-toggle="tooltip"]').tooltip()
            // table.previousElementSibling.classList.add('reload')
            table.previousElementSibling.classList.remove('reload')
            toastClear()
        }

    }

}

const rules_data_to_table = (data) => {
    let table = document.getElementById('table-rule-evaluation'),
        head = table.tHead,
        body = table.tBodies[0]
    removeAllChildNodes(head)
    removeAllChildNodes(body)

    let Hfirst = head.insertRow(),
        Hsecond = head.insertRow(),
        full_name = Hfirst.insertCell()
    full_name.setAttribute('rowspan', 2)
    full_name.style = `background-color: black; color:#fff;`
    full_name.textContent = `Rule Name`
    for (let i = 0; i < data.periods.length; i++) {
        const period = data.periods[i]
        let month = Hfirst.insertCell()
        month.style = `background-color: black; color:#fff;`
        month.setAttribute('colspan', 2)
        month.textContent = period.name

        let target = Hsecond.insertCell(),
            actual = Hsecond.insertCell()
        target.style = `background-color: black; color:#fff;`
        actual.style = `background-color: black; color:#fff;`
        target.textContent = `Target`
        actual.textContent = `Actual`
    }

    for (let i = 0; i < data.rules.length; i++) {
        const rule = data.rules[i]
        let row = body.insertRow(),
            name = row.insertCell(),
            jan_target = row.insertCell(),
            jan_actual = row.insertCell(),
            feb_target = row.insertCell(),
            feb_actual = row.insertCell(),
            mar_target = row.insertCell(),
            mar_actual = row.insertCell(),
            apr_target = row.insertCell(),
            apr_actual = row.insertCell(),
            may_target = row.insertCell(),
            may_actual = row.insertCell(),
            jun_target = row.insertCell(),
            jun_actual = row.insertCell(),
            jul_target = row.insertCell(),
            jul_actual = row.insertCell(),
            aug_target = row.insertCell(),
            aug_actual = row.insertCell(),
            sep_target = row.insertCell(),
            sep_actual = row.insertCell(),
            oct_target = row.insertCell(),
            oct_actual = row.insertCell(),
            nov_target = row.insertCell(),
            nov_actual = row.insertCell(),
            dec_target = row.insertCell(),
            dec_actual = row.insertCell()

        name.classList.add('truncate')
        name.setAttribute('data-toggle', 'tooltip')
        name.setAttribute('title', rule.name)
        name.textContent = rule.name

        jan_target.innerHTML = findLastValue(rule, rule.total[0], 'target')
        jan_actual.innerHTML = findLastValue(rule, rule.total[0], 'actual')

        feb_target.innerHTML = findLastValue(rule, rule.total[1], 'target')
        feb_actual.innerHTML = findLastValue(rule, rule.total[1], 'actual')

        mar_target.innerHTML = findLastValue(rule, rule.total[2], 'target')
        mar_actual.innerHTML = findLastValue(rule, rule.total[2], 'actual')

        apr_target.innerHTML = findLastValue(rule, rule.total[3], 'target')
        apr_actual.innerHTML = findLastValue(rule, rule.total[3], 'actual')

        may_target.innerHTML = findLastValue(rule, rule.total[4], 'target')
        may_actual.innerHTML = findLastValue(rule, rule.total[4], 'actual')

        jun_target.innerHTML = findLastValue(rule, rule.total[5], 'target')
        jun_actual.innerHTML = findLastValue(rule, rule.total[5], 'actual')

        jul_target.innerHTML = findLastValue(rule, rule.total[6], 'target')
        jul_actual.innerHTML = findLastValue(rule, rule.total[6], 'actual')

        aug_target.innerHTML = findLastValue(rule, rule.total[7], 'target')
        aug_actual.innerHTML = findLastValue(rule, rule.total[7], 'actual')

        sep_target.innerHTML = findLastValue(rule, rule.total[8], 'target')
        sep_actual.innerHTML = findLastValue(rule, rule.total[8], 'actual')

        oct_target.innerHTML = findLastValue(rule, rule.total[9], 'target')
        oct_actual.innerHTML = findLastValue(rule, rule.total[9], 'actual')

        nov_target.innerHTML = findLastValue(rule, rule.total[10], 'target')
        nov_actual.innerHTML = findLastValue(rule, rule.total[10], 'actual')

        dec_target.innerHTML = findLastValue(rule, rule.total[11], 'target')
        dec_actual.innerHTML = findLastValue(rule, rule.total[11], 'actual')
    }
}

const findLastValue = (rule, array, key) => {
    let result = 0.00
    for (let i = 0; i < array.length; i++) {
        const element = array[i]
        if (array[0][key] === element[key]) {
            result = element[key]
        } else {
            return `<span style="cursor: pointer; color:red;" data-toggle="modal" data-arr="${array.map(el => el.evaluate_id).join(",")}" data-id="${rule.id}" data-rulename="${rule.name}"
             data-target="#list-invalid-modal" >error..</span>`
        }
    }
    return `<span style="cursor: pointer; " data-toggle="modal" data-arr="${array.map(el => el.evaluate_id).join(",")}" data-id="${rule.id}" data-rulename="${rule.name}"
    data-target="#list-invalid-modal" >${result}</span>`
}

const render_staff_evaluate = async () => {
    let table = document.getElementById('table-staff-evaluation')
    try {
        let d = new Date()
        let result = await getReportStaffEvaluate(d.getFullYear())
        await staff_data_to_table(result.data.data)
        $('[data-toggle="tooltip"]').tooltip()
        table.previousElementSibling.classList.remove('reload')
    } catch (error) {
        console.error(error)
        // toast(error, 'error')
    }
    toastClear()
}

const staff_data_to_table = (data) => {
    console.log(data);
    let table = document.getElementById('table-staff-evaluation'),
        head = table.tHead,
        body = table.tBodies[0]
    removeAllChildNodes(head)
    removeAllChildNodes(body)

    let Hfirst = head.insertRow(),
        degree = Hfirst.insertCell(),
        department = Hfirst.insertCell(),
        full_name = Hfirst.insertCell()
    degree.style = `background-color: black; color:#fff;`
    degree.textContent = `EMC Group`

    department.style = `background-color: black; color:#fff;`
    department.textContent = `Department`

    full_name.style = `background-color: black; color:#fff;`
    full_name.textContent = `Name`
    for (let i = 0; i < data.periods.length; i++) {
        const period = data.periods[i]
        let month = Hfirst.insertCell()
        month.style = `background-color: black; color:#fff;`
        // month.setAttribute('colspan', 2)
        month.textContent = period.name
    }

    for (let i = 0; i < data.users.length; i++) {
        const user = data.users[i]
        let row = body.insertRow(),
            degree_group = row.insertCell(),
            dept = row.insertCell(),
            name = row.insertCell(),
            jan = row.insertCell(),
            feb = row.insertCell(),
            mar = row.insertCell(),
            apr = row.insertCell(),
            may = row.insertCell(),
            jun = row.insertCell(),
            jul = row.insertCell(),
            aug = row.insertCell(),
            sep = row.insertCell(),
            oct = row.insertCell(),
            nov = row.insertCell(),
            dec = row.insertCell()

        degree_group.textContent = user.degree

        dept.classList.add('truncate')
        dept.setAttribute('data-toggle', 'tooltip')
        dept.setAttribute('title', user.department.name)
        dept.textContent = user.department.name

        name.classList.add('truncate')
        name.setAttribute('data-toggle', 'tooltip')
        name.setAttribute('title', user.name)
        name.textContent = user.name

        // let result = calculator_evaluate_of_month('April',user.evaluates)
        // console.log(result);
        jan.textContent = calculator_evaluate_of_month('January', user.evaluates)
        feb.textContent = calculator_evaluate_of_month('February', user.evaluates)
        mar.textContent = calculator_evaluate_of_month('March', user.evaluates)
        apr.textContent = calculator_evaluate_of_month('April', user.evaluates)
        may.textContent = calculator_evaluate_of_month('May', user.evaluates)
        jun.textContent = calculator_evaluate_of_month('June', user.evaluates)
        jul.textContent = calculator_evaluate_of_month('July', user.evaluates)
        aug.textContent = calculator_evaluate_of_month('August', user.evaluates)
        sep.textContent = calculator_evaluate_of_month('September', user.evaluates)
        oct.textContent = calculator_evaluate_of_month('October', user.evaluates)
        nov.textContent = calculator_evaluate_of_month('November', user.evaluates)
        dec.textContent = calculator_evaluate_of_month('December', user.evaluates)
    }
}

const calculator_evaluate_of_month = (month, array) => {
    // let data = []
    let index = array.findIndex(item => item.targetperiod.name === month)
    let total_kpi = 0,
        total_key = 0,
        // total_omg = 0,
        sum_total = 0
    if (index >= 0) {
        let evaluate = array[index]
        let kpi = evaluate.evaluate_detail.filter(item => item.rule.category.name === `kpi`)
        let key_task = evaluate.evaluate_detail.filter(item => item.rule.category.name === `key-task`)
        // let omg = evaluate.evaluate_detail.filter(item => item.rule.category.name === `omg`)


        total_kpi = kpi.reduce((a, c) => a + c.cal, 0)
        total_key = key_task.reduce((a, c) => a + c.cal, 0)
        // total_omg = omg.reduce((a, c) => a + c.cal, 0)
        sum_total = (total_kpi * 70) + (total_key * 30) //+ (total_omg * evaluate.weigth[2])
    }

    return (sum_total / 100).toFixed(2)
}

const fetch_evaluate_modal = async (rule, datas, el) => {
    let result
    try {
        result = await getEvaluateReviewIn({
            params: {
                rule_id: rule,
                evaluate: datas
            }
        })
    } catch (error) {
        console.error(error);
    } finally {
        if (result.status === 200) {
            // console.log(result);
            // let link
            for (let index = 0; index < result.data.data.length; index++) {
                const element = result.data.data[index]
                let li = document.createElement("LI")
                let a = document.createElement("A")
                a.target = "_blank"
                a.href = `${window.location.origin}/kpi/evaluation-review/${element.id}/edit`
                a.rel = "noopener"
                a.innerHTML = `<span style="color:red;">Target: ${element.evaluate_detail[0].target}</span>
                <span style="color:#9ACD32;">Actual: ${element.evaluate_detail[0].actual}</span> = ${element.user.name}`
                // a.textContent = `Target: ${element.evaluate_detail[0].target}  Actual: ${element.evaluate_detail[0].actual} = ${element.user.name}`
                li.appendChild(a)
                el.appendChild(li)
                // link += `<li><a href="${window.location.origin}/kpi/evaluation-review/${element.id}/edit" target="_blank" rel="noopener">${element.user_id}</a></li>`
            }
            // console.log(link);
        }
    }
}
// modal method
$('#list-invalid-modal').on('show.bs.modal', async function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    let all = button.data('arr')
    let id = button.data('id')
    let rule_name = button.data('rulename')
    var modal = $(this)
    modal.find('#rule-modal-label').text(rule_name)
    await fetch_evaluate_modal(id, all, modal.find('.modal-body ul')[0])
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

    modal.find('.modal-body #reload').removeClass('reload')
})

$('#list-invalid-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body ul')[0])
    modal.find('.modal-body #reload').addClass('reload')
})
