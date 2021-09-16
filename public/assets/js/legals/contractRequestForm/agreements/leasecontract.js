(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        let contract = document.getElementById('contract_id')
        let poFile = document.getElementById('validationPurchaseOrderFile')
        let quotationFile = document.getElementById('validationQuotationFile')
        let coparationFile = document.getElementById('validationCoparationFile')
        let insurancepolicyFile = document.getElementById('InsurancePolicyFile')
        let cerofownershipFile = document.getElementById('CerOfOwnershipFile')

        let contractType = document.getElementById('validationContractType')
        // Supporting Documents
        displayFileName(poFile)
        displayFileName(quotationFile)
        displayFileName(coparationFile)
        displayFileName(insurancepolicyFile)
        displayFileName(cerofownershipFile)

        // Comercial Terms
        if (contract) {
            comercialLists(contract.value)
        }
        // Payment Terms
        if (contract_attr.legal_contract_dest.sub_type_contract_id) {
            // subtype
            const select = document.getElementById('validationSubType')
            if (select) {
                const options = Array.from(select.options)
                let dataset = ""
                options.forEach((option, i) => {
                    if (parseInt(option.value) === contract_attr.legal_contract_dest.sub_type_contract_id) {
                        select.selectedIndex = i
                        dataset = option.dataset.id
                    }
                })
                make_subtype(dataset)
            }
            make_contract_type(contract_attr.legal_contract_dest.sub_type_contract_id)
            changeType(contract_attr.legal_contract_dest.payment_type_id)

        }

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
        // console.log(payment_type);
    }, false);

})();

const form = document.getElementById('form-leasecontract');
form.addEventListener('submit', logSubmit);
async function logSubmit(event) {
    // let onSubmit = false
    try {
        let check_list = await getComercialLists(document.getElementById('contract_id').value)
        if (check_list.data.length < 1) {
            document.getElementById('desc').required = true
            document.getElementById('qty').required = true
            document.getElementById('unit_price').required = true
            toast('Can’t find purchase', 'error')
        } else {
            document.getElementById('desc').required = false
            document.getElementById('qty').required = false
            document.getElementById('unit_price').required = false
            // onSubmit = false
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            validationForm(forms)
        }

    } catch (error) {
        console.error(error)
    } finally {
        // console.log(event)
        // if (onSubmit) {
        //     document.getElementById('form-leasecontract').submit()
        // }
        // debugger
        toastClear()
    }
}

var changeType = (e) => {
    let text_search = "";
    if (Number.isInteger(e)) {
        text_search = payment_type.find(item => item.id === e).name
    } else {
        e.selectedOptions[e.selectedIndex]
        // console.log(e);
        // debugger
        text_search = e.selectedOptions[0].text
    }
    let scope = document.getElementById("contract-render")
    let arr = []
    for (const iterator of scope.children) {
        if (iterator.dataset.id) {
            iterator.classList.add('hide-contract')
            if (iterator.dataset.id.split(",").includes(text_search)) {
                arr.push(iterator)
            }
        }
    }
    if (arr.length > 0) {
        arr[0].classList.remove('hide-contract')
        // arr[0].classList.add('show-contract')
    }
}

var changeContractValue = (e) => {
    let el = document.getElementById(e.offsetParent.id)
    setValueOfContract(el)
    enterNoSubmit(e)
}
var setValueOfContract = (e) => {
    if (e) {
        let el = e.children[0].children
        let total = 100 - parseInt(el[0].children[0].value) - parseInt(el[1].children[0].value)
        el[2].children[0].value = total

        document.getElementsByName('value_of_contract')[0].value = `${el[0].children[0].value},${el[1].children[0].value},${el[2].children[0].value}`
    } else {
        document.getElementsByName('value_of_contract')[0].value = ""
    }

}
document.getElementById('validationSubType').addEventListener('change', (event) => {
    console.log('validationSubType 1');
    let dataset = event.target.options[event.target.options.selectedIndex].dataset.id
    make_subtype(dataset)
    make_contract_type(parseInt(event.target.value))
})

// document.getElementById('validationSubType').addEventListener('change', (event) => {
//     console.log('validationSubType 2');
    
// })

var make_subtype = (dataset) => {
    let is_show = ['wh-contract', 'st-contract'],
        forklift = ['forklifts'],
        equip = ['equipment', 'IT Device']
    document.getElementById('validationScope').placeholder = ''
    document.getElementById('validationLocation').placeholder = ''

    if (is_show.includes(dataset)) {
        document.getElementById('InsurancePolicyFile').parentElement.classList.remove('hide-contract')
        document.getElementById('InsurancePolicyFile').required = true
        document.getElementById('CerOfOwnershipFile').parentElement.classList.remove('hide-contract')
        document.getElementById('CerOfOwnershipFile').required = true
        document.getElementById('validationScope').placeholder = 'e.g. lease warehouse/storage for 2,000 sqm.'
        document.getElementById('validationLocation').placeholder = 'e.g. Nadi warehouse/storage'
    } else {
        document.getElementById('InsurancePolicyFile').parentElement.classList.add('hide-contract')
        document.getElementById('InsurancePolicyFile').required = false
        document.getElementById('CerOfOwnershipFile').parentElement.classList.add('hide-contract')
        document.getElementById('CerOfOwnershipFile').required = false

    }
    if (forklift.includes(dataset)) {
        document.getElementById('validationScope').placeholder = 'e.g. lease forklift to support WAC'
        document.getElementById('validationLocation').placeholder = 'e.g. WAC'
    }
    if (equip.includes(dataset)) {
        document.getElementById('validationScope').placeholder = 'e.g. lease Router to support business operation'
        document.getElementById('validationLocation').placeholder = 'e.g. Server Room'
    }
}
function make_contract_type (subtype) {
    let new_payment = payment_type.filter(item => item.subtype_id === subtype)
    let parent = document.getElementById('validationContractType')
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }

    parent.appendChild(new Option('Choose....', ''))
    new_payment.forEach(element => {
        if (contract_attr.legal_contract_dest) {
            parent.appendChild(new Option(element.name, element.id, false, element.id === contract_attr.legal_contract_dest.payment_type_id ? true : false))
        } else {
            parent.appendChild(new Option(element.name, element.id))
        }

    });
}
