document.addEventListener("DOMContentLoaded", function () {
    //The first argument are the elements to which the plugin shall be initialized
    //The second argument has to be at least a empty object or a object with your desired options

    // $("#division_id").select2({
    //     placeholder: 'Select Division',
    //     allowClear: true
    // })

    OverlayScrollbars(document.getElementsByClassName("table-responsive"), {
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
            initialize: true,
        },
        overflowBehavior: {
            x: "scroll",
            y: "scroll",
        },
        scrollbars: {
            visibility: "auto",
            autoHide: "never",
            autoHideDelay: 800,
            dragScrolling: true,
            clickScrolling: false,
            touchSupport: true,
            snapHandle: false,
        },
        textarea: {
            dynWidth: false,
            dynHeight: false,
            inheritedAttrs: ["style", "class"],
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
            onUpdated: null,
        },
    });

    let active_tab = localStorage.getItem("tab-dashboard");
    if (!active_tab) {
        window.localStorage.setItem("tab-dashboard", `tab-c-1`);
        active_tab = localStorage.getItem("tab-dashboard");
    }
    let ele_active = document.getElementById(active_tab);
    let scope = ele_active.parentNode.parentNode.parentNode;
    for (
        let index = 0;
        index < scope.firstElementChild.children.length;
        index++
    ) {
        const element = scope.firstElementChild.children[index];
        if (element.firstElementChild.id === active_tab) {
            element.firstElementChild.classList.add("active");
            content_id = element.firstElementChild.href.substring(
                element.firstElementChild.href.search("#") + 1,
                element.firstElementChild.href.length
            );
        } else {
            element.firstElementChild.classList.remove("active");
        }
    }
    if (content_id) {
        let contents = document.getElementById(content_id).parentElement;
        for (let index = 0; index < contents.children.length; index++) {
            const element = contents.children[index];
            if (content_id === element.id) {
                element.classList.add("active");
            } else {
                element.classList.remove("active");
            }
        }
    }
    if (active_tab === "tab-c-0") {
        make_options_report_score();
        search_score();
    } else {
        make_category();
        make_rule_year();
        make_staff_year();
        render_rule();
        render_staff_evaluate();
    }
});

let weigth_template = [];
let categories = [];
const tabActive = (e) => {
    window.localStorage.setItem("tab-dashboard", e.id);
    let active_tab = localStorage.getItem("tab-dashboard");
    if (active_tab === `tab-c-0`) {
        make_options_report_score();
        search_score();
    }
    if (active_tab === `tab-c-1`) {
        render_rule();
        render_staff_evaluate();
    }
};

//## tab-operation method
document.getElementById("isQuarter").addEventListener("click", () => {
    search_score();
});

const search_score = async () => {
    let check = document.getElementById("isQuarter");
    month_quarter(check);
    let score = [];

    let param;
    if (check.checked) {
        param = {
            quarter:
                $("#quarter").val() === ""
                    ? [1, 2, 3, 4]
                    : [$("#quarter").val()],
            year: [$("#year").val()],
            degree: [$("#degree").val()],
        };
    } else {
        //ตรวจสอบ ช่วงของเดือน
        let values = periodOfMonth($("#period").val(), $("#toperiod")[0].value);
        $("#toperiod > option").each(function (i, e) {
            this.disabled =
                parseInt(e.value) >= parseInt($("#period").val())
                    ? false
                    : true;
        });
        if (values.length < 1) {
            toast("Wrong time selected.", "error");
            return;
        }
        param = {
            month: values,
            year: [$("#year").val()],
            degree: [$("#degree").val()],
        };
    }

    if ($("#division_id").val()) {
        param.division_id = [$("#division_id").val()];
    }

    let table = document.getElementById("table-report-score");
    table.previousElementSibling.classList.add("reload");
    try {
        let fetch_data = await getOperationReportScore({
            params: param,
        });
        score = await combine_information(fetch_data.data.data, check.checked);
    } catch (error) {
        console.error(error);
        toast(error, "error");
    } finally {
        render_score(score.sort((a, b) => b.score - a.score));
        toastClear();
    }
};

