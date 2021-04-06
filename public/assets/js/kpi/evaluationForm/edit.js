(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationTemplate").select2({
            placeholder: 'Select Template...',
            allowClear: true
        });
        $("#mainRule").select2({
            placeholder: 'Select Main Rule...',
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
        // console.log(evaluate.template_id);
        getEvaluateForm(staff.id,period.id,evaluate.id)
        .then( async res => {
            if (res.status === 200) {
                await setEvaluateForm(res.data.data)
                await displayDetail(evaluateForm)
            }
        })
        .catch(error => {
            console.log(error.response.data.message)
        })
        .finally(() => {
            if (evaluate.status === status.READY || evaluate.status === status.APPROVED) {
                pageDisable()
            }else{
                pageEnable()
            }
        })
    }, false);

})();
var evaluateForm = new EvaluateForm()