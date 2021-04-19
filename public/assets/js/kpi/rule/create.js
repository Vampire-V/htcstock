(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationRuleCategory").select2({
            placeholder: 'Select status',
            allowClear: true
        });
        $("#validationRuleType").select2({
            placeholder: 'Select Rule Type',
            allowClear: true
        });
        $("#validationUserActual").select2({
            placeholder: 'Select User Actual',
            allowClear: true
        });
        $("#validationCalculateType").select2({
            placeholder: 'Select agreements',
            allowClear: true
        });
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
    }, false);

})();
