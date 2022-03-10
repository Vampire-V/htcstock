(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        let subtype = document.getElementById('validationSubType')
        let payment_type = document.getElementById('validationContractType')
        let contract = document.getElementById('contract_id')
        if (subtype) {
            changeSubType(subtype)
        }
        if (contract) {
            comercialLists(contract.value)
        }
        if (payment_type) {
            changeType(payment_type)
        }
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation')
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
        displayFileName(e.querySelector("input[id='validationPurchaseOrderFile']"))
        displayFileName(e.querySelector("input[id='validationQuotationFile']"))
        displayFileName(e.querySelector("input[id='validationCoparationFile']"))

        changeType(e.querySelector("input[id='validationContractType']"))
        calMonthToYear(e.querySelector("input[id='validationWarranty']"))
    }
}

var changeType = (e) => {

    if (e) {
        let firstContract = document.getElementById("contractType1")
        switch (e.options[e.selectedIndex].text) {
            case 'PC':
                firstContract.classList.remove('hide-contract');
                setValueOfContract(firstContract)
                break;
            default:
                firstContract.classList.add('hide-contract');
                document.getElementsByName('value_of_contract')[0].value = ""
                break;
        }
    }
}
var changeContractValue = (e) => {
    let el = document.getElementById(e.offsetParent.id)
    setValueOfContract(el)
    // enterNoSubmit(e)
}
var setValueOfContract = (e) => {
    let el = e.children[0].children
    let values = []
    Array.from(el).forEach(li => values.push(`${li.children[0].value}:${li.children[2].value}:${li.children[4].value}`))
    // let total = 100 - parseInt(el[0].children[0].value) - parseInt(el[1].children[0].value)
    // el[2].children[0].value = total
    document.getElementsByName('value_of_contract')[0].value = values.join('|')

    // document.getElementsByName('value_of_contract')[0].value = `${el[0].children[0].value},${el[1].children[0].value},${el[2].children[0].value}`
}
var addInstallmentPayment = () => {
    let scopeDiv = document.getElementById("contractType1")
    let ul = scopeDiv.children[0]
    var li = document.createElement("li");
    li.className = 'li-none-type'
  li.innerHTML = `<input type="number" value="0" class="type-contract-input" min="0" max="100"
  onchange="changeContractValue(this)">%
  <span>of the total value of a contract within</span>
  <input type="number" value="0" class="type-contract-input" min="0" onchange="changeContractValue(this)">
  <span>days from the date of</span>
  <input type="text" value="" class="type-contract-input" style="width: 35%" onchange="changeContractValue(this)">`
  ul.appendChild(li)
}

const form = document.getElementById('form-it');
form.addEventListener('submit', logSubmit);
async function logSubmit(event) {
    let onSubmit = false
    try {
        await getComercialLists(document.getElementById('contract_id').value).then(result => {
            if (result.data.length < 1) {
                document.getElementById('desc').required = true
                document.getElementById('qty').required = true
                document.getElementById('unit_price').required = true
                // document.getElementById('discount').required = true
                toast('Can’t find purchase', 'error')
            } else {
                // if (!document.getElementById('form-workservicecontract').checkValidity()) {
                //     // toast('Can’t find purchase', 'error')
                // }else{
                    document.getElementById('desc').required = false
                    document.getElementById('qty').required = false
                    document.getElementById('unit_price').required = false
                    // document.getElementById('discount').required = false
                    onSubmit = true
                // }
            }
        })

    } catch (error) {
        console.error(error)
    } finally {
        if (onSubmit && document.getElementById('form-it').checkValidity()) {
            document.getElementById('form-it').submit()
        }
        toastClear()
    }
}
