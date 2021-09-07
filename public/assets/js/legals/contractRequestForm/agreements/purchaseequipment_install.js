(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        let contract = document.getElementById('contract_dests_id')
        let poFile = document.getElementById('validationPurchaseOrderFile')
        let quotationFile = document.getElementById('validationQuotationFile')
        let coparationFile = document.getElementById('validationCoparationFile')
        let boqFile = document.getElementById('validationBOQFile')

        let contractType = document.getElementById('validationContractType')
        let warranty = document.getElementById('validationWarranty')
        // Supporting Documents
        displayFileName(poFile)
        displayFileName(quotationFile)
        displayFileName(coparationFile)
        displayFileName(boqFile)
        // Comercial Terms
        if (contract) {
            comercialLists(contract.value)
        }
        // Payment Terms
        if (contractType) {
            changeType(contractType)
        }
        // warranty
        calMonthToYear(warranty)

    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)
    }, false);
})();

const form = document.getElementById('form-purchaseequipmentinstall');
form.addEventListener('submit', logSubmit);
async function logSubmit(event) {
    let onSubmit = false
    try {
        await getComercialLists(document.getElementById('contract_dests_id').value).then(result => {
            if (result.data.length < 1) {
                document.getElementById('desc').required = true
                document.getElementById('qty').required = true
                document.getElementById('unit_price').required = true
                // document.getElementById('discount').required = true
                toast('Can’t find purchase', 'error')
            } else {
                if (!document.getElementById('form-purchaseequipmentinstall').checkValidity()) {
                    // toast('Can’t find purchase', 'error')
                }else{
                    document.getElementById('desc').required = false
                    document.getElementById('qty').required = false
                    document.getElementById('unit_price').required = false
                    // document.getElementById('discount').required = false
                    onSubmit = true
                }
            }
        })

    } catch (error) {
        console.error(error)
    } finally {
        if (onSubmit) {
            document.getElementById('form-purchaseequipmentinstall').submit()
        }
        toastClear()
    }
}

var changeType = (e) => {
    let firstContract = document.getElementById("contractType1")
    switch (e.value) {
        case '4':
            firstContract.classList.remove('hide-contract');
            firstContract.classList.add('show-contract');
            setValueOfContract(firstContract)
            break;
        default:
            firstContract.classList.remove('show-contract');
            firstContract.classList.add('hide-contract');
            document.getElementsByName('value_of_contract')[0].value = ""
            break;
    }
}
var changeContractValue = (e) => {
    let el = document.getElementById(e.offsetParent.id)
    setValueOfContract(el)
    enterNoSubmit(e)
}
var setValueOfContract = (e) => {
    let el = e.children[0].children
    let total = 100 - parseInt(el[0].children[0].value) - parseInt(el[1].children[0].value)
    el[2].children[0].value = total

    document.getElementsByName('value_of_contract')[0].value = `${el[0].children[0].value},${el[1].children[0].value},${el[2].children[0].value}`
}
