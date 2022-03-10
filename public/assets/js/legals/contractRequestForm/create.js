(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        let companyFile = document.getElementById('validationCompanyCertificate')
        let RepresenFile = document.getElementById('validationRepresen')
        // Supporting Documents
        displayFileName(companyFile)
        displayFileName(RepresenFile)
        $(".js-select-status-multiple").select2({
            placeholder: 'Select status',
            allowClear: true
        });
        $(".js-select-agreements-multiple").select2({
            placeholder: 'Select type',
            allowClear: true
        });
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)

        // tooltip บน option ของ Agreements
        let select = this.document.getElementById("validationAgreements")

        Array.from(select.options).forEach(el => {
            el.addEventListener("onmouseover", e => {
                console.log(e);
            })
        })
        // select.addEventListener("mouseover", event => {
        //     console.log("test");
        // })
        $('[data-toggle="tooltip"]').tooltip()
    }, false);

})();
