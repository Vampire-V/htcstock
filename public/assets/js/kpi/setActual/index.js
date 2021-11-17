(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update

        // if (detail.length > 0) {
        //     let tBody = document.getElementById('table-set-actual').tBodies[0]
        //     for (let index = 0; index < tBody.rows.length; index++) {
        //         const element = tBody.rows[index]
        //         let actual = element.cells[10]
        //         let Ach = element.cells[12]
        //         let Cal = element.cells[13]
        //         let obj = detail.find(value => value.id === parseInt(actual.firstChild.id))
        //         if (obj.rules.calculate_type !== calculate.NEGATIVE && obj.rules.calculate_type !== calculate.ZERO) {
        //             actual.setAttribute("min", 0.00);
        //         }
        //         // let ach = findAchValue(obj)
        //         // let cal = findCalValue(obj, ach)
        //         // obj.ach = ach
        //         // obj.cal = cal
        //         setTooltipAch(Ach, obj)
        //         setTooltipCal(Cal, obj)
        //         // Ach.textContent = ach.toFixed(2) + '%'
        //         // Cal.textContent = cal.toFixed(2) + '%'
        //     }
        //     $('[data-toggle="tooltip"]').tooltip()
        // }

        $("#user").select2({
            placeholder: 'Select User',
            allowClear: true
        });
        $("#category").select2({
            placeholder: 'Select Category',
            allowClear: true
        });
        $("#rule").select2({
            placeholder: 'Select Rule',
            allowClear: true
        });
        $("#period").select2({
            placeholder: 'Select Period',
            allowClear: true
        });
        $("#year").select2({
            placeholder: 'Select Year',
            allowClear: true
        });
        $("#department").select2({
            placeholder: 'Select Department',
            allowClear: true
        });
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
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        check_setvalue()
    }, false);

})();


var changeActual = (e) => {
    let row = e.parentNode.parentNode
    let keys = row.cells[0].id.split("_")
    let id ,rule_id,period_id
    id = parseInt(keys[0]), rule_id = parseInt(keys[1]), period_id = parseInt(keys[2])
    let rule = detail.find(item => item.id === id)
    if (Array.isArray(e.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
        for (let index = 0; index < detail.length; index++) {
            const element = detail[index]
            if (element.rule_id === rule.rule_id && element.evaluate.period_id === rule.evaluate.period_id) {
                element.actual = parseFloat(e.value).toFixed(2)
                document.getElementById(`actual_${element.id}`).value = parseFloat(e.value).toFixed(2)
            }
        }
    }
}

var changeTarget = (e) => {
    let row = e.parentNode.parentNode
    let keys = row.cells[0].id.split("_")
    let id ,rule_id,period_id
    id = parseInt(keys[0]), rule_id = parseInt(keys[1]), period_id = parseInt(keys[2])
    let rule = detail.find(item => item.id === id)
    if (Array.isArray(e.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
        for (let index = 0; index < detail.length; index++) {
            const element = detail[index]
            if (element.rule_id === rule.rule_id && element.evaluate.period_id === rule.evaluate.period_id) {
                element.target = parseFloat(e.value).toFixed(2)
                document.getElementById(`target_${element.id}`).value = parseFloat(e.value).toFixed(2)
            }
        }
    }
}

var submit = async () => {
    if (detail.length > 0) {
        if (validationActual()) {
            setVisible(true)
            putSetActual(detail, detail[0].rules.user_actual.id)
                .then(res => {
                    if (res.status === 201) {
                        toast(res.data.message, res.data.status)
                        if (res.data.data.length > 0) {
                            for (let i = 0; i < res.data.data.length; i++) {
                                const errors = res.data.data[i];
                                toast(`${errors.rule} of ${errors.name} : The status is no longer Ready.`,'error')
                            }
                        }
                    }
                })
                .catch(error => {
                    toast(error.response.data.message, error.response.data.status)
                })
                .finally(() => {
                    setVisible(false)
                    toastClear()
                    check_setvalue()
                    console.log(detail);
                })
        } else {
            toast(`Can’t contain letters`, 'error')
            toastClear()
        }
    }
}

var sendemail = async () => {
    if (confirm("Confirm the email sent to the relevant staff. (ยืนยันการส่ง Email ให้พนักงานที่เกี่ยวข้อง)")) {
        console.log('call api send mail');

        try {
            let evaluates = await Array.from(new Set(detail.map(obj => obj['evaluate_id'])))
            let result = await putSendEmailAffterSetActual({evaluates : evaluates})
            console.log(result);
        } catch (error) {
            console.error(error);
        } finally{

        }

    }

}

var validationActual = () => {
    let tBody = document.getElementById('table-set-actual').tBodies[0]
    for (let index = 0; index < tBody.rows.length; index++) {
        const element = tBody.rows[index];
        if (!Array.isArray(element.cells[6].firstChild.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
            element.cells[6].firstChild.focus()
            return false
        }
        if (!Array.isArray(element.cells[5].firstChild.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
            element.cells[5].firstChild.focus()
            return false
        }
    }
    return true
}

var check_setvalue = () => {
    let tBody = document.getElementById('table-set-actual').tBodies[0]
    for (let index = 0; index < tBody.rows.length; index++) {
        const element = tBody.rows[index];
        if (detail[index].rules.calculate_type !== calculate.ZERO) {
            if (detail[index].actual > 0.00) {
                element.classList.add("set-color")
            }else{
                element.classList.remove("set-color")
            }
        }else{
            element.classList.add("set-color")
        }
    }
}
