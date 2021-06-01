document.addEventListener("DOMContentLoaded", function () {
    //The first argument are the elements to which the plugin shall be initialized
    //The second argument has to be at least a empty object or a object with your desired options
    window.localStorage.setItem('tab-dashboard', `tab-c-1`)
    OverlayScrollbars(document.getElementsByClassName('table-responsive'), {
        className: "os-theme-dark",
        resize: "both",
        sizeAutoCapable: true,
        clipAlways: true,
        normalizeRTL: true,
        paddingAbsolute: false,
        autoUpdate: null,
        autoUpdateInterval: 33,
        updateOnLoad: ["img"],
        nativeScrollbarsOverlaid: {
            showNativeScrollbars: false,
            initialize: true
        },
        overflowBehavior: {
            x: "scroll",
            y: "scroll"
        },
        scrollbars: {
            visibility: "auto",
            autoHide: "never",
            autoHideDelay: 800,
            dragScrolling: true,
            clickScrolling: false,
            touchSupport: true,
            snapHandle: false
        },
        textarea: {
            dynWidth: false,
            dynHeight: false,
            inheritedAttrs: ["style", "class"]
        },
        callbacks: {
            onInitialized: null,
            onInitializationWithdrawn: null,
            onDestroyed: null,
            onScrollStart: null,
            onScroll: null,
            onScrollStop: null,
            onOverflowChanged: null,
            onOverflowAmountChanged: null,
            onDirectionChanged: null,
            onContentSizeChanged: null,
            onHostSizeChanged: null,
            onUpdated: null
        }
    });

    let active_tab = localStorage.getItem('tab-dashboard')
    if (active_tab) {
        let content_id = null
        let ele_active = document.getElementById(active_tab)
        let scope = ele_active.parentNode.parentNode.parentNode
        for (let index = 0; index < scope.firstElementChild.children.length; index++) {
            const element = scope.firstElementChild.children[index];
            if (element.firstElementChild.id === active_tab) {
                element.firstElementChild.classList.add('active')
                content_id = element.firstElementChild.href.substring(element.firstElementChild.href.search("#") + 1, element.firstElementChild.href.length)
            } else {
                element.firstElementChild.classList.remove('active')
            }
        }

        if (content_id) {
            let contents = document.getElementById(content_id).parentElement
            for (let index = 0; index < contents.children.length; index++) {
                const element = contents.children[index];
                if (content_id === element.id) {
                    element.classList.add('active')
                } else {
                    element.classList.remove('active')
                }
            }
        }
    }
});

var search_table = (e) => {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = e;
    filter = input.value.toUpperCase()
    table = input.offsetParent.querySelector('table')
    tr = table.tBodies[0].rows
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

var search = () => {
    document.forms['form-search'].submit();
}
