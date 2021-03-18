(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents

        $("#validationDepartment").select2({
            placeholder: 'Select department',
            allowClear: true
        });

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
        // console.log('load...');
    }, false);
})();


// function formSubmit() {
//     document.getElementById('form-template').submit()
// }
