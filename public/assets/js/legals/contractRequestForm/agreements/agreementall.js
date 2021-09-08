class Purchase {
    id = null
    desc = String()
    qty = 0
    unit_price = 0
    price = 0
    discount = 0
    amount = 0
    contract_id = null

    constructor(id = null,
        desc = String(),
        qty = 0,
        unit_price = 0,
        price = 0,
        discount = 0,
        amount = 0,
        contract_id = null) {
        this.id = id
        this.desc = desc
        this.qty = qty
        this.unit_price = unit_price
        this.price = price
        this.discount = discount
        this.amount = amount
        this.contract_id = contract_id
    }
}



var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}

var createRow = () => {
    const obj = new Purchase()
    obj.desc = document.getElementById('desc').value
    obj.qty = parseFloat(document.getElementById('qty').value)
    obj.unit_price = parseFloat(document.getElementById('unit_price').value)
    obj.discount = parseFloat(document.getElementById('discount').value)
    obj.contract_id = parseInt(document.getElementById('contract_id').value)


    postComercialLists(obj).then(result => {
        if (result.status === 201) {
            document.getElementById('desc').required = false
            document.getElementById('qty').required = false
            document.getElementById('unit_price').required = false
            // document.getElementById('discount').required = false
            document.getElementById('desc').value = ''
            document.getElementById('qty').value = ''
            document.getElementById('unit_price').value = ''
            document.getElementById('discount').value = ''
        }
    }).catch(err => {
        console.error(err.response.data);
        for (const key in err.response.data.data) {
            if (Object.hasOwnProperty.call(err.response.data.data, key)) {
                const element = err.response.data.data[key]
                document.getElementById(key).focus()
                element.forEach(message => {
                    toast(message, err.response.data.status)
                })
            }
        }

    }).finally(() => {
        toastClear()
        comercialLists(document.getElementById('contract_id').value)
    })
}

var deleteRow = (id) => {
    deleteComercialLists(id).then(result => {
        if (result.data.status) {
            comercialLists(document.getElementById('contract_id').value)
        }
    }).catch(err => {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Purchase list delete fail',
            showConfirmButton: false,
            timer: 2000
        })
    })
}

var postComercialLists = formData => {
    return axios.post('/legal/contract-request/comerciallists', formData)
}

var getComercialLists = id => {
    return axios.get('/legal/contract-request/comerciallists/' + id + '/edit')
}

var deleteComercialLists = id => {
    return axios.delete('/legal/contract-request/comerciallists/' + id)
}

var generateRowFromComercial = (data) => {
    const table = document.getElementById('table-comercial-lists').tBodies[0]
    let newRow = table.insertRow()
    let newCell0 = newRow.insertCell(0),
        newCell1 = newRow.insertCell(1),
        newCell2 = newRow.insertCell(2),
        newCell3 = newRow.insertCell(3),
        newCell4 = newRow.insertCell(4)
    let btn = document.createElement('button')
    btn.innerHTML = "delete"
    btn.type = 'button'
    btn.className = 'btn btn-danger sm'
    btn.setAttribute('onclick', `deleteRow(${data.id})`)

    newCell0.appendChild(btn)
    newCell1.appendChild(document.createTextNode(data.description))
    newCell2.appendChild(document.createTextNode(data.unit_price))
    newCell3.appendChild(document.createTextNode(data.discount))
    newCell4.appendChild(document.createTextNode(data.amount))
}

var comercialLists = (id) => {
    if (id) {
        let PurchaseList = []
        getComercialLists(id).then(result => {
                if (result.data.length > 0) {
                    result.data.forEach(item => {
                        const model = new Purchase()
                        model.id = item.id
                        model.desc = item.description
                        model.qty = item.qty
                        model.unit_price = item.unit_price
                        model.price = item.price
                        model.discount = item.discount
                        model.amount = item.amount
                        model.contract_id = item.contract_id
                        // console.log(model);
                        PurchaseList.push(model)
                    });
                }
            }).catch(err => {
                let errors = err.response.data.errors
                for (const key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        const element = errors[key];
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: `${element}`,
                            showConfirmButton: false,
                            timer: 5000
                        })
                    }
                }
            })
            .finally(() => {
                let table = document.getElementById('table-comercial-lists')
                removeAllChildNodes(table.tBodies[0])
                if (PurchaseList.length < 1) {
                    document.getElementById('desc').required = true
                    document.getElementById('qty').required = true
                    document.getElementById('unit_price').required = true
                    // document.getElementById('discount').required = true
                }
                PurchaseList.forEach((element, index) => {
                    let newRow = table.tBodies[0].insertRow()
                    let newCell0 = newRow.insertCell(0),
                        newCell1 = newRow.insertCell(1),
                        newCell2 = newRow.insertCell(2),
                        newCell3 = newRow.insertCell(3),
                        newCell4 = newRow.insertCell(4),
                        newCell5 = newRow.insertCell(5),
                        newCell6 = newRow.insertCell(6),
                        newCell7 = newRow.insertCell(7)

                    newCell0.innerHTML = index + 1
                    newCell1.innerHTML = element.desc
                    newCell2.innerHTML = element.qty
                    newCell3.innerHTML = element.unit_price
                    newCell4.innerHTML = element.price
                    newCell5.innerHTML = element.discount
                    newCell6.innerHTML = element.amount
                    newCell7.innerHTML = `<a data-toggle="tooltip" title="delete contract" data-placement="bottom"
                    rel="noopener noreferrer" style="color: white;"
                    class="btn btn-danger btn-sm" onclick="deleteRow(${element.id})"><i
                        class="pe-7s-trash"> </i></a>`
                })

                document.getElementById('total').textContent = PurchaseList.reduce((accumulator, item) => accumulator + item.amount,0)
            })
    }
}

var calMonthToYear = (e) => {
    document.getElementById('validationWarrantyForYear').value = `${parseInt(e.value/12)}.${e.value%12}`
}
