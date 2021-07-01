// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', async function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        $(".js-select-role-multiple").select2({
            allowClear: true,
            placeholder: "Select a role"
        })
        $(".js-select-system-multiple").select2({
            allowClear: true,
            placeholder: "Select a system"
        })
        $(".js-select-user-multiple").select2({
            allowClear: true,
            placeholder: "Select a user multiple"
        })
        $(".js-select-user-copy-multiple").select2({
            allowClear: true,
            placeholder: "Select a user multiple"
        })
        

        // Loop over them and prevent submission
        // let ul = document.getElementById('simpleList')
        let table = document.getElementById('table-approve')
        store_level = user.user_approves
        render_approve_table(table)
        sortable_run(table.tBodies[0])
    }, false);
})();

var store_level = []

var sortable_run = (el) => {
    Sortable.create(el, {
        items: "tr",
        // group: '1',
        animation: 150,
        setData: function ( /** DataTransfer */ dataTransfer, /** HTMLElement*/ dragEl) {
            // console.log('setData...')
            dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
        },

        // Element is chosen
        onChoose: function ( /**Event*/ evt) {
            // console.log('onChoose...')
            evt.oldIndex; // element index within parent
        },

        // Element is unchosen
        onUnchoose: function ( /**Event*/ evt) {
            // same properties as onEnd
            // console.log('onUnchoose...')
        },

        // Element dragging started
        onStart: function ( /**Event*/ evt) {
            evt.oldIndex; // element index within parent
            // console.log('onStart...')
        },

        // Element dragging ended
        onEnd: function ( /**Event*/ evt) {

            var itemEl = evt.item; // dragged HTMLElement
            // console.log('onEnd...', itemEl)
            evt.to; // target list
            evt.from; // previous list
            evt.oldIndex; // element's old index within old parent
            evt.newIndex; // element's new index within new parent
            evt.oldDraggableIndex; // element's old index within old parent, only counting draggable elements
            evt.newDraggableIndex; // element's new index within new parent, only counting draggable elements
            evt.clone // the clone element
            evt.pullMode; // when item is in another sortable: `"clone"` if cloning, `true` if moving
            putUserApproveKPI({
                    users: store_level
                }, user.id)
                .then(res => {
                    if (res.status === 200) {
                        console.log(res);
                        toast(res.data.message, res.data.status)
                    }
                })
                .catch(error => {
                    console.log(error);
                    // console.log(error.response.data.message)
                    toast(error.response.data.message, error.response.data.status)
                })
                .finally(() => {
                    toastClear()
                })
        },

        // Element is dropped into the list from another list
        onAdd: function ( /**Event*/ evt) {
            // same properties as onEnd
            // console.log('onAdd...')
        },

        // Changed sorting within list
        onUpdate: function ( /**Event*/ evt) {
            // same properties as onEnd
            // console.log('onUpdate...')
        },

        // Called by any change to the list (add / update / remove)
        onSort: function ( /**Event*/ evt) {
            // same properties as onEnd
            // console.log('onSort...', evt.to.rows)
            for (let index = 0; index < evt.to.rows.length; index++) {
                const item = evt.to.rows[index]
                let i = store_level.findIndex(e => e.id === parseInt(item.id))
                item.cells[2].textContent = index + 1
                store_level[i].level = index + 1
            }
        },

        // Element is removed from the list into another list
        onRemove: function ( /**Event*/ evt) {
            // same properties as onEnd
            // console.log('onRemove...')
        },

        // Attempt to drag a filtered element
        onFilter: function ( /**Event*/ evt) {
            var itemEl = evt.item; // HTMLElement receiving the `mousedown|tapstart` event.
            // console.log('onFilter...')
        },

        // Event when you move an item in the list or between lists
        onMove: function ( /**Event*/ evt, /**Event*/ originalEvent) {
            // Example: https://jsbin.com/nawahef/edit?js,output
            evt.dragged; // dragged HTMLElement
            evt.draggedRect; // DOMRect {left, top, right, bottom}
            evt.related; // HTMLElement on which have guided
            evt.relatedRect; // DOMRect
            evt.willInsertAfter; // Boolean that is true if Sortable will insert drag element after target by default
            originalEvent.clientY; // mouse position
            // return false; — for cancel
            // return -1; — insert before target
            // return 1; — insert after target
            // return true; — keep default insertion point based on the direction
            // return void; — keep default insertion point based on the direction
            // console.log('onMove...')
        },

        // Called when creating a clone of element
        onClone: function ( /**Event*/ evt) {
            var origEl = evt.item
            var cloneEl = evt.clone
            // console.log('onClone...')
        },

        // Called when dragging element changes position
        onChange: function ( /**Event*/ evt) {
            evt.newIndex // most likely why this event is used is to get the dragging element's current index
            // same properties as onEnd
            // console.log('onChange...', evt.newIndex)
        }
    })
}
var render_approve_table = (table) => {
    removeAllChildNodes(table.tBodies[0])
    store_level.forEach(element => {
        let row = table.tBodies[0].insertRow()
        row.id = element.id
        let cellIndex = row.insertCell()
        cellIndex.innerHTML = `<button 
            class="mr-2 btn-icon btn-sm btn-icon-only btn btn-outline-danger" onclick="deleteLvApprove(${element.id})"><i
                class="pe-7s-trash btn-icon-wrapper"> </i></button>`
        let cellName = row.insertCell()
        cellName.textContent = findNameUser(element.user_approve).name
        let level = row.insertCell()
        level.textContent = element.level
    })
}

