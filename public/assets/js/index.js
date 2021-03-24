function validationForm(forms) {
    Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}

Date.prototype.toDateInputValue = (function () {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0, 10);
});

/**
 * @params {element} document.getElementById
 * @return void
 */
var displayFileName = (e) => {
    if (e) {
        if (e.dataset.cache) {
            let f = new File([""], e.dataset.cache, {
                type: "application/pdf",
                lastModified: Date.now()
            })
            e.files = new FileListItems([f])
        }
    }
}

/**
 * @params {array} files List of file items
 * @return FileList
 */
function FileListItems(files) {
    var b = new ClipboardEvent("").clipboardData || new DataTransfer()
    for (var i = 0, len = files.length; i < len; i++) b.items.add(files[i])
    return b.files
}
/**
 * @params {element} document.getElementById
 * @return FileList
 */
function enterNoSubmit(e) {
    e.onkeypress = function (e) {
        if (e.which == 13) {
            e.preventDefault();
            return false;
        }
    }
}

/**
 * @params {element} document.getElementById
 * @return path string
 */
var uploadFileContract = async e => {
    const configUploadProgress = {
        onUploadProgress: function (progressEvent) {
            var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            let progress = e.offsetParent.getElementsByClassName('progress')[0]
            progress.classList.add('show-contract')
            progress.classList.remove('hide-contract')
            progress.children[0].style.width = `${percentCompleted-7}%`
            progress.children[0].textContent = `${percentCompleted-7}%`

        },
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }

    e.offsetParent.getElementsByClassName('progress-bar')[0].classList.remove('bg-danger')
    e.offsetParent.getElementsByClassName('progress-bar')[0].classList.add('bg-success')
    let uri = "/legal/uploadfilecontract"
    let data = new FormData()
    data.append('file', e.files[0])

    axios.post(uri, data, configUploadProgress)
        .then(res => {
            e.offsetParent.getElementsByTagName('a')[0].href = `${window.location.href.split('/').slice(0, 3).join('/')}/storage/${res.data.path}`
            e.offsetParent.getElementsByTagName('a')[0].textContent = `view file`
            e.offsetParent.querySelector(`input[name='${e.dataset.name}']`).value = res.data.path

            e.offsetParent.getElementsByClassName('progress-bar')[0].style.width = `100%`
            // e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `100%`
            e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `Success !`
        })
        .catch(err => {
            e.offsetParent.getElementsByClassName('progress-bar')[0].classList.remove('bg-success')
            e.offsetParent.getElementsByClassName('progress-bar')[0].classList.add('bg-danger')
            if (Array.isArray(err.response.data.file)) {
                e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `${err.response.data.file[0]}`
            } else {
                e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `${err.response.statusText}`
            }

        })
}

/**
 * @params {element} document.getElementById
 * @return path string
 */
var uploadFileEquipment = async e => {
    const configUploadProgress = {
        onUploadProgress: function (progressEvent) {
            var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            let progress = e.offsetParent.getElementsByClassName('progress')[0]
            progress.classList.add('show-progress')
            progress.classList.remove('hide-progress')
            progress.children[0].style.width = `${percentCompleted-7}%`
            progress.children[0].textContent = `${percentCompleted-7}%`

        },
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }

    e.offsetParent.getElementsByClassName('progress-bar')[0].classList.remove('bg-danger')
    e.offsetParent.getElementsByClassName('progress-bar')[0].classList.add('bg-success')
    let uri = "/admin/uploadfileequipment"
    let data = new FormData()
    data.append('file', e.files[0])

    axios.post(uri, data, configUploadProgress)
        .then(res => {
            e.offsetParent.querySelector(`input[name='${e.dataset.name}']`).value = res.data.path
            e.offsetParent.offsetParent.querySelector(`img`).src = `${window.location.href.split('/').slice(0, 3).join('/')}/storage/${res.data.path}`
            e.offsetParent.getElementsByClassName('progress-bar')[0].style.width = `100%`
            // e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `100%`
            e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `Success !`
        })
        .catch(err => {
            console.log(err);
            e.offsetParent.getElementsByClassName('progress-bar')[0].classList.remove('bg-success')
            e.offsetParent.getElementsByClassName('progress-bar')[0].classList.add('bg-danger')
            if (Array.isArray(err.response.data.file)) {
                e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `${err.response.data.file[0]}`
            } else {
                e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `${err.response.statusText}`
            }
        })
        .finally(() => console.log(document.getElementsByName('image')[0]))
}

var btn = $('#btnontop');

$(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show')
    } else {
        btn.removeClass('show');
    }
});

btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: 0
    }, '300');
});

function toastError(text) {
    let divToast = document.createElement("div")
    let divMessage = document.createElement("div")
    divToast.classList.add('toast')
    divToast.classList.add('toast-error')
    divMessage.classList.add('toast-message')
    divMessage.textContent = text
    divToast.appendChild(divMessage);
    document.getElementById("toast-container").appendChild(divToast)
}

function toastSuccess(text) {
    let divToast = document.createElement("div")
    let divMessage = document.createElement("div")
    divToast.classList.add('toast')
    divToast.classList.add('toast-success')
    divMessage.classList.add('toast-message')
    divMessage.textContent = text
    divToast.appendChild(divMessage);
    document.getElementById("toast-container").appendChild(divToast)
}

function toastClear() {
    let error = document.querySelectorAll('.toast-error')
    let success = document.querySelectorAll('.toast-success')
    setTimeout(() => {
        if (error.length >= 1) {
            document.querySelector('.toast-error').remove()
            toastClear()
        }
        if (success.length >= 1) {
            document.querySelector('.toast-success').remove()
            toastClear()
        }
    }, 5000)
}

var createInput = (element, type, classList = '', name = '', value = '', id = '') => {
    element.setAttribute(`type`, type)
    element.setAttribute(`name`, name)
    element.setAttribute(`id`, id)
    element.setAttribute(`value`, value)
    if (type === 'number') {
        element.setAttribute(`min`, 0)
        element.setAttribute(`step`, 0.01)
    }
    if (Array.isArray(classList)) {
        classList.forEach(name => {
            element.classList.add(name)
        })
    } else {
        element.className = classList
    }

    return element
}

var createOption = (element, value = '', name = '') => {
    element.value = value
    element.textContent = name
    return element
}

var sweetalert = (title, text) => {
    let timerInterval
    Swal.fire({
        title: text,
        html: title,
        timer: 3000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                // const content = Swal.getContent()
                // if (content) {
                //     const b = content.querySelector('b')
                //     if (b) {
                //         b.textContent = Swal.getTimerLeft()
                //     }
                // }
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
        }
    })
}

// KPI
var formulaRuleDetail = (e, key) => {
    let index = e.offsetParent.cellIndex
    let tr = e.offsetParent.parentNode
    let foot = tr.parentNode.parentNode.tFoot
    let actual = parseFloat(tr.cells[index].firstChild.value)
    let target = parseFloat(tr.cells[index - 1].firstChild.textContent)

    if (evaluateDetail[key].rule.calculate_type !== null) {
        let ach = parseFloat((actual / target) * 100)
        evaluateDetail[key].actual = e.value

        if (evaluateDetail[key].rule.calculate_type === 'Amount') {
            tr.cells[index + 1].firstChild.textContent = ach.toFixed(2) + '%'
            evaluateDetail[key].ach = ach
        }
        if (evaluateDetail[key].rule.calculate_type === 'Percent') {
            tr.cells[index + 1].firstChild.textContent = actual.toFixed(2) + '%'
            evaluateDetail[key].ach = actual
        }

        if (evaluateDetail[key].ach < 70) {
            tr.cells[index + 2].firstChild.textContent = '0.00%'
            evaluateDetail[key].cal = 0
        } else if (evaluateDetail[key].ach > evaluateDetail[key].base_line) {
            let c = parseFloat(evaluateDetail[key].base_line) * parseFloat(evaluateDetail[key].weight) / 100
            tr.cells[index + 2].firstChild.textContent = c.toFixed(2) + '%'
            evaluateDetail[key].cal = c
        } else {
            let d = evaluateDetail[key].ach * parseFloat(evaluateDetail[key].weight) / 100
            tr.cells[index + 2].firstChild.textContent = d.toFixed(2) + '%'
            evaluateDetail[key].cal = d
        }

        let sumCal = evaluateDetail.filter(item => {
            return item.rule.category.name === evaluateDetail[key].rule.category.name
        }).reduce(function (total, currentValue) {
            return total + currentValue.cal
        }, 0)
        foot.lastElementChild.cells[foot.lastElementChild.childElementCount - 1].textContent = parseFloat(sumCal).toFixed(2) + '%'
    } else {
        evaluateDetail[key].actual = 0
        tr.cells[index].firstChild.value = 0
        // alert(`${tr.cells[1].firstChild.textContent} ไม่ทราบ calculate type แจ้ง Admin`)
        e.focus()
    }


    // console.log(evaluateDetail[key].rule)
    // console.log(tr.cells[index],tr.cells[index-1]);
}
