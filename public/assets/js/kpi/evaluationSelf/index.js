(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#validationYear").select2({
            placeholder: 'Select Year',
            allowClear: true
        });
        $("#user").select2({
            placeholder: 'Select User',
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

let report_excel = () => {
    let config = {
        params: {
            user: [$("#user").val()],
            year: [$("#validationYear").val()]
        }
    }

    evaluate_excel(config)
    .then(res => {
        console.log(res);
    })
    .catch(error => {
        console.log(error);
        console.log(error.response.data.message);
    })
}
