(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationStatus").select2({
            placeholder: 'Select Status',
            allowClear: true
        });
        $("#validationYear").select2({
            placeholder: 'Select Year',
            allowClear: true
        });
        $("#validationPeriod").select2({
            placeholder: 'Select Period',
            allowClear: true
        });


    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();
