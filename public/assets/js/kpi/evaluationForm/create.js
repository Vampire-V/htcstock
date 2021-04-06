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
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();
var evaluateForm = new EvaluateForm()