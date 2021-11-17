(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $('#division_id').select2({
            placeholder: 'Select Division',
            allowClear: true
        })
        $('#department_id').select2({
            placeholder: 'Select Division',
            allowClear: true
        })
        $("#user").select2({
            placeholder: 'Select User',
            allowClear: true
        });

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
        $("#degree").select2({
            placeholder: 'Select EMC Group',
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
