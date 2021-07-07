(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        // Set data object for form update

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)

        fetch_user_evalate()


    }, false);


})();

const fetch_user_evalate = async () => {


    try {
        let filter = {
            params : {
                month: [],
                year: [2021],
                degree: ['N-1']
            }
        }
        let users = await getOperationReportScore(filter)
        console.log(users);
    } catch (error) {
        console.error(error)
        toast(error)
    }

}
