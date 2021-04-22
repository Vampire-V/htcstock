(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        OverlayScrollbars(document.getElementsByClassName('table-responsive'), { });
    })

    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
        
        // console.log(evaluate,evaluateForm);
        evaluateForm = await setEvaluate(evaluate)
        await displayDetail(evaluateForm)
        if (evaluate.status !== status.READY && evaluate.status !== status.DRAFT) {
            pageDisable()
        }
    }, false);
})();

var evaluateForm = new EvaluateForm()

