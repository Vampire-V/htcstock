// API
function addRoleApi(user, roles) {
    return axios.post(`/admin/${user}/addrole`, {
        roles: roles
    })
}

function removeRoleApi(user, role) {
    return axios.delete(`/admin/${user}/removerole`, {
        data: {
            role: role
        }
    })
}

function addSystemApi(user, system) {
    console.log(system)
    return axios.post(`/admin/${user}/addsystem`, {
        system: system
    })
}

function removeSystemApi(user, system) {
    return axios.delete(`/admin/${user}/removesystem`, {
        data: {
            system: system
        }
    })
}
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        $(".js-select-role-multiple").select2({
            tags: true,
            placeholder: "Select a role"
        });
        $(".js-select-system-multiple").select2({
            tags: true,
            placeholder: "Select a system"
        });
        // Loop over them and prevent submission

    }, false);
})();


function addRole(user) {
    addRoleApi(user, $(".js-select-role-multiple").val())
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
