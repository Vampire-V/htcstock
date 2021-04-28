(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update
        if (evaluate) {
            let tBody = document.getElementById('table-set-ach').tBodies[0]
            for (let index = 0; index < tBody.rows.length; index++) {
                const element = tBody.rows[index];
                let form = evaluate.find(value => value.id === parseInt(element.cells[0].id))
                let total_ach_kpi = form.detail.filter(rule => rule.rules.categorys.name === 'kpi').reduce((accumulator, currentValue) => findAchValue(accumulator) + findAchValue(currentValue), 0)
                let total_ach_key = form.detail.filter(rule => rule.rules.categorys.name === 'key-task').reduce((accumulator, currentValue) => findAchValue(accumulator) + findAchValue(currentValue), 0)
                let total_ach_omg = form.detail.filter(rule => rule.rules.categorys.name === 'omg').reduce((accumulator, currentValue) => findAchValue(accumulator) + findAchValue(currentValue), 0)
                form.ach_kpi = form.ach_kpi ? form.ach_kpi : 0.00
                form.ach_key_task = form.ach_key_task ? form.ach_key_task : 0.00
                form.ach_omg = form.ach_omg ? form.ach_omg : 0.00
                element.cells[4].textContent = total_ach_kpi.toFixed(2) + '%'
                element.cells[6].textContent = total_ach_key.toFixed(2) + '%'
                element.cells[8].textContent = total_ach_omg.toFixed(2) + '%'
            }
        }

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

        OverlayScrollbars(document.getElementsByClassName('table-responsive'), {})
        
        $("#user").select2({
            placeholder: 'Select User',
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
        $("#user_ach").select2({
            placeholder: 'Select User',
            allowClear: true
        });
        $("#period_ach").select2({
            placeholder: 'Select Period',
            allowClear: true
        });
        $("#year_ach").select2({
            placeholder: 'Select Year',
            allowClear: true
        });
        $("#department_ach").select2({
            placeholder: 'Select Department',
            allowClear: true
        });

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)

        let active_tab = localStorage.getItem('tab-active')
        if (active_tab) {
            let ele_active = document.getElementById(active_tab)
            let scope = ele_active.parentNode.parentNode.parentNode
            for (let index = 0; index < scope.firstElementChild.children.length; index++) {
                const element = scope.firstElementChild.children[index];
                if (element.firstElementChild.id === active_tab) {
                    element.firstElementChild.classList.add('active')
                } else {
                    element.firstElementChild.classList.remove('active')
                }
            }
            for (let index = 0; index < scope.lastElementChild.children.length; index++) {
                const element = scope.lastElementChild.children[index];
                if (ele_active.href.search(element.id) > 0) {
                    element.classList.add('active')
                } else {
                    element.classList.remove('active')
                }
            }
        }

    }, false);

})();
