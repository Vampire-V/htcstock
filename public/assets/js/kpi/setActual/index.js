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
        }
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