let combine_information = (fetch_data, isQuarter) => {
    let data = [],
        reduce_averrage;

    reduce_averrage = 0;
    fetch_data.sort(function (a, b) {
        var keyA = a.period_id,
            keyB = b.period_id;
        // Compare the 2 dates
        if (keyA < keyB) return -1;
        if (keyA > keyB) return 1;
        return 0;
    });

    try {
        if (isQuarter) {
            if ($("#quarter").val() === "") {
                const d = new Date();
                let dateForReport =
                    d.getFullYear() !== parseInt($("#year").val())
                        ? new Date(parseInt($("#year").val()), 11, 31)
                        : new Date();
                reduce_averrage = dateForReport.getMonth() + 1;
            } else {
                reduce_averrage = 3;
            }
            /*
            let item_unique = [];
            for (let index = 0; index < fetch_data.length; index++) {
                const evaluate = fetch_data[index];
                evaluate.kpi_reduce_point = [];
                evaluate.keytask_reduce_point = [];
                evaluate.omg_reduce_point = [];
                if (item_unique.length < 1) {
                    evaluate.kpi_reduce_point.push(evaluate.kpi_reduce);
                    evaluate.keytask_reduce_point.push(
                        evaluate.key_task_reduce
                    );
                    evaluate.omg_reduce_point.push(evaluate.omg_reduce);
                    item_unique.push(evaluate);
                } else {
                    let i = item_unique.findIndex(
                        (t) => t.user_id === evaluate.user_id
                    );
                    if (i < 0) {
                        evaluate.kpi_reduce_point.push(evaluate.kpi_reduce);
                        evaluate.keytask_reduce_point.push(
                            evaluate.key_task_reduce
                        );
                        evaluate.omg_reduce_point.push(evaluate.omg_reduce);
                        item_unique.push(evaluate);
                    } else {
                        item_unique[i].kpi_reduce_point.push(
                            evaluate.kpi_reduce
                        );
                        item_unique[i].keytask_reduce_point.push(
                            evaluate.key_task_reduce
                        );
                        item_unique[i].omg_reduce_point.push(
                            evaluate.omg_reduce
                        );
                        item_unique[i].evaluate_detail = item_unique[
                            i
                        ].evaluate_detail.concat(evaluate.evaluate_detail);
                    }
                }
            }
            */
            let result_reunite = reunite_evaluate_user(fetch_data);

            data = calculator_evaluates(
                result_reunite,
                reduce_averrage,
                isQuarter
            );
        } else {
            let result_reunite = reunite_evaluate_user(fetch_data);
            let average_month = periodOfMonth(
                $("#period").val(),
                $("#toperiod")[0].value
            );

            data = calculator_evaluates(
                result_reunite,
                average_month.length,
                isQuarter
            );
        }
        return data;
    } catch (error) {
        console.error(error);
    }
};

const render_score = (score) => {
    let table = document.getElementById("table-report-score"),
        body = table.tBodies[0];
    removeAllChildNodes(body);
    // head = table.tHead
    if (weigth_template.length > 0) {
        for (let index = 0; index < table.tHead.rows[1].cells.length; index++) {
            const element = table.tHead.rows[1].cells[index];
            element.textContent = weigth_template[index];
        }
    }

    if (score.length > 0) {
        for (let index = 0; index < score.length; index++) {
            const element = score[index];
            let uri = "#";
            if (document.getElementById("isQuarter").checked) {
                if ($("#quarter").val() === "") {
                    uri = `${window.origin}/kpi/self-evaluation/user/${
                        element.evaluate.user_id
                    }/year/${$("#year").val()}`;
                } else {
                    uri = `${window.origin}/kpi/self-evaluation/user/${
                        element.evaluate.user_id
                    }/quarter/${$("#quarter").val()}/year/${$("#year").val()}`;
                }
            } else {
                // uri = `${window.origin}/kpi/self-evaluation/${element.evaluate.id}/edit`;
                let months = periodOfMonth(
                    $("#period").val(),
                    $("#toperiod")[0].value
                );
                if (months.length < 2) {
                    uri = `${window.origin}/kpi/self-evaluation/${element.evaluate.id}/edit`;
                } else {
                    let data = {
                        month: months,
                        year: [$("#year").val()],
                        degree: [$("#degree").val()],
                    };
                    if ($("#division_id").val()) {
                        data.division_id = [$("#division_id").val()];
                    }
                    const ret = [];
                    for (let d in data) {
                        for (let v in data[d]) {
                            ret.push(
                                encodeURIComponent(d) +
                                    "[]=" +
                                    encodeURIComponent(data[d][v])
                            );
                        }
                    }
                    uri = `${window.origin}/kpi/self-evaluation/${
                        element.evaluate.user_id
                    }/score?${ret.join("&")}`;
                }
            }
            const rank_rate = calculator_score(element.score);
            let newRow = body.insertRow();
            let name = newRow.insertCell();
            name.style = `text-align: left;`;

            let a = make_link(uri, element.evaluate.user.name);
            a.style = `padding-left: 20%`;
            name.appendChild(a);

            let division = newRow.insertCell();
            division.style = `text-align: left;`;
            division.textContent = element.evaluate.user.divisions.name;

            let kpi = newRow.insertCell();
            kpi.textContent = element.kpi.toFixed(2) + `%`;

            let task = newRow.insertCell();
            task.textContent = element.key_task.toFixed(2) + `%`;

            let omg = newRow.insertCell();
            omg.textContent = element.omg.toFixed(2) + `%`;

            let cscore = newRow.insertCell();
            cscore.textContent = element.score.toFixed(2) + `%`;

            let rank = newRow.insertCell();
            rank.textContent = index + 1;

            let rate = newRow.insertCell();
            rate.textContent = rank_rate;
        }
    } else {
        let newRow = body.insertRow();
        let cell = newRow.insertCell();
        cell.setAttribute("colspan", 8);
        cell.textContent = "No information...";
    }
    table.previousElementSibling.classList.remove("reload");
};

