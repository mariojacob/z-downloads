/* global zdmFrontend */

(function () {
    'use strict';

    if (typeof window.zdmFrontend === 'undefined') {
        return;
    }

    var config = window.zdmFrontend;

    if (!config || !config.ajaxUrl || !config.trackNonce) {
        return;
    }

    function buildPayload(fileId) {
        var params = new URLSearchParams();
        params.append('action', 'zdm_track_download');
        params.append('file_id', String(fileId));
        params.append('nonce', config.trackNonce);
        return params.toString();
    }

    function sendDownloadSignal(fileId) {
        if (!fileId) {
            return;
        }

        var payload = buildPayload(fileId);

        if (navigator.sendBeacon) {
            var blob = new Blob([payload], { type: 'application/x-www-form-urlencoded; charset=UTF-8' });
            navigator.sendBeacon(config.ajaxUrl, blob);
            return;
        }

        if (typeof fetch === 'function') {
            fetch(config.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: payload,
                keepalive: true
            }).catch(function () {
                // Ignoriert Fehler im Hintergrund
            });
            return;
        }

        try {
            var request = new XMLHttpRequest();
            request.open('POST', config.ajaxUrl, true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.send(payload);
        } catch (error) {
            // Ignoriert Fehler im Hintergrund
        }
    }

    function extractAnchor(element) {
        while (element && element !== document) {
            if (element.nodeName === 'A') {
                return element;
            }
            element = element.parentNode;
        }
        return null;
    }

    function handleClick(event) {
        var anchor = extractAnchor(event.target);

        if (!anchor || !anchor.dataset || anchor.dataset.zdmDirectPdf !== '1') {
            return;
        }

        var fileId = anchor.dataset.zdmFileId;

        if (!fileId) {
            return;
        }

        sendDownloadSignal(fileId);
    }

    document.addEventListener('click', handleClick, true);
})();

