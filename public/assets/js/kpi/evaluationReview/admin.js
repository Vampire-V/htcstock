(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        // Supporting Documents
    });

    window.addEventListener(
        "load",
        function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            // let forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            // validationForm(forms)
        },
        false
    );
})();

let approveAll = () => {
    // console.log(document.getElementsByClassName("needs-validation")[0]);
    const form = document.getElementsByClassName("needs-validation")[0];
    form.method = "post";
    form.action = `${window.location.origin}${window.location.pathname}/force`;

    let input = document.createElement("input");
    input.setAttribute("type", "hidden");
    input.setAttribute("name", "_token");
    input.setAttribute("value", document.querySelector('meta[name="csrf-token"]').content);
    form.appendChild(input)
    // console.log(document.getElementsByClassName('needs-validation')[0].action);
    // const queryString = window.location.search;
    // const urlParams = new URLSearchParams(queryString);
    // console.log(queryString);
    // console.log(urlParams.keys(),urlParams.values());
    // console.log(urlParams.getAll('year'));
    // document.getElementsByClassName('needs-validation')[0].querySelectorAll('input').forEach(item => {
    //     console.log(item);
    // })
    form.submit()
};