const make_options_report_score = async () => {
    let selectMonth = document.getElementById("period"),
        selectToMonth = document.getElementById("toperiod"),
        selectYearh = document.getElementById("year"),
        selectQuarter = document.getElementById("quarter"),
        selectDivision = document.getElementById("division_id"),
        max = 5,
        date = new Date(),
        year = new Date().getFullYear();

    removeAllChildNodes(selectYearh);
    removeAllChildNodes(selectMonth);
    removeAllChildNodes(selectToMonth);
    removeAllChildNodes(selectQuarter);
    removeAllChildNodes(selectDivision);

    // year
    do {
        let text_year = year--;
        max--;
        selectYearh.add(new Option(text_year, text_year), null);
    } while (max > 0);
    // month
    for (m = 1; m <= 12; m++) {
        let monthName = new Date(date.getFullYear(), m - 1).toLocaleString(
            "en-US",
            {
                month: "long",
            }
        );
        let selected = date.getMonth() === m - 1;
        let value = m < 10 ? `0${m}` : m;
        selectMonth.add(new Option(monthName, value, false, selected));
    }

    // to month
    for (tm = 1; tm <= 12; tm++) {
        let monthName = new Date(date.getFullYear(), tm - 1).toLocaleString(
            "en-US",
            {
                month: "long",
            }
        );
        let selected = date.getMonth() === tm - 1;
        let value = tm < 10 ? `0${tm}` : tm;
        selectToMonth.add(new Option(monthName, value, false, selected));
    }

    // quarter
    selectQuarter.add(new Option(`All`, "", true, false));
    for (let q = 1; q <= 4; q++) {
        selectQuarter.add(new Option(`Quarter ${q}`, q, true, false));
    }
    let divisions = [];
    try {
        let result = await getdivisions();
        if (result.status === 200) {
            divisions = result.data.data;
        }
    } catch (error) {
        console.error(error);
    } finally {
        if (divisions.length > 0) {
            selectDivision.add(new Option(`All`, "", true, false));
            divisions.forEach((item) => {
                selectDivision.add(new Option(item.name, item.id, true, false));
            });
        }
    }
};

const month_quarter = (check) => {
    let checked = check.checked;
    let period = document.getElementById("period");
    let to_period = document.getElementById("toperiod");
    let quarter = document.getElementById("quarter");
    let degree = document.getElementById("degree");
    let config = {
        params: {
            is_quarter: checked,
            period: period.value,
            degree: degree.value,
        },
    };
    if (checked) {
        period.previousElementSibling.textContent = "Quarter";
        to_period.previousElementSibling.textContent = "";
        quarter.style.display = "block";
        period.style.display = "none";
        to_period.style.display = "none";
    } else {
        to_period.previousElementSibling.textContent = "To Month";
        period.previousElementSibling.textContent = "Month";
        quarter.style.display = "none";
        period.style.display = "block";
        to_period.style.display = "block";
    }
    getWeigthConfig(config)
        .then((res) => {
            if (res.status === 200) {
                // console.log(res.data.data);
                weigth_template = res.data.data;
            }
        })
        .catch((error) => {
            console.log(error.response.data.message);
            toast(error.response.data.message, error.response.data.status);
        })
        .finally(() => {
            toastClear();
        });
};

let periodOfMonth = (first, second) => {
    let f = parseInt(first),
        s = parseInt(second);
    let result = [];
    for (let index = f; index <= s; index++) {
        result.push(
            index.toString().length < 2 ? `0${index}` : index.toString()
        );
    }
    return result;
};

let calculator_score = (number) => {
    if (number >= 110.0) {
        return "A";
    } else if (number >= 100.0 && number < 110.0) {
        return "B+";
    } else if (number >= 90.0 && number < 100.0) {
        return "B";
    } else if (number >= 70.0 && number < 90.0) {
        return "C";
    } else if (number < 70.0) {
        return "D";
    }
};

