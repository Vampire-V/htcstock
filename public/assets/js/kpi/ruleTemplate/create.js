(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
        // console.log('load...');
    }, false);
})();

const create_template = () => {
    // created template 
    let name = document.getElementById('validationTemplate').value
    setVisible(true)
    postTemplate({name:name})
    .then(res => {
        if (res.status === 200) {
            let path = window.location.pathname.replace("create",res.data.data.id)
            window.location.replace(`${window.origin}${path}/edit`)
        }
    })
    .catch(error => {
        toast(error.response.data.message, error.response.data.status)
        console.log(error,error.response.data.message);
    })
    .finally(()=>{
        setVisible(false)
        toastClear()
    })
}
