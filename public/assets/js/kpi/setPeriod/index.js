(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#period").select2({
            placeholder: 'Select Period',
            allowClear: true
        });
        $("#year").select2({
            placeholder: 'Select Year',
            allowClear: true
        });
        OverlayScrollbars(document.getElementsByClassName('table-responsive'), {});
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)

    }, false);

})();