let reunite_evaluate_user = (evaluates) => {
    let item_unique = [];
    //
    for (let index = 0; index < evaluates.length; index++) {
        const evaluate = evaluates[index];
        evaluate.kpi_reduce_point = [];
        evaluate.keytask_reduce_point = [];
        evaluate.omg_reduce_point = [];
        if (item_unique.length < 1) {
            evaluate.kpi_reduce_point.push(evaluate.kpi_reduce);
            evaluate.keytask_reduce_point.push(evaluate.key_task_reduce);
            evaluate.omg_reduce_point.push(evaluate.omg_reduce);
            item_unique.push(evaluate);
        } else {
            let i = item_unique.findIndex(
                (t) => t.user_id === evaluate.user_id
            );
            if (i < 0) {
                evaluate.kpi_reduce_point.push(evaluate.kpi_reduce);
                evaluate.keytask_reduce_point.push(evaluate.key_task_reduce);
                evaluate.omg_reduce_point.push(evaluate.omg_reduce);
                item_unique.push(evaluate);
            } else {
                item_unique[i].kpi_reduce_point.push(evaluate.kpi_reduce);
                item_unique[i].keytask_reduce_point.push(
                    evaluate.key_task_reduce
                );
                item_unique[i].omg_reduce_point.push(evaluate.omg_reduce);
                item_unique[i].evaluate_detail = item_unique[
                    i
                ].evaluate_detail.concat(evaluate.evaluate_detail);
            }
        }
    }
    return item_unique;
};

let calculator_evaluates = async (evaluates, reduce_averrage, checkQuarter) => {
    let data = [];
    // last month's use omg
    for (let index = 0; index < evaluates.length; index++) {
        const element = evaluates[index];
        // let total_kpi, total_key, total_omg, sum_total;
        let total_kpi = 0,
            total_key = 0,
            total_omg = 0,
            sum_total = 0;

            
        
        let omg_rules = element.evaluate_detail.filter(
            (item) => item.rule.category.name === category.OMG
        );

        if (checkQuarter && omg_rules.length > 0) {
            let removeValFromIndex = [];
            await omg_rules.forEach((ruleOmg) => {
                let s = element.evaluate_detail.indexOf(ruleOmg);
                removeValFromIndex.push(s);
            });
            let ruleKeep = await fetchEvaluateDetailByIds(
                omg_rules.map((r) => r.id)
            );
            if (ruleKeep.status === 200 && removeValFromIndex.length > 0) {
                let idsForKeep = Object.values(ruleKeep.data.data).map(
                    (o) => o.id
                );
                for (var i = removeValFromIndex.length - 1; i >= 0; i--) {
                    if (
                        !idsForKeep.includes(
                            element.evaluate_detail[removeValFromIndex[i]]
                                .id
                        )
                    ) {
                        element.evaluate_detail.splice(
                            removeValFromIndex[i],
                            1
                        );
                    }
                }
            }
            total_omg = element.evaluate_detail.filter(
                (item) => item.rule.category.name === category.OMG
            ).reduce((a, c) => a + c.cal, 0) //- omg_rules.omg_reduce_point.reduce((a, c) => a + c, 0);
        }

        let kpi_rules = element.evaluate_detail.filter(
            (item) => item.rule.category.name === category.KPI
        );
        let key_task_rules = element.evaluate_detail.filter(
            (item) => item.rule.category.name === category.KEYTASK
        );

        total_kpi =
            total_quarter(kpi_rules, reduce_averrage).reduce(
                (a, c) => a + c.cal,
                0
            ) - element.kpi_reduce_point.reduce((a, c) => a + c, 0);

        total_key =
            total_quarter(key_task_rules, reduce_averrage).reduce(
                (a, c) => a + c.cal,
                0
            ) - element.keytask_reduce_point.reduce((a, c) => a + c, 0);

        
        sum_total = total_kpi * weigth_template[0] + total_key * weigth_template[1] + (total_omg * weigth_template[2]);

        data.push({
            evaluate: element,
            kpi: total_kpi,
            key_task: total_key,
            omg: total_omg,
            score: sum_total / 100,
        });
    }
    return data;
};


