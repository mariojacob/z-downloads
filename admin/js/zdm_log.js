(function (window, document) {
    'use strict';

    var app = document.querySelector('.zdm-log-wrap[data-zdm-log-ajax]');
    if (!app) {
        return;
    }

    var globalConfig = window.zdmLogConfig || {};
    var strings = Object.assign({
        loading: 'Loading logs…',
        errorGeneric: 'Logs could not be loaded. Please try again.',
        errorPermissions: 'You do not have sufficient permissions to load logs.',
        summary: 'Showing {from}–{to} of {total} entries',
        summaryZero: 'No entries found for the current filters',
        pagePrev: 'Previous page',
        pageNext: 'Next page',
        pageNumber: 'Page {page}',
        detailsTitle: 'Log entry #{id}',
        filterReset: 'Filters have been reset.',
        empty: 'No log entries match the current filters.',
        detailNotFound: 'The requested log entry could not be found.',
        detailRequestError: 'The log entry could not be loaded. Please try again.'
    }, globalConfig.strings || {});

    var ajaxUrl = app.getAttribute('data-zdm-log-ajax');
    var nonce = app.getAttribute('data-zdm-log-nonce');
    var perPageDefault = parseInt(app.getAttribute('data-zdm-log-per-page'), 10);
    if (!isFinite(perPageDefault) || perPageDefault <= 0) {
        perPageDefault = 50;
    }

    var typeConfig = {};
    try {
        typeConfig = JSON.parse(app.getAttribute('data-zdm-log-types') || '{}') || {};
    } catch (error) {
        typeConfig = {};
    }

    var defaultTypeConfig = typeConfig.default || { label: '', icon: 'info', color: '' };

    var initialState = {};
    try {
        initialState = JSON.parse(app.getAttribute('data-zdm-log-initial') || '{}') || {};
    } catch (error) {
        initialState = {};
    }

    var detailUrlBase = app.getAttribute('data-zdm-log-detail-base') || '?page=z-downloads-log&id=';

    var elements = {
        search: app.querySelector('[data-zdm-log-input="search"]'),
        types: app.querySelector('[data-zdm-log-input="types"]'),
        dateFrom: app.querySelector('[data-zdm-log-input="date_from"]'),
        dateTo: app.querySelector('[data-zdm-log-input="date_to"]'),
        perPage: app.querySelector('[data-zdm-log-input="per_page"]'),
        reset: app.querySelector('[data-zdm-log-reset]'),
        summary: app.querySelector('[data-zdm-log-summary]'),
        tableBody: app.querySelector('[data-zdm-log-body]'),
        emptyState: app.querySelector('[data-zdm-log-empty]'),
        pagination: app.querySelector('[data-zdm-log-pagination]'),
        modal: app.querySelector('[data-zdm-log-modal]'),
        modalCloseButtons: app.querySelectorAll('[data-zdm-log-modal-close]'),
        modalTitle: app.querySelector('#zdm-log-modal-title'),
        modalType: app.querySelector('[data-zdm-log-modal-type]'),
        modalIp: app.querySelector('[data-zdm-log-modal-ip]'),
        modalAgent: app.querySelector('[data-zdm-log-modal-agent]'),
        modalCreated: app.querySelector('[data-zdm-log-modal-created]'),
        modalMessage: app.querySelector('[data-zdm-log-modal-message]'),
        rowTemplate: document.getElementById('zdm-log-row-template')
    };

    if (!elements.tableBody || !elements.rowTemplate) {
        return;
    }

    var state = {
        page: 1,
        perPage: perPageDefault,
        types: [],
        dateFrom: '',
        dateTo: '',
        search: '',
        total: 0,
        pages: 1
    };

    state = Object.assign(state, initialState || {});

    parseUrlState();
    syncControlsWithState();

    // no modal/deeplink handling; details open via anchors with page reload

    var currentLogs = {};
    var isLoading = false;
    // removed modal/deeplink functions
    var lastFocusedElement = null;

    function format(template, replacements) {
        return template.replace(/\{(\w+)\}/g, function (_, key) {
            if (replacements && Object.prototype.hasOwnProperty.call(replacements, key)) {
                return replacements[key];
            }
            return '';
        });
    }

    function debounce(fn, wait) {
        var timeout = null;
        return function () {
            var context = this;
            var args = arguments;
            window.clearTimeout(timeout);
            timeout = window.setTimeout(function () {
                fn.apply(context, args);
            }, wait);
        };
    }

    function parseUrlState() {
        var url;

        try {
            url = new URL(window.location.href);
        } catch (error) {
            return;
        }

        var params = url.searchParams;

        if (params.has('zdm_log_search')) {
            state.search = params.get('zdm_log_search') || '';
        }

        if (params.has('zdm_log_types')) {
            var typeParam = params.get('zdm_log_types') || '';
            state.types = typeParam.split(',').map(function (item) {
                return item.trim();
            }).filter(function (item) {
                return item.length > 0;
            });
        }

        if (params.has('zdm_log_from')) {
            state.dateFrom = params.get('zdm_log_from') || '';
        }

        if (params.has('zdm_log_to')) {
            state.dateTo = params.get('zdm_log_to') || '';
        }

        if (params.has('zdm_log_page')) {
            var parsedPage = parseInt(params.get('zdm_log_page'), 10);
            if (isFinite(parsedPage) && parsedPage > 0) {
                state.page = parsedPage;
            }
        }

        if (params.has('zdm_log_per_page')) {
            var parsedPerPage = parseInt(params.get('zdm_log_per_page'), 10);
            if (isFinite(parsedPerPage) && parsedPerPage > 0) {
                state.perPage = parsedPerPage;
            }
        }
    }

    function syncUrlState() {
        var url;

        try {
            url = new URL(window.location.href);
        } catch (error) {
            return;
        }

        var params = url.searchParams;

        if (state.search) {
            params.set('zdm_log_search', state.search);
        } else {
            params.delete('zdm_log_search');
        }

        if (state.types && state.types.length) {
            params.set('zdm_log_types', state.types.join(','));
        } else {
            params.delete('zdm_log_types');
        }

        if (state.dateFrom) {
            params.set('zdm_log_from', state.dateFrom);
        } else {
            params.delete('zdm_log_from');
        }

        if (state.dateTo) {
            params.set('zdm_log_to', state.dateTo);
        } else {
            params.delete('zdm_log_to');
        }

        if (state.page > 1) {
            params.set('zdm_log_page', state.page);
        } else {
            params.delete('zdm_log_page');
        }

        if (state.perPage !== perPageDefault) {
            params.set('zdm_log_per_page', state.perPage);
        } else {
            params.delete('zdm_log_per_page');
        }

        url.search = params.toString();
        window.history.replaceState({}, '', url.toString());
    }

    function syncControlsWithState() {
        if (elements.search) {
            elements.search.value = state.search || '';
        }

        if (elements.types) {
            var options = elements.types.options;
            for (var i = 0; i < options.length; i += 1) {
                options[i].selected = state.types.indexOf(options[i].value) !== -1;
            }
        }

        if (elements.dateFrom) {
            elements.dateFrom.value = state.dateFrom || '';
        }

        if (elements.dateTo) {
            elements.dateTo.value = state.dateTo || '';
        }

        if (elements.perPage) {
            elements.perPage.value = state.perPage;
        }
    }

    function setLoading(active) {
        isLoading = !!active;
        if (active) {
            app.classList.add('is-loading');
            if (elements.summary) {
                elements.summary.textContent = strings.loading;
            }
        } else {
            app.classList.remove('is-loading');
        }
    }

    function formatSummary() {
        if (!elements.summary) {
            return;
        }

        if (!state.total) {
            elements.summary.textContent = strings.summaryZero;
            return;
        }

        var from = ((state.page - 1) * state.perPage) + 1;
        var to = Math.min(state.page * state.perPage, state.total);

        elements.summary.textContent = format(strings.summary, {
            from: from,
            to: to,
            total: state.total
        });
    }

    function clearTable() {
        if (!elements.tableBody) {
            return;
        }

        while (elements.tableBody.firstChild) {
            elements.tableBody.removeChild(elements.tableBody.firstChild);
        }
    }

    function renderEmptyRow(message) {
        if (!elements.tableBody) {
            return;
        }

        var row = document.createElement('tr');
        row.className = 'zdm-log-row is-empty';

        var cell = document.createElement('td');
        cell.colSpan = 4;
        cell.textContent = message || strings.empty;

        row.appendChild(cell);
        elements.tableBody.appendChild(row);
    }

    function applyIconStyles(iconElement, colorClass) {
        if (!iconElement) {
            return;
        }

        var baseClass = 'material-icons-round zdm-log-row__icon';
        if (colorClass && colorClass.length > 0) {
            iconElement.className = baseClass + ' ' + colorClass;
        } else {
            iconElement.className = baseClass;
        }
    }

    function mapLogType(type) {
        if (type && Object.prototype.hasOwnProperty.call(typeConfig, type)) {
            return Object.assign({}, defaultTypeConfig, typeConfig[type]);
        }

        return defaultTypeConfig;
    }

    function renderLogs(logs) {
        clearTable();
        currentLogs = {};

        if (!logs || !logs.length) {
            renderEmptyRow(strings.empty);
            if (elements.emptyState) {
                elements.emptyState.hidden = false;
            }
            formatSummary();
            renderPagination();
            return;
        }

        if (elements.emptyState) {
            elements.emptyState.hidden = true;
        }

        logs.forEach(function (log) {
            var templateContent = elements.rowTemplate.content ? elements.rowTemplate.content : null;
            var row = templateContent ? templateContent.firstElementChild.cloneNode(true) : null;

            if (!row) {
                row = document.createElement('tr');
                row.className = 'zdm-log-row';
            }

            row.setAttribute('data-log-id', log.id);
            row.setAttribute('data-action', 'details');
            row.setAttribute('tabindex', '0');
            row.setAttribute('role', 'button');

            var typeInfo = mapLogType(log.type);
            var iconElement = row.querySelector('.zdm-log-row__icon');
            var typeElement = row.querySelector('.zdm-log-row__type');
            var messageElement = row.querySelector('.zdm-log-row__message');
            var ipElement = row.querySelector('.zdm-log-row__ip');
            var agentElement = row.querySelector('.zdm-log-row__agent');
            var createdElement = row.querySelector('.zdm-log-row__created');
            var detailsLink = row.querySelector('.zdm-log-row__details');

            if (iconElement) {
                iconElement.textContent = typeInfo.icon || defaultTypeConfig.icon;
                applyIconStyles(iconElement, typeInfo.color);
            }

            if (typeElement) {
                typeElement.textContent = (log.type_label || typeInfo.label || log.type || '').toString();
                typeElement.setAttribute('href', detailUrlBase + encodeURIComponent(String(log.id)));
            }

            if (messageElement) {
                messageElement.innerHTML = log.message || '';
            }

            if (ipElement) {
                ipElement.textContent = log.user_ip || '—';
            }

            if (agentElement) {
                agentElement.textContent = log.user_agent || '';
            }

            if (createdElement) {
                createdElement.textContent = log.time_formatted || '';
            }

            if (detailsLink) {
                detailsLink.setAttribute('href', detailUrlBase + encodeURIComponent(String(log.id)));
            }

            currentLogs[log.id] = log;
            elements.tableBody.appendChild(row);
        });

        formatSummary();
        renderPagination();
    }

    function renderPagination() {
        if (!elements.pagination) {
            return;
        }

        elements.pagination.innerHTML = '';

        if (!state.pages || state.pages <= 1) {
            elements.pagination.hidden = true;
            return;
        }

        elements.pagination.hidden = false;

        var addButton = function (label, targetPage, disabled, title, isCurrent) {
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'button';
            button.textContent = label;
            button.disabled = !!disabled;
            button.setAttribute('data-page', targetPage);
            button.setAttribute('aria-label', title);

            if (isCurrent) {
                button.classList.add('button-primary', 'current');
                button.setAttribute('aria-current', 'page');
            }

            elements.pagination.appendChild(button);
        };

        var prevPage = Math.max(1, state.page - 1);
        addButton('‹', prevPage, state.page === 1, strings.pagePrev, false);

        var range = 2;
        var start = Math.max(1, state.page - range);
        var end = Math.min(state.pages, state.page + range);

        if (end - start < range * 2) {
            var missing = (range * 2) - (end - start);
            start = Math.max(1, start - missing);
            end = Math.min(state.pages, start + range * 2);
        }

        for (var i = start; i <= end; i += 1) {
            addButton(String(i), i, false, format(strings.pageNumber, { page: i }), i === state.page);
        }

        var nextPage = Math.min(state.pages, state.page + 1);
        addButton('›', nextPage, state.page === state.pages, strings.pageNext, false);
    }

    function buildRequestBody() {
        var formData = new window.FormData();
        formData.append('action', 'zdm_load_logs');
        formData.append('nonce', nonce);
        formData.append('page', state.page);
        formData.append('per_page', state.perPage);

        if (state.types && state.types.length) {
            state.types.forEach(function (typeValue) {
                formData.append('types[]', typeValue);
            });
        }

        if (state.search) {
            formData.append('search', state.search);
        }

        if (state.dateFrom) {
            formData.append('date_from', state.dateFrom);
        }

        if (state.dateTo) {
            formData.append('date_to', state.dateTo);
        }

        return formData;
    }

    function handleError(message) {
        clearTable();
        renderEmptyRow(message || strings.errorGeneric);
        if (elements.emptyState) {
            elements.emptyState.hidden = false;
        }
        if (elements.summary) {
            elements.summary.textContent = message || strings.errorGeneric;
        }
    }

    function fetchLogs() {
        if (!ajaxUrl || !nonce) {
            handleError(strings.errorGeneric);
            return;
        }

        setLoading(true);
        syncUrlState();

        window.fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: buildRequestBody()
        }).then(function (response) {
            if (!response.ok) {
                if (response.status === 403) {
                    throw new Error(strings.errorPermissions);
                }
                throw new Error(strings.errorGeneric);
            }
            return response.json();
        }).then(function (payload) {
            if (!payload || !payload.success || !payload.data) {
                throw new Error(strings.errorGeneric);
            }

            var data = payload.data;
            state.total = parseInt(data.total, 10) || 0;
            state.page = parseInt(data.page, 10) || 1;
            state.pages = parseInt(data.pages, 10) || 1;
            state.perPage = parseInt(data.per_page, 10) || state.perPage;

            renderLogs(data.logs || []);
        }).catch(function (error) {
            handleError(error && error.message ? error.message : strings.errorGeneric);
        }).finally(function () {
            setLoading(false);
        });
    }

    var debouncedFetch = debounce(function () {
        state.page = 1;
        fetchLogs();
    }, 300);

    if (elements.search) {
        elements.search.addEventListener('input', function (event) {
            state.search = event.target.value.trim();
            debouncedFetch();
        });
    }

    if (elements.types) {
        elements.types.addEventListener('change', function (event) {
            var selected = Array.prototype.slice.call(event.target.options).filter(function (option) {
                return option.selected;
            }).map(function (option) {
                return option.value;
            });

            state.types = selected;
            state.page = 1;
            fetchLogs();
        });
    }

    var dateChangeHandler = function () {
        state.dateFrom = elements.dateFrom ? elements.dateFrom.value : '';
        state.dateTo = elements.dateTo ? elements.dateTo.value : '';
        state.page = 1;
        fetchLogs();
    };

    if (elements.dateFrom) {
        elements.dateFrom.addEventListener('change', dateChangeHandler);
    }

    if (elements.dateTo) {
        elements.dateTo.addEventListener('change', dateChangeHandler);
    }

    if (elements.perPage) {
        elements.perPage.addEventListener('change', function (event) {
            var value = parseInt(event.target.value, 10);
            if (!isFinite(value) || value <= 0) {
                value = perPageDefault;
            }
            state.perPage = value;
            state.page = 1;
            fetchLogs();
        });
    }

    if (elements.reset) {
        elements.reset.addEventListener('click', function () {
            state.page = 1;
            state.perPage = perPageDefault;
            state.types = [];
            state.dateFrom = '';
            state.dateTo = '';
            state.search = '';

            syncControlsWithState();
            fetchLogs();
        });
    }

    if (elements.pagination) {
        elements.pagination.addEventListener('click', function (event) {
        if (event.target && event.target.matches('button[data-page]')) {
                var target = parseInt(event.target.getAttribute('data-page'), 10);
                if (isFinite(target) && target >= 1 && target <= state.pages && target !== state.page) {
                    state.page = target;
                    fetchLogs();
                }
            }
        });
    }

    // no modal click handlers – anchors handle navigation

    // removed modal code entirely

    fetchLogs();
})(window, document);

