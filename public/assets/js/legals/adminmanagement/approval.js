(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // dept
        $("#dept").select2({
            placeholder: 'Select Department',
            // allowClear: true,
            dropdownParent: $('#copydata')
        });
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);
    $('#staticBackdrop').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('dept') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        // modal.find('.modal-title').text('New message to ' + recipient)
        // console.log(recipient);
        modal.find('#department_id').val(recipient)
    })

    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#dept > option").prop("selected","selected");
            $("#dept").trigger("change");
        }else{
            $('#dept').val(null).trigger('change');
         }
    });

})();