let total_quarter = (objArr, quarter_all) => {
    let temp = [];
    // quarter_all = $("#quarter").val() === "" ? d.getMonth() + 1 - 1 : 3;
    //(d.getMonth()+1) - 1 จะมีปัญหา สิ้นปี
    try {
        for (var i = 0; i < objArr.length; i++) {
            let item = objArr[i];
            item.average_max = [];
            item.average_weight = [];
            item.average_actual = [];
            item.average_target = [];
            if (temp.length < 1) {
                item.average_max.push(item.max_result);
                item.average_weight.push(item.weight);
                item.average_actual.push(item.actual);
                item.average_target.push(item.target);
                temp.push(item);
            } else {
                let t_index = temp.findIndex((t) => t.rule_id === item.rule_id);
                if (t_index === -1) {
                    item.average_max.push(item.max_result);
                    item.average_weight.push(item.weight);
                    item.average_actual.push(item.actual);
                    item.average_target.push(item.target);
                    temp.push(item);
                } else {
                    temp[t_index].average_max.push(item.max_result);
                    temp[t_index].average_weight.push(item.weight);
                    temp[t_index].average_actual.push(item.actual);
                    temp[t_index].average_target.push(item.target);
                }
            }
        }
    } catch (error) {
        console.error(error);
    }

    try {
        for (let index = 0; index < temp.length; index++) {
            const element = temp[index];
            if (element.rule.category.name !== category.OMG) {
                let weight = element.average_weight.reduce(
                    (previousValue, currentValue) =>
                        previousValue + currentValue
                );
                element.max_result =
                    element.average_max[element.average_max.length - 1];
                element.weight = weight / quarter_all;
                element.target = score_quarter_cal_target(element);
                element.actual = score_quarter_cal_amount(element);
                element.actual_pc = score_findActualPercent(element, temp);
                element.target_pc = score_findTargetPercent(element, temp);
                element.ach = score_findAchValue(element);
                element.cal =
                    Math.round(score_findCalValue(element, element.ach) * 100) /
                    100;
            }
        }
    } catch (error) {
        console.error(error);
    }
    return temp;
};
//## tab-all method

const search_staff_table = (e) => {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue, col;
    input = e;
    filter = input.value.toUpperCase();
    table = input.offsetParent.querySelector("table");
    tr = table.tBodies[0].rows;
    if (input.name === `full_name`) {
        col = 2;
    }
    if (input.name === `department`) {
        col = 1;
    }
    if (input.name === `degree_tab2`) {
        col = 0;
    }
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[col];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
};
const make_rule_year = () => {
    let max = 5;
    let selectYearh = document.getElementById("rule_year");
    let year = new Date().getFullYear();

    removeAllChildNodes(selectYearh);
    // year
    do {
        let text_year = year--;
        max--;
        selectYearh.add(new Option(text_year, text_year), null);
    } while (max > 0);
};
const make_staff_year = () => {
    let max = 5;
    let selectYearh = document.getElementById("staff_year");
    let year = new Date().getFullYear();

    removeAllChildNodes(selectYearh);
    // year
    do {
        let text_year = year--;
        max--;
        selectYearh.add(new Option(text_year, text_year), null);
    } while (max > 0);
};
const render_rule = async () => {
    if (show_rules) {
        let table = document.getElementById("table-rule-evaluation");
        if (table.previousElementSibling.classList.length < 1) {
            table.previousElementSibling.classList.add("reload");
        }

        try {
            let selectedYear = $("#rule_year").val();
            let filter = {
                category_id: [],
            };
            if ($("#category").val()) {
                filter.category_id = [$("#category").val()];
            }
            if ($("#ruleName").val()) {
                filter.ruleName = $("#ruleName").val();
            }
            let result = await getReportRuleOfYear(selectedYear, {
                params: filter,
            });

            await rules_data_to_table(result.data.data);
        } catch (error) {
            console.error(error);
            // toast(error)
        } finally {
            $('[data-toggle="tooltip"]').tooltip();
            // table.previousElementSibling.classList.add('reload')
            table.previousElementSibling.classList.remove("reload");
            // hideSpinner()
            toastClear();
        }
    }
};

