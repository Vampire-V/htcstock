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
    }
});

let weigth_template = []
document.getElementById('customSwitch1').addEventListener('click', () => {
    month_quarter()
    search_score()
})

var search_table = (e) => {
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

var search = () => {
    document.forms['form-search'].submit();
}

var tabActive = (e) => {
    window.localStorage.setItem('tab-dashboard', e.id)
    let active_tab = localStorage.getItem('tab-dashboard')
    if (active_tab === `tab-c-0`) {
        make_options()
        month_quarter()
        search_score()
    }
}

var search_score = () => {
    let score = []
    let month = {
        month: [$("#period").val()],
        year: [$("#year").val()]
    }
    let quarter = {
        quarter: [$("#quarter").val()],
        year: [$("#year").val()]
    }
    let config = {
        params: document.getElementById('customSwitch1').checked ? quarter : month,
    }
    let table = document.getElementById('table-report-score')
    table.previousElementSibling.classList.add('reload')
    getOperationReportScore(config)
        .then(res => {
            if (res.status === 200) {
                for (let index = 0; index < res.data.data.length; index++) {
                    const evaluate = res.data.data[index];
                    const detail = evaluate.evaluate_detail;
                    let kpi = detail.filter(item => item.rule.category.id === 1)
                    let key_task = detail.filter(item => item.rule.category.id === 2)
                    let omg = detail.filter(item => item.rule.category.id === 3)

                    score.push({
                        user: evaluate.user,
                        kpi: kpi.reduce((a, c) => a + c.cal, 0),
                        key_task: key_task.reduce((a, c) => a + c.cal, 0),
                        omg: omg.reduce((a, c) => a + c.cal, 0)
                    })
                }
                score = quarter_sum(score)
            }
        })
        .catch(error => {
            console.log(error);
            console.log(error.response.data);
        })
        .finally(() => {
            setTimeout(render_score(score), 50000)
        })
}

var render_score = (score) => {
    let table = document.getElementById('table-report-score')
    let body = table.tBodies[0]
    // head = table.tHead
    if (weigth_template.length > 0) {
        for (let index = 0; index < table.tHead.rows[1].cells.length; index++) {
            const element = table.tHead.rows[1].cells[index]
            element.textContent = weigth_template[index]
        }
    }

    if (body.rows.length > 0) {
        removeAllChildNodes(body)
    }
    if (score.length > 0) {
        for (let index = 0; index < score.length; index++) {
            const element = score[index]
            const rank_rate = calculator_score(element.score)
            let newRow = body.insertRow()
            let name = newRow.insertCell()
            name.textContent = element.user.translations[0].name

            let position = newRow.insertCell()
            position.textContent = element.user.positions.name

            let kpi = newRow.insertCell()
            kpi.textContent = element.kpi.toFixed(2)

            let task = newRow.insertCell()
            task.textContent = element.key_task.toFixed(2)

            let omg = newRow.insertCell()
            omg.textContent = element.omg.toFixed(2)

            let cscore = newRow.insertCell()
            cscore.textContent = element.score

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

let month_quarter = () => {
    let check = document.getElementById('customSwitch1').checked
    let period = document.getElementById('period')
    let quarter = document.getElementById('quarter')
    let config = {
        params: {
            is_quarter: check
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

let quarter_sum = (objArr) => {
    let temp = [];
    for (var i = 0; i < objArr.length; i++) {
        let item = objArr[i]
        if (temp.length < 1) {
            item.score = 0.00
            temp.push(item)
        } else {
            let t_index = temp.findIndex(t => t.user.id === item.user.id)
            if (t_index === -1) {
                item.score = 0.00
                temp.push(item)
            } else {
                temp[t_index].kpi += item.kpi
                temp[t_index].key_task += item.key_task
                temp[t_index].omg += item.omg
            }
        }
    }
    for (let index = 0; index < temp.length; index++) {
        const element = temp[index];
        element.score = (element.kpi * weigth_template[0]) + (element.key_task * weigth_template[1]) + (element.omg * weigth_template[2])
        element.score = element.score / 100
    }
    return temp.sort((a, b) => b.score - a.score)
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
