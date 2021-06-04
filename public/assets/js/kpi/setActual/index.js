(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update
        if (detail.length > 0) {
            let tBody = document.getElementById('table-set-actual').tBodies[0]
            for (let index = 0; index < tBody.rows.length; index++) {
                const element = tBody.rows[index];
                let obj = detail.find(value => value.id === parseInt(element.cells[8].firstChild.id))
                let ach = findAchValue(obj)
                let cal = findCalValue(obj, ach)
                obj.ach = ach
                obj.cal = cal
                setTooltipAch(element.cells[9], obj)
                setTooltipCal(element.cells[10], obj)
                element.cells[9].textContent = ach.toFixed(2) + '%'
                element.cells[10].textContent = cal.toFixed(2) + '%'
            }
            $('[data-toggle="tooltip"]').tooltip()
        }

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
        OverlayScrollbars(document.getElementsByClassName('table-responsive'), {});
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)

    }, false);

})();


var changeActual = (e) => {
    let button = document.getElementById('table-set-actual').querySelector('button')
    if (Array.isArray(e.value.match(/\w+/))) {
        for (let index = 0; index < detail.length; index++) {
            const element = detail[index];
            if (element.id === parseInt(e.id)) {
                element.actual = parseFloat(e.value).toFixed(2)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.ach = ach
                element.cal = cal
                e.parentNode.nextElementSibling.textContent = ach.toFixed(2) + '%'
                e.parentNode.nextElementSibling.nextElementSibling.textContent = cal.toFixed(2) + '%'
                e.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle = changeTooltipCal(e.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle, element)
            }

            if (e.parentNode.parentNode.cells[3].textContent === element.rules.name && e.parentNode.parentNode.cells[2].textContent === `${element.evaluate.targetperiod.name} ${element.evaluate.targetperiod.year}`) {
                element.actual = parseFloat(e.value).toFixed(2)
                let ach = findAchValue(element)
                let cal = findCalValue(element, ach)
                element.ach = ach
                element.cal = cal
                let input = document.getElementById(element.id)
                // console.log(input,e);
                input.value = parseFloat(e.value).toFixed(2)
                input.parentNode.nextElementSibling.textContent = ach.toFixed(2) + '%'
                input.parentNode.nextElementSibling.nextElementSibling.textContent = cal.toFixed(2) + '%'
                input.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle = changeTooltipCal(input.parentNode.nextElementSibling.nextElementSibling.dataset.originalTitle, element)
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
                        toast(res.data.message, res.data.status)
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
        if (!Array.isArray(element.cells[8].firstChild.value.match(/\w+/))) {
            element.cells[8].firstChild.focus()
            console.log(element.cells[8].firstChild);
            return false
        }
    }
    return true
}
