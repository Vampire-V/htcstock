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

// modal method
$('#modal-dead-line').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    let id = button.data('setting') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-body #action').val(id)
    // modal.find('.modal-body #reload').addClass('reload')
    render_modal_action(id)

    // fetch rules filter
})

$('#modal-dead-line').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
})

const render_modal_action = (id) => {
    let data = [],
        users = []
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
            getOperation()
                .then(res => {
                    if (res.status === 200) {
                        users = res.data.data
                    }
                }).catch(error => {
                    console.log(error, error.response.data.message);
                })
                .finally(() => {
                    $('.modal-body #reload').removeClass('reload')
                    let ul = $('.modal-body ul')
                    removeAllChildNodes(ul[0])
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index]
                        let rm_index = users.findIndex(i => i.id === element.id)
                        if (rm_index > -1) {
                            users.splice(rm_index, 1)
                        }
                        let user = element.translations.find(item => item.locale === locale) ?? element.translations[0]
                        ul.append(`<li class="mb-1"><span class="badge badge-danger" style="cursor: pointer;" onclick="detach_authorized(${element.id})"><i class="pe-7s-trash"></i></span> ${user.name}</li>`)
                    }
                    removeAllChildNodes($('.modal-body #user')[0])
                    for (let index = 0; index < users.length; index++) {
                        const user = users[index]
                        let model = user.translations.find(item => item.locale === locale) ?? user.translations[0]
                        $('.modal-body #user').append(new Option(model.name, user.id))
                    }
                })
        })
}

//ลบ user ออก
const detach_authorized = (id) => {
    let user = id
    let action = $("#action").val()
    if (user && action) {
        postDetachAction({
                user: user
            }, action).then(res => {
                console.log(res.data);
            })
            .catch(error => {
                console.log(error, error.response.data.message);
            })
            .finally(() => {
                render_modal_action(action)
            })
    }
}
// เพิ่ม user เข้า
const attach_authorized = () => {
    let user = $("#user").val()
    let action = $("#action").val()
    if (user && action) {
        postAttachAction({
                user: user
            }, action).then(res => {
                console.log(res.data);
            })
            .catch(error => {
                console.log(error, error.response.data.message);
            })
            .finally(() => {
                render_modal_action(action)
            })
    }
}
