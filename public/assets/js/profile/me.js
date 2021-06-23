// const { template } = 'filepond';

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Supporting Documents
        $("#head_id").select2({
            placeholder: 'Select Manager',
            allowClear: true
        })
        $("#division").select2({
            placeholder: 'Select Division',
            allowClear: true
        })
        $("#department").select2({
            placeholder: 'Select Department',
            allowClear: true
        })
        $("#position").select2({
            placeholder: 'Select Position',
            allowClear: true
        })

        FilePond.setOptions({
            allowDrop: false,
            allowReplace: false,
            instantUpload: false,
            server: {
                url: window.location.origin,
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    // fieldName is the name of the input field
                    // file is the actual file object to send
                    const formData = new FormData();
                    formData.append(fieldName, file, file.name);

                    // related to aborting the request
                    const CancelToken = axios.CancelToken;
                    const source = CancelToken.source();

                    // the request itself
                    axios({
                        method: 'POST',
                        url: `${window.location.pathname}/upload`,
                        data: formData,
                        cancelToken: source.token,
                        onUploadProgress: (e) => {
                            // updating progress indicator
                            progress(e.lengthComputable, e.loaded, e.total);
                        }
                    }).then(response => {
                        // passing the file id to FilePond
                        console.log(response)
                        load(response.data.data.id)
                    }).catch((thrown) => {
                        if (axios.isCancel(thrown)) {
                            console.log('Request canceled', thrown.message);
                        } else {
                            // handle error
                        }
                    });

                    // Setup abort interface
                    return {
                        abort: () => {
                            source.cancel('Operation canceled by the user.');
                        }
                    }
                }
                // fetch: `${window.location.pathname}/fetch`,
            },
        })

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImageExifOrientation,
            FilePondPluginImagePreview,
            FilePondPluginImageCrop,
            FilePondPluginImageResize,
            FilePondPluginImageTransform
        )

        // Select the file input and use 
        // create() to turn it into a pond
        let inputElement = document.querySelector('#filepond')

        FilePond.create(
            inputElement, {
                labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
                imagePreviewHeight: 170,
                imageCropAspectRatio: '1:1',
                imageResizeTargetWidth: 200,
                imageResizeTargetHeight: 200,
                stylePanelLayout: 'compact circle',
                styleLoadIndicatorPosition: 'center bottom',
                styleProgressIndicatorPosition: 'right bottom',
                styleButtonRemoveItemPosition: 'left bottom',
                styleButtonProcessItemPosition: 'right bottom',
                files: [{
                    source: image,
                }]
            }
        )

        // FilePond.destroy(inputElement);
    })

    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        validationForm(forms)

    }, false);

})();