const rules_data_to_table = async (data) => {
    let table = document.getElementById("table-rule-evaluation"),
        head = table.tHead,
        body = table.tBodies[0];
    table.previousElementSibling.classList.add("reload");
    removeAllChildNodes(head);
    removeAllChildNodes(body);
    try {
        let Hfirst = head.insertRow(),
            Hsecond = head.insertRow();
        // h_category = Hfirst.insertCell(),
        // full_name = Hfirst.insertCell();

        let cell_cat = document.createElement("th");
        cell_cat.setAttribute("rowspan", 2);
        cell_cat.style = `background-color: black; color:#fff;`;

        // h_category.setAttribute("rowspan", 2);
        // h_category.style = `background-color: black; color:#fff;`;
        // h_category.textContent = `Category`;

        let cell_rule = document.createElement("th");
        cell_rule.setAttribute("rowspan", 2);
        cell_rule.style = `background-color: black; color:#fff;`;
        cell_rule.appendChild(document.createTextNode("Rule Name"));
        cell_cat.appendChild(document.createTextNode("Category"));
        Hfirst.appendChild(cell_cat);
        Hfirst.appendChild(cell_rule);

        // full_name.setAttribute("rowspan", 2);
        // full_name.style = `background-color: black; color:#fff;`;
        // full_name.textContent = `Rule Name`;
        // set Header
        for (let i = 0; i < data.periods.length; i++) {
            const period = data.periods[i];
            // let month = Hfirst.insertCell();
            // month.style = `background-color: black; color:#fff;`;
            // month.setAttribute("colspan", 2);
            // month.textContent = period.name;

            let month = document.createElement("th");
            month.setAttribute("colspan", 2);
            month.style = `background-color: black; color:#fff;`;
            month.appendChild(document.createTextNode(period.name));
            Hfirst.appendChild(month);

            let target = document.createElement("th"); // Hsecond.insertCell(),
            actual = document.createElement("th"); // Hsecond.insertCell();
            target.style = `background-color: black; color:#fff;`;
            actual.style = `background-color: black; color:#fff;`;
            target.appendChild(document.createTextNode("Target"));
            actual.appendChild(document.createTextNode("Actual"));
            Hsecond.appendChild(target);
            Hsecond.appendChild(actual);
        }
        // set body
        for (let i = 0; i < data.rules.length; i++) {
            const rule = data.rules[i];
            let row = body.insertRow(),
                group = row.insertCell(),
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
                dec_actual = row.insertCell();

            group.classList.add("truncate");
            group.textContent = rule.category.name;

            name.classList.add("truncate");
            name.setAttribute("data-toggle", "tooltip");
            name.setAttribute("title", rule.name);
            name.textContent = rule.name;

            jan_target.innerHTML = findLastValue(rule, rule.total[0], "target");
            jan_actual.innerHTML = findLastValue(rule, rule.total[0], "actual");

            feb_target.innerHTML = findLastValue(rule, rule.total[1], "target");
            feb_actual.innerHTML = findLastValue(rule, rule.total[1], "actual");

            mar_target.innerHTML = findLastValue(rule, rule.total[2], "target");
            mar_actual.innerHTML = findLastValue(rule, rule.total[2], "actual");

            apr_target.innerHTML = findLastValue(rule, rule.total[3], "target");
            apr_actual.innerHTML = findLastValue(rule, rule.total[3], "actual");

            may_target.innerHTML = findLastValue(rule, rule.total[4], "target");
            may_actual.innerHTML = findLastValue(rule, rule.total[4], "actual");

            jun_target.innerHTML = findLastValue(rule, rule.total[5], "target");
            jun_actual.innerHTML = findLastValue(rule, rule.total[5], "actual");

            jul_target.innerHTML = findLastValue(rule, rule.total[6], "target");
            jul_actual.innerHTML = findLastValue(rule, rule.total[6], "actual");

            aug_target.innerHTML = findLastValue(rule, rule.total[7], "target");
            aug_actual.innerHTML = findLastValue(rule, rule.total[7], "actual");

            sep_target.innerHTML = findLastValue(rule, rule.total[8], "target");
            sep_actual.innerHTML = findLastValue(rule, rule.total[8], "actual");

            oct_target.innerHTML = findLastValue(rule, rule.total[9], "target");
            oct_actual.innerHTML = findLastValue(rule, rule.total[9], "actual");

            nov_target.innerHTML = findLastValue(
                rule,
                rule.total[10],
                "target"
            );
            nov_actual.innerHTML = findLastValue(
                rule,
                rule.total[10],
                "actual"
            );

            dec_target.innerHTML = findLastValue(
                rule,
                rule.total[11],
                "target"
            );
            dec_actual.innerHTML = findLastValue(
                rule,
                rule.total[11],
                "actual"
            );
        }
    } catch (error) {
        console.error(error);
    } finally {
        table.previousElementSibling.classList.remove("reload");
    }
    // table.previousElementSibling.classList.remove('reload')
};

const findLastValue = (rule, array, key) => {
    let result = 0.0;
    for (let i = 0; i < array.length; i++) {
        const element = array[i];
        if (array[0][key] === element[key]) {
            result = element[key];
        } else {
            return `<span style="cursor: pointer; color:red;" data-toggle="modal" data-arr="${array
                .map((el) => el.evaluate_id)
                .join(",")}" data-id="${rule.id}" data-rulename="${rule.name}"
             data-target="#list-invalid-modal" >error..</span>`;
        }
    }
    return `<span style="cursor: pointer; " data-toggle="modal" data-arr="${array
        .map((el) => el.evaluate_id)
        .join(",")}" data-id="${rule.id}" data-rulename="${rule.name}"
    data-target="#list-invalid-modal" >${result}</span>`;
};

