(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationRuleCategory").select2({
            placeholder: 'Select Rule Category',
            allowClear: true
        })
        $("#validationRuleType").select2({
            placeholder: 'Select Rule Type',
            allowClear: true
        })
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
    }, false);

})();