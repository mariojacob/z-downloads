(function (window, document) {
    'use strict';

    if (typeof window.Dropzone === 'undefined') {
        return;
    }

    window.Dropzone.autoDiscover = false;

    var config = window.zdmDropzoneConfig || {};
    var dropzoneElement = document.querySelector('[data-zdm-dropzone]');

    if (!dropzoneElement) {
        return;
    }

    var ajaxUrl = typeof config.ajaxUrl === 'string' ? config.ajaxUrl : dropzoneElement.getAttribute('data-ajax-url');
    var nonce = typeof config.nonce === 'string' ? config.nonce : dropzoneElement.getAttribute('data-nonce');

    if (!ajaxUrl || !nonce) {
        return;
    }

    dropzoneElement.classList.add('has-dropzone-js');

    var messageElement = dropzoneElement.querySelector('[data-zdm-dropzone-feedback]');
    if (!messageElement) {
        messageElement = document.createElement('div');
        messageElement.setAttribute('data-zdm-dropzone-feedback', '');
        dropzoneElement.appendChild(messageElement);
    }

    messageElement.className = 'zdm-dropzone__message';
    messageElement.setAttribute('role', 'status');
    messageElement.setAttribute('aria-live', 'polite');
    messageElement.setAttribute('aria-hidden', 'true');

    var texts = Object.assign({
        defaultMessage: dropzoneElement.getAttribute('data-default-message') || 'Datei hierher ziehen oder klicken',
        uploadInProgress: dropzoneElement.getAttribute('data-upload-message') || 'Upload läuft...',
        success: dropzoneElement.getAttribute('data-success-message') || 'Upload erfolgreich. Weiterleitung...',
        duplicate: dropzoneElement.getAttribute('data-duplicate-message') || 'Diese Datei wurde bereits hochgeladen.',
        validation: dropzoneElement.getAttribute('data-validation-message') || 'Upload nicht möglich.',
        genericError: dropzoneElement.getAttribute('data-error-message') || 'Upload fehlgeschlagen. Bitte erneut versuchen.'
    }, config.texts || {});

    var allowedMimeTypes = [];
    if (Array.isArray(config.allowedMimeTypes)) {
        allowedMimeTypes = config.allowedMimeTypes.slice();
    } else if (typeof config.allowedMimeTypes === 'string') {
        allowedMimeTypes = config.allowedMimeTypes.split(',');
    }

    allowedMimeTypes = allowedMimeTypes
        .map(function (item) {
            return item.trim();
        })
        .filter(function (item) {
            return item.length > 0;
        });

    var maxFileSize = parseFloat(config.maxFileSizeMb);
    if (!isFinite(maxFileSize) || maxFileSize <= 0) {
        maxFileSize = undefined;
    }

    var showMessage = function (type, text) {
        if (!messageElement) {
            return;
        }

        if (!text) {
            messageElement.innerHTML = '';
            messageElement.className = 'zdm-dropzone__message';
            messageElement.setAttribute('aria-hidden', 'true');
            return;
        }

        messageElement.innerHTML = text;
        messageElement.className = 'zdm-dropzone__message is-' + type;
        messageElement.setAttribute('aria-hidden', 'false');
    };

    var parsePayload = function (payload) {
        var data = payload;

        if (typeof payload === 'string') {
            try {
                data = JSON.parse(payload);
            } catch (error) {
                data = {};
            }
        }

        return data || {};
    };

    var dropzoneOptions = {
        url: ajaxUrl,
        paramName: 'file',
        maxFiles: 1,
        parallelUploads: 1,
        uploadMultiple: false,
        clickable: dropzoneElement,
        timeout: config.timeout && config.timeout > 0 ? config.timeout : 0,
        dictDefaultMessage: texts.defaultMessage,
        acceptedFiles: allowedMimeTypes.length ? allowedMimeTypes.join(',') : null
    };

    if (typeof maxFileSize !== 'undefined') {
        dropzoneOptions.maxFilesize = maxFileSize;
    }

    var previewsContainer = dropzoneElement.querySelector('[data-zdm-dropzone-previews]');
    if (previewsContainer) {
        dropzoneOptions.previewsContainer = previewsContainer;
    }

    var dz = new window.Dropzone(dropzoneElement, dropzoneOptions);

    var dragCounter = 0;

    dropzoneElement.addEventListener('dragenter', function (event) {
        event.preventDefault();
        dragCounter += 1;
        dropzoneElement.classList.add('is-dragover');
    });

    dropzoneElement.addEventListener('dragover', function (event) {
        event.preventDefault();
        dropzoneElement.classList.add('is-dragover');
    });

    dropzoneElement.addEventListener('dragleave', function (event) {
        event.preventDefault();

        dragCounter -= 1;
        if (dragCounter <= 0) {
            dropzoneElement.classList.remove('is-dragover');
            dragCounter = 0;
        }
    });

    dropzoneElement.addEventListener('drop', function () {
        dragCounter = 0;
        dropzoneElement.classList.remove('is-dragover');
    });

    dz.on('addedfile', function (file) {
        if (dz.files.length > 1) {
            dz.removeFile(dz.files[0]);
        }
        showMessage('info', '');
        dropzoneElement.classList.remove('is-dragover');
    });

    dz.on('sending', function (file, xhr, formData) {
        dropzoneElement.classList.add('is-uploading');
        showMessage('info', texts.uploadInProgress);
        formData.append('action', 'zdm_upload_file');
        formData.append('nonce', nonce);
    });

    dz.on('success', function (file, response) {
        dropzoneElement.classList.remove('is-uploading');

        var payload = parsePayload(response);

        if (payload.success && payload.data) {
            showMessage('success', texts.success);

            var redirectUrl = payload.data.redirect_url || payload.data.redirectUrl;
            if (redirectUrl) {
                window.setTimeout(function () {
                    window.location.href = redirectUrl;
                }, 800);
            }
            return;
        }

        var message = texts.genericError;
        if (payload.data && payload.data.message) {
            message = payload.data.message;
        } else if (payload.message) {
            message = payload.message;
        }

        showMessage('error', message);
        dz.removeFile(file);
    });

    dz.on('error', function (file, errorMessage, xhr) {
        dropzoneElement.classList.remove('is-uploading');

        var message = texts.genericError;
        var code = '';

        if (xhr && typeof xhr.responseText === 'string') {
            var payload = parsePayload(xhr.responseText);
            if (payload.data) {
                if (payload.data.code) {
                    code = payload.data.code;
                }
                if (payload.data.message) {
                    message = payload.data.message;
                }
            }
        } else if (typeof errorMessage === 'string') {
            message = errorMessage;
        } else if (errorMessage && errorMessage.message) {
            message = errorMessage.message;
        }

        if (code === 'duplicate') {
            message = texts.duplicate;
        } else if (code === 'validation') {
            message = message || texts.validation;
        }

        showMessage('error', message || texts.genericError);

        if (file) {
            dz.removeFile(file);
        }
    });

    var resetState = function () {
        dropzoneElement.classList.remove('is-uploading');
    };

    dz.on('complete', resetState);
    dz.on('queuecomplete', resetState);

    dz.on('maxfilesexceeded', function (file) {
        if (dz.files.length > 0) {
            dz.removeFile(dz.files[0]);
        }
        dz.addFile(file);
    });

    var triggerButton = document.querySelector('[data-zdm-dropzone-button]');
    if (triggerButton) {
        triggerButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            if (dz.hiddenFileInput) {
                dz.hiddenFileInput.click();
            }
        });
    }
})(window, document);