const render_staff_evaluate = async () => {
    let table = document.getElementById("table-staff-evaluation");
    if (table.previousElementSibling.classList.length < 1) {
        table.previousElementSibling.classList.add("reload");
    }
    try {
        let staffYear = $("#staff_year").val();
        let result = await getReportStaffEvaluate(staffYear);
        await staff_data_to_table(result.data.data);
        $('[data-toggle="tooltip"]').tooltip();
        table.previousElementSibling.classList.remove("reload");
    } catch (error) {
        console.error(error);
        // toast(error, 'error')
    }
    toastClear();
};

const staff_data_to_table = (data) => {
    // console.log(data);
    let table = document.getElementById("table-staff-evaluation"),
        head = table.tHead,
        body = table.tBodies[0];
    removeAllChildNodes(head);
    removeAllChildNodes(body);

    let Hfirst = head.insertRow(),
        degree = document.createElement("th"); // Hfirst.insertCell(),
    department = document.createElement("th"); // Hfirst.insertCell(),
    full_name = document.createElement("th"); // Hfirst.insertCell(),
    // let cell_rule = document.createElement("th");
    // cell_rule.setAttribute("rowspan", 2);
    // cell_rule.style = `background-color: black; color:#fff;`;
    // cell_rule.appendChild(document.createTextNode("Rule Name"));

    degree.style = `background-color: black; color:#fff;`;
    degree.appendChild(document.createTextNode("EMC Group"));
    Hfirst.appendChild(degree);

    department.style = `background-color: black; color:#fff;`;
    department.appendChild(document.createTextNode("Department"));
    Hfirst.appendChild(department);

    full_name.style = `background-color: black; color:#fff;`;
    full_name.appendChild(document.createTextNode("Name"));
    Hfirst.appendChild(full_name);
    for (let i = 0; i < data.periods.length; i++) {
        const period = data.periods[i];
        let month = document.createElement("th"); // Hfirst.insertCell(),
        month.style = `background-color: black; color:#fff;`;
        month.appendChild(document.createTextNode(period.name));
        Hfirst.appendChild(month);
    }

    for (let i = 0; i < data.users.length; i++) {
        const user = data.users[i];
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
            dec = row.insertCell();

        degree_group.textContent = user.degree;

        dept.classList.add("truncate");
        dept.setAttribute("data-toggle", "tooltip");
        dept.setAttribute("title", user.department.name);
        dept.textContent = user.department.name;

        name.classList.add("truncate");
        name.setAttribute("data-toggle", "tooltip");
        name.setAttribute("title", user.name);
        name.textContent = user.name;

        // let result = calculator_evaluate_of_month('April',user.evaluates)
        // console.log(result);
        jan.textContent = calculator_evaluate_of_month(
            "January",
            user.evaluates
        );
        feb.textContent = calculator_evaluate_of_month(
            "February",
            user.evaluates
        );
        mar.textContent = calculator_evaluate_of_month("March", user.evaluates);
        apr.textContent = calculator_evaluate_of_month("April", user.evaluates);
        may.textContent = calculator_evaluate_of_month("May", user.evaluates);
        jun.textContent = calculator_evaluate_of_month("June", user.evaluates);
        jul.textContent = calculator_evaluate_of_month("July", user.evaluates);
        aug.textContent = calculator_evaluate_of_month(
            "August",
            user.evaluates
        );
        sep.textContent = calculator_evaluate_of_month(
            "September",
            user.evaluates
        );
        oct.textContent = calculator_evaluate_of_month(
            "October",
            user.evaluates
        );
        nov.textContent = calculator_evaluate_of_month(
            "November",
            user.evaluates
        );
        dec.textContent = calculator_evaluate_of_month(
            "December",
            user.evaluates
        );
    }
};

const calculator_evaluate_of_month = (month, array) => {
    // let data = []
    let index = array.findIndex((item) => item.targetperiod.name === month);
    let total_kpi = 0,
        total_key = 0,
        // total_omg = 0,
        sum_total = 0;
    if (index >= 0) {
        let evaluate = array[index];
        let kpi = evaluate.evaluate_detail.filter(
            (item) => item.rule.category.name === category.KPI
        );
        let key_task = evaluate.evaluate_detail.filter(
            (item) => item.rule.category.name === category.KEYTASK
        );
        // let omg = evaluate.evaluate_detail.filter(item => item.rule.category.name === category.OMG)

        total_kpi = kpi.reduce((a, c) => a + c.cal, 0);
        total_key = key_task.reduce((a, c) => a + c.cal, 0);
        // total_omg = omg.reduce((a, c) => a + c.cal, 0)
        sum_total = total_kpi * 70 + total_key * 30; //+ (total_omg * evaluate.weigth[2])
    }

    return (sum_total / 100).toFixed(2);
};

