// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        $(".role-select2").select2({
            tags: true,
            placeholder: "Select a permission",
            allowClear: true,
            // dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter')
        });
        // Loop over them and prevent submission



    }, false);
})();
