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
        make_month()
        search_score()
    }
}
var search_score = () => {
    let score = []
    let config = {
        params: {
            month: [$("#period").val()],
            year: [$("#year").val()]
        },
    }
    let table = document.getElementById('table-report-score')
    table.previousElementSibling.classList.add('reload')
    getOperationReportScore(config)
        .then(res => {
            console.log(res.data.data)
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
    if (body.rows.length > 0) {
        removeAllChildNodes(body)
    }
    if (score.length > 0) {
        for (let index = 0; index < score.length; index++) {
            const element = score[index]
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
            cscore.textContent = element.omg.toFixed(2)

            let rank = newRow.insertCell()
            rank.textContent = index + 1

            let rate = newRow.insertCell()
            rate.textContent = 'test'
        }
    } else {
        let newRow = body.insertRow()
        let cell = newRow.insertCell()
        cell.setAttribute("colspan", 8)
        cell.textContent = 'No information...'
    }
    table.previousElementSibling.classList.remove('reload')
}

const make_month = () => {
    let selectMonth = document.getElementById('period'),
        selectYearh = document.getElementById('year'),
        selectQuarter = document.getElementById('quarter'),
        max = 5,
        date = new Date(),
        year = new Date().getFullYear()
    do {
        let text_year = year--
        max--
        selectYearh.add(new Option(text_year, text_year), null);
    } while (max > 0);

    for (m = 1; m <= 12; m++) {
        let monthName = new Date(date.getFullYear(), m - 1).toLocaleString('en-US', {
            month: 'long'
        })
        let selected = date.getMonth() === (m - 1)
        let value = m < 10 ? `0${m}` : m
        selectMonth.add(new Option(monthName, value, false, selected))
    }
    selectQuarter.add(new Option('', ''))
    for (let q = 1; q <= 4; q++) {
        selectQuarter.add(new Option(`Quarter ${q}`, q, true, false))
    }
    selectQuarter.disabled = true
    $("#quarter").select2({
        placeholder: 'Select Quarter',
        allowClear: true
    })
    $("#period").select2({
        placeholder: 'Select Month',
        allowClear: true
    })
    $("#year").select2({
        placeholder: 'Select Year',
        allowClear: true
    })

}
