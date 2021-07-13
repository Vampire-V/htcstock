(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update
        if (detail.length > 0) {
            let tBody = document.getElementById('table-set-actual').tBodies[0]
            for (let index = 0; index < tBody.rows.length; index++) {
                const element = tBody.rows[index]
                let actual = element.cells[10]
                let Ach = element.cells[12]
                let Cal = element.cells[13]
                let obj = detail.find(value => value.id === parseInt(actual.firstChild.id))
                if (obj.rules.calculate_type !== calculate.NEGATIVE && obj.rules.calculate_type !== calculate.ZERO) {
                    actual.setAttribute("min", 0.00);
                }
                // let ach = findAchValue(obj)
                // let cal = findCalValue(obj, ach)
                // obj.ach = ach
                // obj.cal = cal
                setTooltipAch(Ach, obj)
                setTooltipCal(Cal, obj)
                // Ach.textContent = ach.toFixed(2) + '%'
                // Cal.textContent = cal.toFixed(2) + '%'
            }
            $('[data-toggle="tooltip"]').tooltip()
        }

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

    }, false);

})();


var changeActual = (e) => {
    let row = e.parentNode.parentNode
    if (Array.isArray(e.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
        for (let index = 0; index < detail.length; index++) {
            const element = detail[index]
            
            if (element.id === parseInt(e.id)) {
                element.actual = parseFloat(e.value).toFixed(2)
                let actual_pc = findActualPercent(element,detail)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.actual_pc = actual_pc
                element.ach = ach
                element.cal = cal
                row.cells[11].textContent = actual_pc.toFixed(2) + '%'
                row.cells[12].textContent = ach.toFixed(2) + '%'
                row.cells[13].textContent = cal.toFixed(2) + '%'
                row.cells[13].dataset.originalTitle = changeTooltipCal(row.cells[12].dataset.originalTitle, element)
            }

            if (row.cells[4].textContent === element.rules.name && row.cells[3].textContent === `${element.evaluate.targetperiod.name} ${element.evaluate.targetperiod.year}`) {
                element.actual = parseFloat(e.value).toFixed(2)
                let actual_pc = findActualPercent(element,detail)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.actual_pc = actual_pc
                element.ach = ach
                element.cal = cal
                let input = document.getElementById(element.id)
                let duplicate_row = input.parentNode.parentNode
                input.value = parseFloat(e.value).toFixed(2)
                duplicate_row.cells[11].textContent = actual_pc.toFixed(2) + '%'
                duplicate_row.cells[12].textContent = ach.toFixed(2) + '%'
                duplicate_row.cells[13].textContent = cal.toFixed(2) + '%'
                duplicate_row.cells[13].dataset.originalTitle = changeTooltipCal(duplicate_row.cells[12].dataset.originalTitle, element)
            }
        }
    }
}

var changeTarget = (e) => {
    let row = e.parentNode.parentNode
    if (Array.isArray(e.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
        for (let index = 0; index < detail.length; index++) {
            const element = detail[index]
            let id = parseInt(e.id.substr(e.id.search("_") + 1,e.id.length))
            if (element.id === id) {
                element.target = parseFloat(e.value).toFixed(2)
                let target_pc = findTargetPercent(element,detail)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.target_pc = target_pc
                element.ach = ach
                element.cal = cal
                row.cells[9].textContent = target_pc.toFixed(2) + '%'
                row.cells[12].textContent = ach.toFixed(2) + '%'
                row.cells[13].textContent = cal.toFixed(2) + '%'
                row.cells[13].dataset.originalTitle = changeTooltipCal(row.cells[12].dataset.originalTitle, element)
            }
            if (row.cells[4].textContent === element.rules.name && row.cells[3].textContent === `${element.evaluate.targetperiod.name} ${element.evaluate.targetperiod.year}`) {
                element.target = parseFloat(e.value).toFixed(2)
                let target_pc = findTargetPercent(element,detail)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.target_pc = target_pc
                element.ach = ach
                element.cal = cal
                let input = document.getElementById(`target_${element.id}`)
                let duplicate_row = input.parentNode.parentNode
                input.value = parseFloat(e.value).toFixed(2)
                duplicate_row.cells[9].textContent = target_pc.toFixed(2) + '%'
                duplicate_row.cells[12].textContent = ach.toFixed(2) + '%'
                duplicate_row.cells[13].textContent = cal.toFixed(2) + '%'
                duplicate_row.cells[13].dataset.originalTitle = changeTooltipCal(duplicate_row.cells[12].dataset.originalTitle, element)
            }
        }
    }
}

var submit = async (e) => {
    if (detail.length > 0) {
        if (validationActual()) {
            setVisible(true)
            putSetActual(detail, detail[0].rules.user_actual.id)
                .then(res => {
                    if (res.status === 201) {
                        console.log(res.data.data);
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
                })
        } else {
            toast(`Can’t contain letters`, 'error')
            toastClear()
        }
    }
}

var validationActual = () => {
    let tBody = document.getElementById('table-set-actual').tBodies[0]
    for (let index = 0; index < tBody.rows.length; index++) {
        const element = tBody.rows[index];
        if (!Array.isArray(element.cells[10].firstChild.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
            element.cells[10].firstChild.focus()
            return false
        }
        if (!Array.isArray(element.cells[8].firstChild.value.match(/^-?(\d+\.?\d*|\.\d+)$/))) {
            element.cells[8].firstChild.focus()
            return false
        }
    }
    return true
}
