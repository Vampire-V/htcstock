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
        render_rule()
        render_staff_evaluate()
    }

    // $("#department").select2({
    //     placeholder: 'Select Department...',
    //     allowClear: true
    // })
    // $("#degree_tab2").select2({
    //     placeholder: 'Select EMC Group...',
    //     allowClear: true
    // })
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
            // let newData = []
            if (res.status === 200) {
                // console.log(res.data.data);
                if (checked) {
                    // is quarter
                    // New function for quarter
                    // let group = res.data.data.reduce((r, a) => {
                    //     r[a.user_id] = [...r[a.user_id] || [], a];
                    //     return r;
                    // }, {})
                    //    let total_kpi = 0
                    //     let total_key = 0
                    //     let total_omg = 0
                    // for (const key in group) {
                    //     if (Object.hasOwnProperty.call(group, key)) {
                    //         const element = group[key]
                    //         total_kpi = element.reduce((a, c) => a + c.cal_kpi, 0) / 3
                    //         total_key = element.reduce((a, c) => a + c.cal_key_task, 0) / 3
                    //         total_omg = element.reduce((a, c) => a + c.cal_omg, 0) / 3
                    //         sum_total = (total_kpi * weigth_template[0]) + (total_key * weigth_template[1])
                    //         newData.push({
                    //             evaluate: element[element.length-1],
                    //             kpi: total_kpi,
                    //             key_task: total_key,
                    //             omg: total_omg,
                    //             score: sum_total / 100
                    //         })
                    //     }
                    // }
                    // console.log(newData);
                    // End New function for quarter
                    let item_unique = []
                    
                    // let group = res.data.data.reduce((r, a) => {
                    //     r[a.user_id] = [...r[a.user_id] || [], a];
                    //     return r;
                    // }, {})
                    res.data.data.sort(function(a, b) {
                        var keyA = a.period_id,
                          keyB = b.period_id;
                        // Compare the 2 dates
                        if (keyA < keyB) return -1;
                        if (keyA > keyB) return 1;
                        return 0;
                      })
                    for (let index = 0; index < res.data.data.length; index++) {
                        const evaluate = res.data.data[index]
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
                        let kpi = evaluate.evaluate_detail.filter(item => item.rule.category.name === `kpi`)
                        let key_task = evaluate.evaluate_detail.filter(item => item.rule.category.name === `key-task`)
                        // let omg = evaluate.detail.filter(item => item.rule.categorys.name === `omg`)
                        let total_kpi = 0.00
                        let total_key = 0.00
                        let total_omg = 0.00
                        let sum_total = 0.00

                        total_kpi = kpi.reduce((a, c) => a + c.cal, 0)
                        total_key = key_task.reduce((a, c) => a + c.cal, 0)
                        // total_omg = omg.reduce((a, c) => a + c.cal, 0)
                        sum_total = (total_kpi * weigth_template[0]) + (total_key * weigth_template[1]) 
                        // + (total_omg * weigth_template[2])

                        data.push({
                            evaluate: evaluate,
                            kpi: total_kpi,
                            key_task: total_key,
                            omg: total_omg,
                            score: sum_total / 100
                        })

                        // New version รอ อัพเดทข้อมูลใน database ครบก่อน
                        // sum_total = (evaluate.cal_kpi * weigth_template[0]) + (evaluate.cal_key_task * weigth_template[1])
                        // data.push({
                        //     evaluate: evaluate,
                        //     kpi: evaluate.cal_kpi,
                        //     key_task: evaluate.cal_key_task,
                        //     omg: 0.00, //evaluate.cal_omg,
                        //     score: sum_total / 100
                        // })
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
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            render_score(score)
            toastClear()
        })
}


