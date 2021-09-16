(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        let subtype = document.getElementById('validationSubType')
        if (subtype) {
            changeSubType(subtype)
        }
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)

    }, false);

})();

var changeSubType = (e) => {
    let uid = e.options[e.selectedIndex]
    let array = document.querySelectorAll('.sub-type')
    array.forEach(element => {
        if (element.id === uid.dataset.id) {
            let form = element.getElementsByTagName('form')[0]
            let key = form.querySelector("input[name='sub_type_contract_id']")
            key.value = uid.value
            displayFileBySubType(form)
            element.classList.remove('hide-contract')
            element.classList.add('show-contract')
        } else {
            element.classList.remove('show-contract')
            element.classList.add('hide-contract')
        }
    });
}

var totalOfMaid = () => {
    let road = document.getElementById('validationRoad').value
    let building = document.getElementById('validationBuilding').value
    let toilet = document.getElementById('validationToilet').value
    let canteen = document.getElementById('validationCanteen').value
    let washing = document.getElementById('validationWashing').value
    let water = document.getElementById('validationWater').value
    let mowing = document.getElementById('validationMowing').value
    let general = document.getElementById('validationGeneral').value
    let total = parseInt(road) + parseInt(building) + parseInt(toilet) + parseInt(canteen) + parseInt(washing) + parseInt(water) + parseInt(mowing) + parseInt(general)
    document.getElementById('total').value = total
}


var displayFileBySubType = e => {
    if (e.id === 'form-bus') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        displayFileName(e.querySelector("input[id='validationTransportationPermission']"))
        displayFileName(e.querySelector("input[id='validationVehicleRegistration']"))
        displayFileName(e.querySelector("input[id='validationRoute']"))
        displayFileName(e.querySelector("input[id='validationInsurance']"))
        displayFileName(e.querySelector("input[id='validationDriverLicense']"))
    }
    if (e.id === 'form-cleaning') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        totalOfMaid()
    }
    if (e.id === 'form-cook') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
    }
    if (e.id === 'form-doctor') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        displayFileName(e.querySelector("input[id='validationDoctorLicense']"))
    }
    if (e.id === 'form-nurse') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        displayFileName(e.querySelector("input[id='validationNurseLicense']"))
    }
    if (e.id === 'form-security') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        displayFileName(e.querySelector("input[id='validationSecurityService']"))
        displayFileName(e.querySelector("input[id='validationSecurityGuardLicense']"))
    }
    if (e.id === 'form-subcontractor') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
    }
    if (e.id === 'form-transportation') {
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))
        displayFileName(e.querySelector("input[id='validationInsurance']"))
    }
    if (e.id === 'form-it') {
        
    }
}
