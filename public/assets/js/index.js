//get the current defaultOptions
// var defaultOptions = OverlayScrollbars.defaultOptions();
//set new default options
// OverlayScrollbars.defaultOptions({
//     className: "os-theme-dark",
//     resize: "both"
// });
document.addEventListener("DOMContentLoaded", function () {
    //The first argument are the elements to which the plugin shall be initialized
    //The second argument has to be at least a empty object or a object with your desired options
    // OverlayScrollbars(document.body, {});
});


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
            console.log(res);
            e.offsetParent.getElementsByTagName('a')[0].href = `${window.location.href.split('/').slice(0, 3).join('/')}/storage/${res.data.path}`
            e.offsetParent.getElementsByTagName('a')[0].textContent = `view file`
            e.offsetParent.querySelector(`input[name='${e.dataset.name}']`).value = res.data.path

            e.offsetParent.getElementsByClassName('progress-bar')[0].style.width = `100%`
            // e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `100%`
            e.offsetParent.getElementsByClassName('progress-bar')[0].textContent = `Success !`
        })
        .catch(err => {
            console.error(err.response);
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

/**
 * @params {text} ข้อความที่จะให้แสดง
 * @params {type} success , error
 */
function toast(text, type) {
    let divToast = document.createElement("div")
    let divMessage = document.createElement("div")
    divToast.classList.add('toast')
    divToast.classList.add(`toast-${type}`)
    divMessage.classList.add('toast-message')
    divMessage.textContent = text
    divToast.appendChild(divMessage);
    document.getElementById("toast-container").appendChild(divToast)
}
/**
 * @return run clear alert
 */
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

/**
 * @params {visible} bool : true | false
 * @return disable page
 */
var setVisible = (visible) => {
    window.scroll({
        top: 0,
        behavior: "smooth"
    })
    var intervalId = window.setInterval(function () {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalId);
        }
    }, 0);
    document.querySelector('#loading').style.display = visible ? 'block' : 'none';
}
/**
 * @params {elements} string : button,select,input
 * @return disable page
 */
var pageDisable = (elements = '') => {
    let inner_btn = document.getElementsByClassName('app-main__inner')[0].querySelectorAll(elements === '' ? `button,select,input` : elements)
    for (let index = 0; index < inner_btn.length; index++) {
        const element = inner_btn[index]
        if (!element.classList.contains('no-disable')) {
            element.disabled = true
        }
    }
}

var pageEnable = () => {
    let inner_btn = document.getElementsByClassName('app-main__inner')[0].querySelectorAll('button,select,input')
    for (let index = 0; index < inner_btn.length; index++) {
        const element = inner_btn[index]
        element.disabled = false
    }
}
/**
 * @params {type} string : number,text
 * @params {classList} string||Array : "class_name" | ["class_name1","class_name2"]
 * @params {name} string : "name"
 * @params {value} string : "value"
 * @params {id} string : "id"
 * @params {nameMethod} string : "changeActualValue(this)"
 * @return {HTMLElement} HTMLElement
 */
var newInput = (type, classList = '', name = '', value = '', id = '', nameMethod = '', readonly = false) => {
    let element = document.createElement(`input`)
    element.setAttribute(`type`, type)
    element.setAttribute(`name`, name)
    element.setAttribute(`id`, id)
    element.setAttribute(`value`, value)
    if (nameMethod !== '') {
        element.setAttribute(`onchange`, nameMethod)
    }

    if (type === 'number') {
        // element.setAttribute(`min`, 0)
        element.setAttribute(`step`, 0.01)
    }
    if (Array.isArray(classList)) {
        classList.forEach(name => {
            element.classList.add(name)
        })
    } else {
        element.className = classList
    }
    if (readonly) {
        element.setAttribute(`readonly`, readonly)
    }
    return element
}

/**
 * @params {HTMLElement} element
 * @params {Attribute} Object: {"key1" : "value1","key2" : "value2",}
 */
var setAttributes = (el, attrs) => {
    for (var key in attrs) {
        el.setAttribute(key, attrs[key]);
    }
}

var round = (num) => {
    var m = Number((Math.abs(num) * 100).toPrecision(15));
    return Math.round(m) / 100 * Math.sign(num);
}

var findNameUser = (user) => {
    return user.translations.find(item => item.locale === locale) ?? user.translations[0]
}

var removeAllChildNodes = (parent) => {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}
