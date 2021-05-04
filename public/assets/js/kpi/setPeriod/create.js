(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#name").select2({
            placeholder: 'Select Period',
            allowClear: true
        });
        $("#year").select2({
            placeholder: 'Select Period',
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
