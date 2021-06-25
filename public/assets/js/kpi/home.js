document.addEventListener("DOMContentLoaded", function () {
    //The first argument are the elements to which the plugin shall be initialized
    //The second argument has to be at least a empty object or a object with your desired options
    window.localStorage.setItem('tab-dashboard', `tab-c-1`)
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

    if (active_tab) {
        let content_id = null
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
        render_self()
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
        render_self()
    }
}

// tab-c-0 method
document.getElementById('customSwitch1').addEventListener('click', () => {
    search_score()
})

const search_score = () => {
    month_quarter()
    let score = []
    let checked = document.getElementById('customSwitch1').checked
    let param
    if (checked) {
        param = {
            quarter: [$("#quarter").val()],
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
    getOperationReportScore({
            params: param
        })
        .then(res => {
            let data = []
            if (res.status === 200) {
                if (checked) {
                    // is quarter
                    let item_unique = []
                    for (let index = 0; index < res.data.data.length; index++) {
                        const evaluate = res.data.data[index]
                        if (item_unique.length < 1) {
                            item_unique.push(evaluate)
                        } else {
                            let i = item_unique.findIndex(t => t.user_id === evaluate.user_id)
                            if (i < 0) {
                                item_unique.push(evaluate)
                            } else {
                                item_unique[i].detail = item_unique[i].detail.concat(evaluate.detail)
                            }
                        }
                    }
                    for (let index = 0; index < item_unique.length; index++) {
                        const element = item_unique[index]
                        let kpi = element.detail.filter(item => item.rules.categorys.name === `kpi`)
                        let key_task = element.detail.filter(item => item.rules.categorys.name === `key-task`)
                        let omg = element.detail.filter(item => item.rules.categorys.name === `omg`)
                        let total_kpi = 0
                        let total_key = 0
                        let total_omg = 0
                        let sum_total = 0
                        total_kpi = total_quarter(kpi).reduce((a, c) => a + c.cal, 0)
                        total_key = total_quarter(key_task).reduce((a, c) => a + c.cal, 0)
                        total_omg = total_quarter(omg).reduce((a, c) => a + c.cal, 0)
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
                    for (let index = 0; index < res.data.data.length; index++) {
                        const evaluate = res.data.data[index]
                        let kpi = evaluate.detail.filter(item => item.rules.categorys.name === `kpi`)
                        let key_task = evaluate.detail.filter(item => item.rules.categorys.name === `key-task`)
                        let omg = evaluate.detail.filter(item => item.rules.categorys.name === `omg`)
                        let total_kpi = 0
                        let total_key = 0
                        let total_omg = 0
                        let sum_total = 0

                        total_kpi = kpi.reduce((a, c) => a + c.cal, 0)
                        total_key = key_task.reduce((a, c) => a + c.cal, 0)
                        total_omg = omg.reduce((a, c) => a + c.cal, 0)
                        sum_total = (total_kpi * weigth_template[0]) + (total_key * weigth_template[1]) + (total_omg * weigth_template[2])

                        data.push({
                            evaluate: evaluate,
                            kpi: total_kpi,
                            key_task: total_key,
                            omg: total_omg,
                            score: sum_total / 100
                        })
                    }
                }
            }
            return data
        })
        .then(data => {
            score = data.sort((a, b) => b.score - a.score)
        })
        .catch(error => {
            console.log(error);
            console.log(error.response.data);
        })
        .finally(() => {
            render_score(score)
        })
}

let total_quarter = (objArr) => {
    let temp = [];
    try {
        for (var i = 0; i < objArr.length; i++) {
            let item = objArr[i]
            if (temp.length < 1) {
                item.average_weight.push(item.weight)
                item.average_actual.push(item.actual)
                item.average_target.push(item.target)
                temp.push(item)
            } else {
                let t_index = temp.findIndex(t => t.rule_id === item.rule_id)
                if (t_index === -1) {
                    item.average_weight.push(item.weight)
                    item.average_actual.push(item.actual)
                    item.average_target.push(item.target)
                    temp.push(item)
                } else {
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
            element.weight = element.rules.categorys.name === `omg` ? element.average_weight.reduce((a, b) => a + b, 0) : element.average_weight.reduce((a, b) => a + b, 0) / 3
            element.target = quarter_cal_target(element)
            element.actual = quarter_cal_amount(element)
            element.actual_pc = findActualPercent(element, temp)
            element.target_pc = findTargetPercent(element, temp)
            element.ach = findAchValue(element)
            element.cal = findCalValue(element, element.ach)
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
            // console.log(element);
            const rank_rate = calculator_score(element.score)
            let newRow = body.insertRow()
            let name = newRow.insertCell()
            name.style = `text-align: left;`
            let uri = document.getElementById('customSwitch1').checked ? `${window.origin}/kpi/self-evaluation/user/${element.evaluate.user_id}/quarter/${$("#quarter").val()}/year/${$("#year").val()}` : `${window.origin}/kpi/self-evaluation/${element.evaluate.id}/edit`;
            let a = make_link(uri, element.evaluate.user.translations[0].name)
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
    for (let q = 1; q <= 4; q++) {
        selectQuarter.add(new Option(`Quarter ${q}`, q, true, false))
    }
}

const month_quarter = () => {
    let check = document.getElementById('customSwitch1').checked
    let period = document.getElementById('period')
    let quarter = document.getElementById('quarter')
    let config = {
        params: {
            is_quarter: check,
            period: period.value
        },
    }
    getWeigthConfig(config)
        .then(res => {
            if (res.status === 200) {
                weigth_template = res.data.data
            }
        })
        .catch(error => {
            console.log(error.response.data.message)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            if (check) {
                period.previousElementSibling.textContent = 'Quarter'
                quarter.style.display = 'block'
                period.style.display = 'none'
            } else {
                period.previousElementSibling.textContent = 'Month'
                quarter.style.display = 'none'
                period.style.display = 'block'
            }
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

// tab-c-1 method
const search_table = (e) => {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = e;
    filter = input.value.toUpperCase()
    table = input.offsetParent.querySelector('table')
    tr = table.tBodies[0].rows
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

const render_self = () => {
    let data = []
    document.getElementById('table-self-evaluation').previousElementSibling.classList.add('reload')
    getReportYourSelf(2021)
        .then(res => {
            if (res.status == 200) {
                data = res.data.data
            }
        })
        .catch(error => {
            console.log(error, error.response.data.message)
        })
        .finally(() => {
            self_data_to_table(data)
        })
}

const self_data_to_table = (data) => {
    let table = document.getElementById('table-self-evaluation'),
        head = table.tHead,
        body = table.tBodies[0]

    removeAllChildNodes(head)
    removeAllChildNodes(body)
    let rowH = head.insertRow()
    rowH.insertCell().textContent = `#`

    let rowBone = body.insertRow()
    let tar = document.createElement('th')
    tar.textContent = `Target`
    rowBone.appendChild(tar)
    let rowBtwo = body.insertRow()
    let act = document.createElement('th')
    act.textContent = `Actual`
    rowBtwo.appendChild(act)
    for (let index = 0; index < data.length; index++) {
        const element = data[index];
        // header
        let th = document.createElement('th')
        th.textContent = element.name
        rowH.appendChild(th)
        // body
        let t_month = rowBone.insertCell()
        if (element.evaluates.length > 0) {
            t_month.textContent = element.evaluates[0].evaluate_detail.reduce((a, b) => a + b.target, 0).toFixed(2)
        } else {
            t_month.textContent = 0.00
        }
        let a_month = rowBtwo.insertCell()
        if (element.evaluates.length > 0) {
            a_month.textContent = element.evaluates[0].evaluate_detail.reduce((a, b) => a + b.actual, 0).toFixed(2)
        } else {
            a_month.textContent = 0.00
        }
    }
    table.previousElementSibling.classList.remove('reload')
}