let total_quarter = (objArr) => {
    let temp = []
    
    try {
        for (var i = 0; i < objArr.length; i++) {
            let item = objArr[i]
            item.average_weight = []
            item.average_actual = []
            item.average_target = []
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
            element.weight = element.rule.category.name === `omg` ? element.average_weight.reduce((a, b) => a + b, 0) : element.average_weight.reduce((a, b) => a + b, 0) / 3
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

const score_quarter_cal_target = (rule) => {
    if (rule.rule.quarter_cal === quarters.AVERAGE) {
        return rule.average_target.reduce((a, b) => (a + b)) / rule.average_target.length
    }
    if (rule.rule.quarter_cal === quarters.LAST_MONTH) {
        return rule.average_target[rule.average_target.length - 1]
    }
    if (rule.rule.quarter_cal === quarters.SUM) {
        return rule.average_target.reduce((a, b) => (a + b))
    }
}

const score_quarter_cal_amount = (rule) => {
    if (rule.rule.quarter_cal === quarters.AVERAGE) {
        return rule.average_actual.reduce((a, b) => (a + b)) / rule.average_actual.length
    }
    if (rule.rule.quarter_cal === quarters.LAST_MONTH) {
        return rule.average_actual[rule.average_actual.length - 1]
    }
    if (rule.rule.quarter_cal === quarters.SUM) {
        return rule.average_actual.reduce((a, b) => (a + b))
    }
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
 var score_findActualPercent = (element, array) => {
    let result = 0.00
    if (element.rule.parent) {
        let parent = array.find(item => item.rule_id === element.rule.parent)
        if (element.rule.calculate_type === calculate.POSITIVE) {
            result = element.actual > parent.actual ? 0.00 : element.actual === 0.00 ? 0.00 : (element.actual / parent.actual) * 100
        }
        if (element.rule.calculate_type === calculate.NEGATIVE) {
            result = parent.actual > element.actual ?  (element.actual / parent.actual) * 100 : 0.00
        }
        if (element.rule.calculate_type === calculate.ZERO) {
            // ไม่มี
            result = element.actual <= parent.actual ? 100.00 : 0.00
        }
    } else {
        // result = (element.actual / (element.target === 0) ? 1 : element.target) * 100
        if (element.rule.calculate_type === calculate.POSITIVE) {
            result = element.actual > element.target ? 100.00 : element.actual === 0.00 ? 0.00 : (element.actual / element.target) * 100
        }
        if (element.rule.calculate_type === calculate.NEGATIVE) {
            result = element.actual > element.target ? ((element.actual / element.target) * 100) : 100.00
        }
        if (element.rule.calculate_type === calculate.ZERO) {
            result = element.actual <= element.target ? 100.00 : 0.00
        }
    }
    return element.actual_pc = result
}

/**
 * @params {element} EvaluateDetail
 * @params {array} EvaluateDetail list
 * @return percent (element.target / parent.target) * 100
 */
 var score_findTargetPercent = (element, array) => {

    if (element.rule.parent) {
        let parent = array.find(item => item.rule_id === element.rule.parent)
        let target = element.target_config ?? element.target
        let parent_target = parent.target_config ?? parent.target
        if (parent) {
            let result = target > parent_target ? 0.00 : target === 0.00 && parent_target === 0.00 ? 0.00 : (target / parent_target) * 100
            element.target_pc = result
        }
    } else {
        element.target_pc = 100.00
    }
    return element.target_pc
}


const score_findAchValue = (obj) => {
    if (typeof obj === `object`) {
        if (!obj.rule.parent) {
            // ใช้ amount หา
            if (obj.rule.calculate_type === calculate.POSITIVE) {
                if (obj.target === 0.00 && obj.actual > obj.target) {
                    ach = obj.max
                } else if (obj.actual === 0.00) {
                    ach = 0.00
                }else{
                    ach = parseFloat((obj.actual / obj.target) * 100.00)
                }
                // ach = obj.actual >= obj.target ? obj.max : obj.actual === 0.00 ? 0.00 : parseFloat((obj.actual / obj.target) * 100.00)
            }
            if (obj.rule.calculate_type === calculate.NEGATIVE) {
                let dd = (obj.actual / obj.target)
                if (dd === -Infinity) {
                    dd = 0
                }
                // console.log(obj.actual);
                // if (obj.actual !== 0.00) {
                //     if (obj.actual < obj.target) {
                //         ach = obj.max_result ?? obj.max
                //     } else {
                        ach = parseFloat((2 - dd ) * 100.00)
                        // console.log(obj.rules.name,ach);
                    // }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual !== 0.00 ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 2
                // ach = obj.actual > obj.target ?  parseFloat((2 - (obj.actual / obj.target)) * 100.00) : obj.max #version 1
            }
            if (obj.rule.calculate_type === calculate.ZERO) {
                ach = obj.actual <= obj.target ? 100.00 : 0.00
            }
        } else {
            // ใช้ % หา
            if (obj.rule.calculate_type === calculate.POSITIVE) {
                if (obj.target_pc === 0.00 && obj.actual_pc > obj.target_pc) {
                    ach = obj.max
                } else if (obj.actual_pc === 0.00) {
                    ach = 0.00
                }else{
                    ach = parseFloat((obj.actual_pc / obj.target_pc) * 100.00)
                }
                // ach = obj.actual_pc >= obj.target_pc ? obj.max : parseFloat((obj.actual_pc / obj.target_pc) * 100)
            }
            if (obj.rule.calculate_type === calculate.NEGATIVE) {
                // console.log(obj.actual_pc , obj.target_pc);
                let dd = (obj.actual_pc / obj.target_pc)
                if (dd === -Infinity) {
                    dd = 0
                }
                ach = parseFloat((2 - dd ) * 100.00)
                // if (obj.actual_pc !== 0.00) {
                //     if (obj.actual_pc < obj.target_pc) {
                //         ach = obj.max ?? obj.max_result
                //     } else {
                        // ach = parseFloat((2 - dd ) * 100.00)
                        // console.log(obj.rules.name,ach);
                //     }
                // }else{
                //     ach = 0.00
                // }

                // ach = obj.actual_pc !== 0.00 ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : 0.00  #version 2
                // ach = obj.actual_pc > obj.target_pc ? parseFloat((2 - (obj.actual_pc / obj.target_pc)) * 100) : obj.max  #version 1
            }
            if (obj.rule.calculate_type === calculate.ZERO) {
                ach = obj.actual_pc <= obj.target_pc ? 100.00 : 0.00
            }
        }
    }
    if (typeof obj === `number`) {
        ach = obj
    }
    return isNaN(ach) || (ach === Infinity || ach === -Infinity) ? 0.00 : ach
}

var score_findCalValue = (obj, ach) => {
    // console.log(obj,ach);
    if (ach < obj.base_line) {
        cal = 0.00
    } else {
        if ('max_result' in obj) {
            if (ach >= obj.max_result) {
                cal = parseFloat(obj.max_result) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }
        if ('max' in obj) {
            if (ach >= obj.max) {
                cal = parseFloat(obj.max) * parseFloat(obj.weight) / 100
            } else {
                cal = ach * parseFloat(obj.weight) / 100
            }
        }

    }
    return isNaN(cal) || (cal === Infinity) ? 0.00 : cal
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
    let degree = document.getElementById('degree')
    let config = {
        params: {
            is_quarter: check,
            period: period.value,
            degree: degree.value
        },
    }
    getWeigthConfig(config)
        .then(res => {
            if (res.status === 200) {
                console.log(res.data.data);
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
    let table = document.getElementById('table-rule-evaluation')
    try {
        let result = await getReportRuleOfYear(2021)
        await rules_data_to_table(result.data.data)
        $('[data-toggle="tooltip"]').tooltip()
        // table.previousElementSibling.classList.add('reload')
        table.previousElementSibling.classList.remove('reload')
    } catch (error) {
        console.error(error)
        toast(error)
    }
    toastClear()
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

        jan_target.textContent = rule.total[0].length > 0 ? findLastValue(rule.total[0], 'target') : rule.total[0].length
        jan_actual.textContent = rule.total[0].length > 0 ? findLastValue(rule.total[0], 'actual') : rule.total[0].length

        feb_target.textContent = rule.total[1].length > 0 ? findLastValue(rule.total[1], 'target') : rule.total[1].length
        feb_actual.textContent = rule.total[1].length > 0 ? findLastValue(rule.total[1], 'actual') : rule.total[1].length

        mar_target.textContent = rule.total[2].length > 0 ? findLastValue(rule.total[2], 'target') : rule.total[2].length
        mar_actual.textContent = rule.total[2].length > 0 ? findLastValue(rule.total[2], 'actual') : rule.total[2].length

        apr_target.textContent = rule.total[3].length > 0 ? findLastValue(rule.total[3], 'target') : rule.total[3].length
        apr_actual.textContent = rule.total[3].length > 0 ? findLastValue(rule.total[3], 'actual') : rule.total[3].length

        may_target.textContent = rule.total[4].length > 0 ? findLastValue(rule.total[4], 'target') : rule.total[4].length
        may_actual.textContent = rule.total[4].length > 0 ? findLastValue(rule.total[4], 'actual') : rule.total[4].length

        jun_target.textContent = rule.total[5].length > 0 ? findLastValue(rule.total[5], 'target') : rule.total[5].length
        jun_actual.textContent = rule.total[5].length > 0 ? findLastValue(rule.total[5], 'actual') : rule.total[5].length

        jul_target.textContent = rule.total[6].length > 0 ? findLastValue(rule.total[6], 'target') : rule.total[6].length
        jul_actual.textContent = rule.total[6].length > 0 ? findLastValue(rule.total[6], 'actual') : rule.total[6].length

        aug_target.textContent = rule.total[7].length > 0 ? findLastValue(rule.total[7], 'target') : rule.total[7].length
        aug_actual.textContent = rule.total[7].length > 0 ? findLastValue(rule.total[7], 'actual') : rule.total[7].length

        sep_target.textContent = rule.total[8].length > 0 ? findLastValue(rule.total[8], 'target') : rule.total[8].length
        sep_actual.textContent = rule.total[8].length > 0 ? findLastValue(rule.total[8], 'actual') : rule.total[8].length

        oct_target.textContent = rule.total[9].length > 0 ? findLastValue(rule.total[9], 'target') : rule.total[9].length
        oct_actual.textContent = rule.total[9].length > 0 ? findLastValue(rule.total[9], 'actual') : rule.total[9].length

        nov_target.textContent = rule.total[10].length > 0 ? findLastValue(rule.total[10], 'target') : rule.total[10].length
        nov_actual.textContent = rule.total[10].length > 0 ? findLastValue(rule.total[10], 'actual') : rule.total[10].length

        dec_target.textContent = rule.total[11].length > 0 ? findLastValue(rule.total[11], 'target') : rule.total[11].length
        dec_actual.textContent = rule.total[11].length > 0 ? findLastValue(rule.total[11], 'actual') : rule.total[11].length
    }
}

const findLastValue = (array, key) => {
    let result
    for (let i = 0; i < array.length; i++) {
        const element = array[i]
        if (array[i][key] === element[key]) {
            result = element[key]
        } else {
            return `error inaccurate information..`
        }
    }
    return result
}

const render_staff_evaluate = async () => {
    let table = document.getElementById('table-staff-evaluation')
    try {
        let result = await getReportStaffEvaluate(2021)
        await staff_data_to_table(result.data.data)
        $('[data-toggle="tooltip"]').tooltip()
        table.previousElementSibling.classList.remove('reload')
    } catch (error) {
        console.error(error)
        toast(error, 'error')
    }
    toastClear()
}

const staff_data_to_table = (data) => {
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

        let result = calculator_evaluate_to_month(user.evaluates)

        jan.textContent = result[0] ? result[0].score.toFixed(2) : 0.00
        feb.textContent = result[1] ? result[1].score.toFixed(2) : 0.00
        mar.textContent = result[2] ? result[2].score.toFixed(2) : 0.00
        apr.textContent = result[3] ? result[3].score.toFixed(2) : 0.00
        may.textContent = result[4] ? result[4].score.toFixed(2) : 0.00
        jun.textContent = result[5] ? result[5].score.toFixed(2) : 0.00
        jul.textContent = result[6] ? result[6].score.toFixed(2) : 0.00
        aug.textContent = result[7] ? result[7].score.toFixed(2) : 0.00
        sep.textContent = result[8] ? result[8].score.toFixed(2) : 0.00
        oct.textContent = result[9] ? result[9].score.toFixed(2) : 0.00
        nov.textContent = result[10] ? result[10].score.toFixed(2) : 0.00
        dec.textContent = result[11] ? result[11].score.toFixed(2) : 0.00
    }
}

const calculator_evaluate_to_month = (array) => {
    let data = []
    for (let index = 0; index < array.length; index++) {
        const evaluate = array[index]
        let kpi = evaluate.evaluate_detail.filter(item => item.rule.category.name === `kpi`)
        let key_task = evaluate.evaluate_detail.filter(item => item.rule.category.name === `key-task`)
        let omg = evaluate.evaluate_detail.filter(item => item.rule.category.name === `omg`)
        let total_kpi = 0
        let total_key = 0
        let total_omg = 0
        let sum_total = 0

        total_kpi = kpi.reduce((a, c) => a + c.cal, 0)
        total_key = key_task.reduce((a, c) => a + c.cal, 0)
        total_omg = omg.reduce((a, c) => a + c.cal, 0)
        sum_total = (total_kpi * evaluate.weigth[0]) + (total_key * evaluate.weigth[1]) + (total_omg * evaluate.weigth[2])

        data.push({
            evaluate: evaluate,
            kpi: total_kpi,
            key_task: total_key,
            omg: total_omg,
            score: sum_total / 100
        })
    }
    return data
}
