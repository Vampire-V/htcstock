(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationTemplate").select2({
            placeholder: 'Select Template...',
            allowClear: true
        });

        $("#rule-name").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            dropdownParent: $('#rule-modal')
        });
        // $("#validationTemplate").val(evaluate.template_id);
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        getEvaluateForm(staff.id, period.id, evaluate.id)
            .then(async res => {
                if (res.status === 200) {
                    await setEvaluateForm(res.data.data)
                    await displayDetail(evaluateForm)
                }
            })
            .catch(error => {
                console.log(error.response.data.message)
                toast(error.response.data.message, 'error')
            })
            .finally(() => {
                if (evaluate.status === status.NEW || evaluate.status === status.SUBMITTED) {
                    pageEnable()
                } else {
                    pageDisable()
                }
                toastClear()
            })
    }, false);
})();
var evaluateForm = new EvaluateForm()
