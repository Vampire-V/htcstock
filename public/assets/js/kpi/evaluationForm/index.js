(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        
        $("#division").select2({
            placeholder: 'Select Division',
            allowClear: true
        })
        $("#department").select2({
            placeholder: 'Select Department',
            allowClear: true
        })
        $("#position").select2({
            placeholder: 'Select Position',
            allowClear: true
        })
        $("#validationYear").select2({
            placeholder: 'Select Year',
            allowClear: true
        })
        
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();
