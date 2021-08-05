(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#user").select2({
            placeholder: 'Select user',
            allowClear: true,
            dropdownParent: $('#transfer-modal')
        });
        $("#validationRuleTemplate").select2({
            placeholder: 'Select RuleTemplate',
            allowClear: true,
            
        });
        
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
    }, false);

})();

var transfer_template = async () => {
    let form = {
        user: document.getElementById('user').value
    }
    console.log(form);
    try {
        let result = await putTemplateTransfer(form, document.getElementById('template').value)
        console.log(result.data);
    } catch (error) {
        console.error(error);
        toast(error, 'error')
    } finally {
        toastClear()
    }
}

// Modal
$('#transfer-modal').on('show.bs.modal', async function (event) {
    let button = $(event.relatedTarget) // Button that triggered the modal
    let template = button.data('template')
    let modal = $(this)
    modal.find('.modal-body #template').val(template)
    try {
        let result = await getusers()
        let select = modal.find('.modal-body #user')[0]
        if (result.status === 200) {
            for (let index = 0; index < result.data.data.length; index++) {
                const element = result.data.data[index];
                select.add(new Option(element.name, element.id))
            }
        }
    } catch (error) {
        console.error(error)
        toast(error, 'error')
    } finally {
        modal.find('.modal-body #reload').removeClass('reload')
    }
})

$('#transfer-modal').on('hide.bs.modal', function (event) {
    let button = $(event.relatedTarget) // Button that triggered the modal
    let modal = $(this)

    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #user')[0])
    modal.find('.modal-body #reload').addClass('reload')
})
