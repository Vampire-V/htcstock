(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        let table = document.getElementById('table-steing-action')
        render_dead_line(table)
        $("#user").select2({
            placeholder: 'Select user',
            allowClear: true,
            dropdownParent: $('#modal-dead-line')
        })
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        // let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        // validationForm(forms)
    }, false);

})();


const render_dead_line = (table) => {
    let data = []
    getDeadLine()
        .then(res => {
            if (res.status === 200) {
                data = res.data.data
            }
        })
        .catch(error => {
            console.log(error, error.response.data.message)
        })
        .finally(() => {
            table.previousElementSibling.classList.remove('reload')
            removeAllChildNodes(table.tBodies[0])
            if (data.length > 0) {
                for (let index = 0; index < data.length; index++) {
                    const element = data[index]
                    let row = table.tBodies[0].insertRow()
                    let number = row.insertCell()
                    number.textContent = index + 1

                    let day = row.insertCell()
                    day.textContent = element.end

                    let remark = row.insertCell()
                    remark.textContent = element.remark

                    let action = row.insertCell()
                    action.innerHTML = `<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modal-dead-line" data-setting="${element.id}">click</button>`
                }
            }
        })
}

const render_user_action = (table) => {
    let data = []
}

// modal method
$('#modal-dead-line').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    let id = button.data('setting') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    let data = []
    let users = []
    // modal.find('.modal-body #reload').addClass('reload')

    getUserSettingAction(id)
        .then(res => {
            if (res.status === 200) {
                data = res.data.data
            }
        })
        .catch(error => {
            console.log(error, error.response.data.message)
        })
        .finally(() => {
            modal.find('.modal-body #reload').removeClass('reload')
            let ul = modal.find('ul')
            removeAllChildNodes(ul[0])
            for (let index = 0; index < data.length; index++) {
                const element = data[index]
                let user = element.translations.find(item => item.locale === locale) ?? element.translations[0]
                ul.append(`<li>${user.name}  <span class="badge badge-danger" style="cursor: pointer;" onclick="detach_authorized(${element.id})"><i class="pe-7s-trash"></i></span></li>`)
            }

            getOperation()
            .then(res => {
                if (res.status === 200) {
                    users = res.data.data
                }
            }).catch(error => {
                console.log(error, error.response.data.message);
            })
            .finally(() => {
                removeAllChildNodes(modal.find('.modal-body #user')[0])
                if (users.length > 0) {
                    console.log(data,users);
                    // ติด filter ค่าซ้ำ dropdown
                    let sasd = users.filter(value => !data.includes(item => item.id === value.id))
                    for (let index = 0; index < sasd.length; index++) {
                        const user = sasd[index];
                        let model = user.translations.find(item => item.locale === locale) ?? user.translations[0]
                        // console.log(model);
                        let option = new Option(model.name,user.id)
                        modal.find('.modal-body #user').append(option)
                    }
                }
            })
        })

    // fetch rules filter
})

$('#modal-dead-line').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
})

//ลบ user ออก
const detach_authorized = (id) => {

}
// เพิ่ม user เข้า
const attach_authorized = (id) => {

}
