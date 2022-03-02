(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#user_actual").select2({
            placeholder: 'Select employee from',
            allowClear: true
        })
        $("#user_to").select2({
            placeholder: 'Select employee to',
            allowClear: true,
            dropdownParent: $('#modal_transfer_rules')
        })
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)

    }, false);

})();
