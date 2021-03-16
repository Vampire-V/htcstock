(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents

        $("#validationRuleName").select2({
            dropdownParent: $('#exampleModal'),
            placeholder: 'Select Rules',
            allowClear: true
        });
        getRuleTemplate(template.id).then(res => {
            createRow(res.data.data)
        })
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
        // console.log('load...');
    }, false);
})();


function formSubmit() {
    document.getElementById('form-template').submit()
}
