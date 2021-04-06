(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
    })

    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        evaluateForm = await setEvaluate(evaluate)
        await displayDetail(evaluateForm)
        if (evaluate.status === status.READY && evaluate.status === status.DRAFT) {
            pageDisable()
        }
    }, false);
})();
var evaluateForm = new EvaluateForm()