// modal method

$('#lv-approve-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    filters = [user.id]
    store_level.forEach(item => filters.push(item.user_approve.id))
    getusers()
        .then(res => {
            if (res.status === 200) {
                let select = modal.find('.modal-body #user')[0]
                let dropdown = res.data.data.filter(item => !filters.includes(item.id))
                dropdown.forEach(item => {
                    select.add(new Option(findNameUser(item).name, item.id))
                })
            }
        })
        .catch(error => {
            console.log(error)
            console.log(error.response.data)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            modal.find('.modal-body #reload').removeClass('reload')
        })
    console.log('show modal...')
    // dropdownRule(group, modal)
})

$('#lv-approve-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #user')[0])
    modal.find('.modal-body #reload').addClass('reload')
    console.log('hide modal...');
})

const addLvApprove = () => {
    let form = {
        users: $('#user').val()
    }
    postUserApproveKPI(form, user.id)
        .then(res => {
            if (res.status === 200) {
                console.log(res.data.data);
                store_level = res.data.data.user_approves
            }
        })
        .catch(error => {
            console.log(error)
            console.log(error.response.data.message)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            $('#lv-approve-modal .close')[0].click()
            let table = document.getElementById('table-approve')
            render_approve_table(table)
            toastClear()
        })
}

const deleteLvApprove = (id) => {
    deleteUserApproveKPI({user_approve : id},user.id)
    .then(res => {
        console.log(res);
        if (res.status === 200) {
            let index = store_level.findIndex(item => item.id === id)
            store_level.splice(index, 1)
            toast(res.data.message,res.data.status)
        }
    })
    .then(() => {
        store_level.forEach((item,i) => item.level = i+1)
    })
    .catch(error => {
        console.log(error)
        toast(error.response.data.message,error.response.data.status)
    })
    .finally(() => {
        let table = document.getElementById('table-approve')
        render_approve_table(table)
        toastClear()
    })
}

$('#copy-to-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    // fetch rules filter
    getusers()
        .then(res => {
            if (res.status === 200) {
                let select = modal.find('.modal-body #user_copy')[0]
                let dropdown = res.data.data.filter(item => item.id !== user.id)
                dropdown.forEach(item => {
                    select.add(new Option(findNameUser(item).name, item.id))
                })
            }
        })
        .catch(error => {
            console.log(error);
            console.log(error.response.data);
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            modal.find('.modal-body #reload').removeClass('reload')
        })
    console.log('show modal...');
    // dropdownRule(group, modal)
})

$('#copy-to-modal').on('hide.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var modal = $(this)
    // var group = button.data('group') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    removeAllChildNodes(modal.find('.modal-body #user_copy')[0])
    modal.find('.modal-body #reload').addClass('reload')
    console.log('hide modal...');
})

const copy_to = () => {
    let form = {
        users: $('#user_copy').val()
    }
    postUserCopyApproveKPI(form,user.id)
    .then(res => {
        if (res.status === 200) {
            toast(res.data.message,res.data.status)
        }
    })
    .catch(error => {
        console.log(error);
        console.log(error.response.data.message);
        toast(error.response.data.message, error.response.data.status)
    })
    .finally(() => {
        $('#copy-to-modal .close')[0].click()
        toastClear()
    })
}

function addRole(user) {
    addRoleApi(user, $(".js-select-role-multiple").val())
        .then((response) => {
            if (response.status === 200) {
                location.reload()
            }
        })
        .catch((error) => {
            console.log(error)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            toastClear()
        })
}

function removeRole(user, role) {
    console.log(user, role)
    removeRoleApi(user, role).then((response) => {
            if (response.status == 200) {
                location.reload()
            }
        })
        .catch((error) => {
            console.log(error)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            toastClear()
        })
}

function addSystem(user) {
    addSystemApi(user, $(".js-select-system-multiple").val())
        .then((response) => {
            if (response.status == 200) {
                location.reload()
            }
        })
        .catch((error) => {
            console.log(error)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            toastClear()
        })
}

function removeSystem(user, system) {
    console.log(user, system)
    removeSystemApi(user, system)
        .then((response) => {
            if (response.status == 200) {
                location.reload()
            }
        })
        .catch((error) => {
            console.log(error)
            toast(error.response.data.message, error.response.data.status)
        })
        .finally(() => {
            toastClear()
        })
}