const fetch_evaluate_modal = async (rule, datas, el) => {
    let result;
    try {
        result = await getEvaluateReviewIn({
            params: {
                rule_id: rule,
                evaluate: datas,
            },
        });
    } catch (error) {
        console.error(error);
    } finally {
        if (result.status === 200) {
            // console.log(result);
            // let link
            for (let index = 0; index < result.data.data.length; index++) {
                const element = result.data.data[index];
                let li = document.createElement("LI");
                let a = document.createElement("A");
                a.target = "_blank";
                a.href = `${window.location.origin}/kpi/evaluation-review/${element.id}/edit`;
                a.rel = "noopener";
                a.innerHTML = `<span style="color:red;">Target: ${element.evaluate_detail[0].target}</span>
                <span style="color:#9ACD32;">Actual: ${element.evaluate_detail[0].actual}</span> = ${element.user.name}`;
                // a.textContent = `Target: ${element.evaluate_detail[0].target}  Actual: ${element.evaluate_detail[0].actual} = ${element.user.name}`
                li.appendChild(a);
                el.appendChild(li);
                // link += `<li><a href="${window.location.origin}/kpi/evaluation-review/${element.id}/edit" target="_blank" rel="noopener">${element.user_id}</a></li>`
            }
            // console.log(link);
        }
    }
};

const make_category = async () => {
    let result_category = await getcategory();
    if (result_category.status === 200) {
        categories = result_category.data.data;

        removeAllChildNodes($("#category")[0]);
        $("#category").append(new Option(`All`, ""));
        for (let index = 0; index < categories.length; index++) {
            const group = categories[index];
            $("#category").append(new Option(group.name, group.id));
        }
    }
};
// modal method
$("#list-invalid-modal").on("show.bs.modal", async function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    let evaluate = button.data("arr");
    let rule_id = button.data("id");
    let rule_name = button.data("rulename");
    var modal = $(this);
    modal.find("#rule-modal-label").text(rule_name);
    await fetch_evaluate_modal(
        rule_id,
        evaluate,
        modal.find(".modal-body ul")[0]
    );
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    modal
        .find(".modal-body button")[0]
        .setAttribute("data-evaluates", evaluate);
    modal.find(".modal-body button")[0].setAttribute("data-rule", rule_id);
    modal.find(".modal-body #reload").removeClass("reload");
});

$("#list-invalid-modal").on("hide.bs.modal", function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var modal = $(this);
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find(".modal-body ul")[0]);
    modal.find(".modal-body #reload").addClass("reload");
});

const changeValues = async (e) => {
    let inputs, isSave, regexp, form;
    isSave = true;
    regexp = /^-?\d+(\.\d{1,2})?$/;
    inputs = document
        .getElementById("list-invalid-modal")
        .querySelectorAll("INPUT");
    form = {
        target: null,
        actual: null,
        evaluates: [],
        rule: null,
    };

    inputs.forEach((element, key) => {
        console.log(regexp.test(element.value));
        if (!regexp.test(element.value)) {
            isSave = false;
            toast(`${element.name} value ${element.value} not match.`, "error");
        } else {
            form.target =
                element.name === "target"
                    ? parseFloat(element.value)
                    : form.target;
            form.actual =
                element.name === "actual"
                    ? parseFloat(element.value)
                    : form.actual;
        }
    });
    if (isSave) {
        form.evaluates = JSON.parse("[" + e.dataset.evaluates + "]");
        form.rule = e.dataset.rule;
        try {
            let result = await putChangeTargetActual(form);
            if (result.status === 200) {
                toast(`${result.data.message}`, result.data.status);
            }
        } catch (error) {
            if (error.response.status === 400) {
                if (typeof error.response.data.message === `object`) {
                    for (const key in error.response.data.message) {
                        if (
                            Object.hasOwnProperty.call(
                                error.response.data.message,
                                key
                            )
                        ) {
                            const element = error.response.data.message[key];
                            for (const iterator of element) {
                                toast(iterator, "error");
                            }
                        }
                    }
                }
            }
        } finally {
            e.parentElement.offsetParent
                .getElementsByClassName("close")[0]
                .click();
            toastClear();
        }
    }
};
