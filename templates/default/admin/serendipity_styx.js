/* Copyright (c) 2026, Ian Styx, All rights reserved. */
/**
 * This is a main and only backend Javascript file.
 *
 * Modern Styx Plugin Replacement Kit (Vanilla ES6+)
 * Replaces: jquery.tabs, js-cookie, jquery.syncheight, canvas-toBlob, jquery.sortable, etc
 */

/* ==========================================================
 * Styx Modal Engine (Native HTML5 <dialog> Replacement for MFP)
 * Supports: Touch Gestures (Swipe-down to close), MFP Selectors & API
 * ========================================================== */
class StyxModal {
    constructor() {
        this.dialogStack = [];
    }

    open(options = {}) {
        if (!document.body) return;

        // If this call comes from an iframe, execute it in the main window:
        if (window !== window.top && window.top.StyxModalInstance) {
            return window.top.StyxModalInstance.open(options);
        }

        const dialogId = 'styx_modal_' + (this.dialogStack.length + 1);

        const dialog = document.createElement('dialog');
        dialog.id = dialogId;

        // Determine if this is a nested / child modal
        const isChild = this.dialogStack.length > 0;

        // Combine new styx classes with legacy options.mainClass (e.g. smp-with-zoom, smp-img-mobile)
        dialog.className = `styx-modal ${isChild ? 'styx-modal-child' : ''} ${options.mainClass || ''}`.trim();

        // Structure compatible with legacy smp-* classes and theme CSS
        dialog.innerHTML = `
            <div class="styx-modal-container smp-container">
                <div class="styx-modal-content smp-content">
                    <button type="button" class="styx-modal-close smp-close" aria-label="Close">&times;</button>
                    <div class="styx-modal-body"></div>
                </div>
            </div>
        `;

        document.body.appendChild(dialog);

        const body = dialog.querySelector('.styx-modal-body');

        // Content Embedding
        if (options.html) {
            body.innerHTML = options.html;
        } else if (options.img || options.src) {
            const imgSrc = options.img || options.src;
            body.innerHTML = `<div style="text-align:center;"><img src="${imgSrc}" style="max-width:100%; max-height:80vh; height:auto; border-radius:4px;"></div>`;
        } else if (options.items && options.items.src) {
            body.innerHTML = `<iframe src="${options.items.src}" style="width:100%; height:75vh; border:none; display:block;"></iframe>`;
        }

        // Listen on close to later on unlock noScroll behaviour
        dialog.addEventListener('close', () => {
            this.close(dialog);
        });

        // Backdrop & Close Click Handling
        dialog.addEventListener('click', (e) => {
            const rect = dialog.getBoundingClientRect();
            const isInDialog = (rect.top <= e.clientY && e.clientY <= rect.top + rect.height &&
                                 rect.left <= e.clientX && e.clientX <= rect.left + rect.width);

            // Close if backdrop clicked or close button clicked
            if (!isInDialog || e.target.classList.contains('styx-modal-close') || e.target.classList.contains('smp-close')) {
                this.close(dialog);
            }
        });

        // Open the top layer natively in the browser
        dialog.showModal();
        this.dialogStack.push(dialog);

        // Prevents background scrolling on touch devices
        document.body.style.overflow = 'hidden';
    }

    /**
     * Closes a specified modal dialog and cleans up the stack and scrolling state.
     *
     * @param {HTMLDialogElement} [targetDialog] - Optional target dialog to close. Defaults to top-most dialog.
     */
    close(targetDialog) {
        const dialog = targetDialog || this.dialogStack[this.dialogStack.length - 1];
        if (!dialog) {
            // Safety check: If stack is empty or target missing, ensure scroll lock is released if no modal exists in DOM
            if (!document.querySelector('dialog[open], .styx-modal-active')) {
                this.dialogStack = [];
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            }
            return;
        }

        // Close native modal
        if (typeof dialog.close === 'function' && dialog.open) {
            dialog.close();
        }

        // Remove from DOM
        dialog.remove();

        // CRITICAL FIX: Remove from stack to prevent persistent 'styx-modal-child' shrinking on subsequent opens!
        const index = this.dialogStack.indexOf(dialog);
        if (index !== -1) {
            this.dialogStack.splice(index, 1);
        }

        // Restore body scrolling if no modals remain in stack OR in DOM
        const remainingModals = document.querySelectorAll('dialog[open], .styx-modal-active');
        if (this.dialogStack.length === 0 || remainingModals.length === 0) {
            this.dialogStack = []; // Force-reset stack in case of out-of-sync removals
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }
    }
}

// Create a global instance
window.StyxModalInstance = new StyxModal();

/* ==========================================================
 * MFP Compatibility Bridge for Plugins & Scripts
 * ========================================================== */
$.magnificPopup = {
    instance: {
        open: (opts) => window.StyxModalInstance.open(opts),
        close: () => serendipity.closeMediaModal()
    },
    open: (opts) => window.StyxModalInstance.open(opts),
    close: () => serendipity.closeMediaModal()
};
window.$.magnificPopup = $.magnificPopup;


/**
 * StyxCookie Management Engine (Modal & Multi-Prefix Resilient)
 * Handles reading, writing, and deleting cookies while transparently managing
 * Serendipity backend/frontend cookie prefixes and modal path alignment.
 */
window.StyxCookie = {
    /**
     * Resolves the active global serendipity context object across main windows,
     * iframes, and Styx Modal dialogs.
     *
     * @function _getContext
     * @memberof StyxCookie
     * @private
     * @returns {Object|null}
     */
    _getContext() {
        if (typeof serendipity !== 'undefined') {
            return serendipity;
        }
        if (window.parent && typeof window.parent.serendipity !== 'undefined') {
            return window.parent.serendipity;
        }
        if (window.top && typeof window.top.serendipity !== 'undefined') {
            return window.top.serendipity;
        }
        return null;
    },

    /**
     * Resolves the active cookie prefix (handles static 'serendipity'
     * and dynamic 's9y_hash' prefixes across main windows and modal frames).
     *
     * @function prefix
     * @memberof StyxCookie
     * @returns {string} The active cookie prefix.
     * @private
     */
    get prefix() {
        const globalCtx = this._getContext();
        return (globalCtx && globalCtx.cookie_prefix) ? globalCtx.cookie_prefix : 'serendipity';
    },

    /**
     * Helper to determine exact HTTP root path matching Serendipity installation.
     * Always guarantees a trailing slash for RFC 6265 & PHP cookie path compatibility.
     *
     * @function _getAppPath
     * @memberof StyxCookie
     * @returns {string} Clean base path matching installation root (e.g. '/' or '/path/to/blog/').
     * @private
     */
    _getAppPath() {
        const globalCtx = this._getContext();

        // 1. Primary path from StyxConfig / global context
        let appPath = (typeof StyxConfig !== 'undefined' && StyxConfig.get('serendipityHTTPPath'))
            ? StyxConfig.get('serendipityHTTPPath')
            : (globalCtx?.serendipityHTTPPath || globalCtx?.rewrite?.httpPath);

        // 2. Fallback if not present in context/config
        if (!appPath) {
            const currentPath = window.location.pathname;
            const adminIndex = currentPath.indexOf('serendipity_admin');
            appPath = adminIndex !== -1 ? currentPath.substring(0, adminIndex) : '/';
        }

        // 3. Ensure trailing slash IS present for valid cookie directory scoping
        if (!appPath.endsWith('/')) {
            appPath += '/';
        }

        return appPath;
    },

    /**
     * Internal helper: Purges ghost copies of a specific cookie on the slash-less path variant.
     * Prevents HTTP 431 header bloat without affecting sibling paths or other blogs.
     *
     * @function _sanitizeSlashlessGhost
     * @memberof StyxCookie
     * @private
     */
    _sanitizeSlashlessGhost(rawName, validPath, domain) {
        if (validPath.length > 1 && validPath.endsWith('/')) {
            const noSlashPath = validPath.slice(0, -1);
            let killCookie = `${rawName}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=${noSlashPath}; SameSite=Lax`;
            if (domain) killCookie += `; domain=${domain}`;
            document.cookie = killCookie;
        }
    },

    /**
     * Sets a cookie with automatic path detection matching the Serendipity installation root.
     * Ensures settings saved within Modals persist globally across the backend.
     *
     * @function set
     * @memberof StyxCookie
     * @param {string} name - Cookie identifier name.
     * @param {string|number|boolean|null} value - Cookie value payload.
     * @param {object} [options={}] - Cookie configuration options.
     * @param {boolean} [raw=false] - If true, bypasses automatic prefix formatting.
     */
    set(name, value, options = {}, raw = false) {

        // 1. HEADER 431 PREVENTION: If value is null/undefined, remove cookie
        if (value === null || value === undefined) {
            this.remove(name, options, raw);
            return;
        }

        const rawName = raw ? name : `${this.prefix}[${name}]`;
        const defaultPath = this._getAppPath();
        const opts = Object.assign({ path: defaultPath, expires: 365, sameSite: 'Lax' }, options);

        // 2. Kill potential ghost copy on serendipityHTTPPath: /path/to/blog (without trailing slash)
        this._sanitizeSlashlessGhost(rawName, opts.path, opts.domain);

        // 3. If namespaced, also ensure un-prefixed raw cookie on non-slash path is dead
        if (!raw) {
            this._sanitizeSlashlessGhost(name, opts.path, opts.domain);
        }

        let cookieString = `${rawName}=${encodeURIComponent(value)}`;

        if (opts.expires) {
            const d = new Date();
            d.setTime(d.getTime() + (opts.expires * 24 * 60 * 60 * 1000));
            cookieString += `; expires=${d.toUTCString()}`;
        }

        cookieString += `; path=${opts.path}`;

        if (opts.domain) cookieString += `; domain=${opts.domain}`;
        if (opts.secure) cookieString += `; secure`;
        if (opts.sameSite) cookieString += `; samesite=${opts.sameSite}`;

        document.cookie = cookieString;
    },

    /**
     * Reads a cookie by name with smart fallbacks for both 'serendipity[x]'
     * and dynamic 's9y_hash[x]' prefixes.
     *
     * @function get
     * @memberof StyxCookie
     * @param {string} name - Cookie identifier name.
     * @param {boolean} [raw=false] - If true, searches for the exact un-prefixed cookie name.
     * @returns {string|undefined} The decoded cookie value, or undefined if not found.
     */
    get(name, raw = false) {
        const cookies = document.cookie ? document.cookie.split('; ') : [];

        if (raw) {
            for (let i = 0; i < cookies.length; i++) {
                const parts = cookies[i].split('=');
                if (decodeURIComponent(parts[0]) === name) {
                    return decodeURIComponent(parts.slice(1).join('='));
                }
            }
            return undefined;
        }

        // Search patterns in priority order:
        // 1. Active Prefix (e.g. serendipity[img_align] or s9y_hash[img_align])
        const targetPrefixes = [
            `${this.prefix}[${name}]`,
            `serendipity[${name}]`
        ];

        // 2. Standard 'serendipity[img_align]' fallback
        for (let p = 0; p < targetPrefixes.length; p++) {
            const target = targetPrefixes[p];
            for (let i = 0; i < cookies.length; i++) {
                const parts = cookies[i].split('=');
                if (decodeURIComponent(parts[0]) === target) {
                    return decodeURIComponent(parts.slice(1).join('='));
                }
            }
        }

        // Dynamic s9y_ Prefix fallback (search for any s9y_*[name] pattern)
        for (let i = 0; i < cookies.length; i++) {
            const parts = cookies[i].split('=');
            const key = decodeURIComponent(parts[0]);
            if (key.startsWith('s9y_') && key.endsWith(`[${name}]`)) {
                return decodeURIComponent(parts.slice(1).join('='));
            }
        }

        // Raw fallback
        for (let i = 0; i < cookies.length; i++) {
            const parts = cookies[i].split('=');
            if (decodeURIComponent(parts[0]) === name) {
                return decodeURIComponent(parts.slice(1).join('='));
            }
        }

        return undefined;
    },

    /**
     * Sets a cookie only if it does not exist yet or if its value has changed.
     * Prevents unnecessary write operations to `document.cookie` and reduces I/O overhead.
     *
     * @function setIfChanged
     * @memberof StyxCookie
     * @param {string} name - The unique name of the cookie (key).
     * @param {string|number|boolean} value - The value to store. Converted to a string for comparison.
     * @param {number} [days] - Optional expiration lifetime in days.
     * @returns {boolean} `true` if the cookie was set or updated; `false` if the value was identical.
     *
     * @example
     * // Only writes to document.cookie if 'only_path' is not already 'images/':
     * StyxCookie.setIfChanged('only_path', 'images/', 30);
     */
    setIfChanged: function (name, value, days) {
        var currentValue = this.get(name);
        var newValue = String(value);

        // Only set if cookie doesn't exist or value has changed
        if (currentValue !== newValue) {
            this.set(name, value, days);
            return true;
        }
        return false;
    },

    /**
     * Deletes a cookie by expiring it immediately across both valid and slash-less ghost paths.
     *
     * @function remove
     * @memberof StyxCookie
     * @param {string} name - Cookie identifier name.
     * @param {object} [options={}] - Cookie configuration options.
     * @param {boolean} [raw=false] - If true, bypasses automatic prefix formatting.
     */
    remove(name, options = {}, raw = false) {
        const rawName = raw ? name : `${this.prefix}[${name}]`;
        const targetPath = options.path || this._getAppPath() || '/';

        // Kill ghost copy on /path/to/blog
        this._sanitizeSlashlessGhost(rawName, targetPath, options.domain);

        // Remove official cookie on /path/to/blog/
        let cookieString = `${rawName}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=${targetPath}; SameSite=Lax`;
        if (options.domain) {
            cookieString += `; domain=${options.domain}`;
        }

        // Fix: Execute actual browser cookie deletion!
        document.cookie = cookieString;
    },

    /**
     * Alias for remove() to support standard JavaScript Web API syntax.
     */
    delete(name, options = {}, raw = false) {
        this.remove(name, options, raw);
    },

    /**
     * Purges a legacy or namespaced Serendipity cookie.
     * Maps global cookie keys to the StyxCookie removal logic.
     *
     * @function PurgeCookie
     * @memberof StyxCookie
     * @param {string} name - The cookie key name (e.g., 'align' or '[align]').
     */
    PurgeCookie(name) {
        if (typeof StyxCookie !== 'undefined') {
            const formattedName = (name.indexOf('[') !== -1) ? 'serendipity' + name : 'serendipity[' + name + ']';
            StyxCookie.remove(formattedName, {}, true);
        }
    },

    /**
     * Routine maintenance method to purge historical empty or malformed filter cookies
     * that cause HTTP 431 Request Header Fields Too Large errors.
     *
     * @function setIfChanged
     * @memberof StyxCookie
     */
    purgeFilterGarbage() {
        const cookies = document.cookie ? document.cookie.split('; ') : [];
        cookies.forEach(c => {
            const parts = c.split('=');
            const key = decodeURIComponent(parts[0].trim());
            const val = parts[1] ? decodeURIComponent(parts[1].trim()) : '';

            if (key.includes('[filter]') || key.includes('[]') || val === '' || val === '""') {
                this.remove(key, {}, true);
            }
        });
    }
};


/**
 * Accessible Tabs component (Vanilla JS) adhering to WAI-ARIA Authoring Practices.
 * Converts heading/panel pairs into an accessible tablist with keyboard navigation.
 *
 * @class StyxTabs
 * @param {HTMLElement|string} container - Element or CSS selector for the tab container.
 * @param {Object} [options] - Configuration options.
 * @param {string} [options.wrapperClass='content'] - Wrapper class name.
 * @param {string} [options.tabhead='h3'] - CSS selector for heading elements serving as tab titles.
 * @param {string} [options.tabbody='.panel'] - CSS selector for tab content panel elements.
 * @param {boolean} [options.saveState=true] - Whether to persist the active tab state in a cookie.
 * @param {string} [options.storageKey] - Custom cookie storage key. Defaults to `styx_tab_${container.id || 'default'}`.
 *
 * @example
 * // Basic initialization
 * new StyxTabs('#my-tab-container');
 *
 * @example
 * // Custom selectors
 * new StyxTabs('.settings-wrapper', {
 *     tabhead: 'h4',
 *     tabbody: '.tab-content',
 *     saveState: false
 * });
 */
class StyxTabs {
    constructor(container, options = {}) {
        this.container = typeof container === 'string' ? document.querySelector(container) : container;
        if (!this.container) return;

        // If instance already exists on container: reuse or return early
        if (this.container._styxTabs) {
            return this.container._styxTabs;
        }

        this.options = Object.assign({
            wrapperClass: 'content',
            tabhead: 'h3',
            tabbody: '.panel',
            saveState: true,
            storageKey: `styx_tab_${this.container.id || 'default'}`
        }, options);

        this.container._styxTabs = this;
        this.init();
    }

    /**
     * Initializes the tab navigation, builds DOM structure, attaches event handlers,
     * and restores saved state if available.
     *
     * @private
     * @returns {void}
     */
    init() {
        // 1. Remove existing navigation bars inside the container
        const existingNavs = this.container.querySelectorAll('.tabs-list');
        existingNavs.forEach(nav => nav.remove());

        // 2. Query tab headers and panels
        const heads = this.container.querySelectorAll(this.options.tabhead);
        const allPanels = this.container.querySelectorAll(this.options.tabbody);
        const bodies = Array.from(allPanels).slice(0, heads.length);

        if (!heads.length || !bodies.length) return;

        // 3. Construct tab navigation
        const nav = document.createElement('ul');
        nav.className = 'tabs-list clearfix';
        nav.setAttribute('role', 'tablist');

        heads.forEach((head, idx) => {
            head.style.display = 'none'; // Hide original H3 header

            const tabId = `tab-link-${idx}`;
            const panelId = `tab-panel-${idx}`;

            const li = document.createElement('li');
            li.setAttribute('role', 'presentation');

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.id = tabId;
            btn.setAttribute('role', 'tab');
            btn.setAttribute('aria-controls', panelId);
            btn.setAttribute('aria-selected', 'false');
            btn.setAttribute('tabindex', '-1');
            btn.innerHTML = head.innerHTML;

            li.appendChild(btn);
            nav.appendChild(li);

            const body = bodies[idx];
            body.id = panelId;
            body.setAttribute('role', 'tabpanel');
            body.setAttribute('aria-labelledby', tabId);
            body.style.display = 'none';

            // Click activation
            btn.addEventListener('click', () => this.activateTab(idx));

            // WAI-ARIA Keyboard Navigation
            btn.addEventListener('keydown', (e) => this.handleKeyDown(e, idx));
        });

        this.container.insertBefore(nav, this.container.firstChild);

        // 4. Restore saved active tab state
        let activeIdx = 0;
        if (this.options.saveState && window.StyxCookie) {
            const saved = window.StyxCookie.get(this.options.storageKey);
            if (saved !== undefined && heads[saved]) {
                activeIdx = parseInt(saved, 10);
            }
        }
        this.activateTab(activeIdx);
    }

    /**
     * Handles WAI-ARIA keyboard navigation for arrow keys, Home, and End.
     *
     * @private
     * @param {KeyboardEvent} e - The native keydown event.
     * @param {number} currentIndex - Index of the focused tab button.
     * @returns {void}
     */
    handleKeyDown(e, currentIndex) {
        const tabs = Array.from(this.container.querySelectorAll('[role="tab"]'));
        const totalTabs = tabs.length;
        let newIndex = null;

        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                newIndex = (currentIndex - 1 + totalTabs) % totalTabs;
                break;
            case 'ArrowRight':
            case 'ArrowDown':
                newIndex = (currentIndex + 1) % totalTabs;
                break;
            case 'Home':
                newIndex = 0;
                break;
            case 'End':
                newIndex = totalTabs - 1;
                break;
            default:
                return;
        }

        e.preventDefault();
        this.activateTab(newIndex);
        tabs[newIndex].focus();
    }

    /**
     * Activates a specific tab by index, updates ARIA states, handles CSS class assignments,
     * toggles panel visibility, and saves the active state to cookies.
     *
     * @param {number} index - Zero-based index of the tab to activate.
     * @returns {void}
     */
    activateTab(index) {
        const tabs = this.container.querySelectorAll('[role="tab"]');
        const allPanels = this.container.querySelectorAll(this.options.tabbody);
        const panels = Array.from(allPanels).slice(0, tabs.length);

        tabs.forEach((tab, i) => {
            const isSelected = i === index;
            const li = tab.parentElement;

            // 1. ARIA Accessibility State & Roving Tabindex
            tab.setAttribute('aria-selected', isSelected ? 'true' : 'false');
            tab.setAttribute('tabindex', isSelected ? '0' : '-1');

            // 2. CSS State classes (Modern 'current' as well as Serendipity legacy 'on')
            li.classList.toggle('current', isSelected);
            li.classList.toggle('on', isSelected);

            // 3. Positional Serendipity classes for first and last elements
            if (i === 0) {
                li.classList.add('first');
            }
            if (i === tabs.length - 1) {
                li.classList.add('last');
            }

            // 4. Toggle panel visibility
            if (panels[i]) {
                panels[i].style.display = isSelected ? 'block' : 'none';
            }
        });

        // Save active state to cookie (using setIfChanged to prevent unnecessary cookie writes)
        if (this.options.saveState && window.StyxCookie) {
            if (typeof window.StyxCookie.setIfChanged === 'function') {
                window.StyxCookie.setIfChanged(this.options.storageKey, index, 365);
            } else {
                window.StyxCookie.set(this.options.storageKey, index, { expires: 365 });
            }
        }
    }
}


/**
 * Lightweight Native Mouse/Pointer Drag & Drop List-Sortable component.
 * Enables reordering of list elements or plugin containers with custom handle support and callback notifications.
 *
 * @class StyxSortable
 * @param {HTMLElement|string} listContainer - The parent DOM element or CSS selector containing sortable items.
 * @param {Object|Function} [options={}] - Configuration options or direct update callback function.
 * @param {Function} [options.onUpdate] - Callback function triggered after a successful drag-and-drop operation. Receives the dropped HTMLElement as a parameter.
 * @param {string|null} [options.handle=null] - Optional CSS selector for drag handles. If omitted, the entire item is draggable.
 * @param {string} [options.item='li, .pluginmanager_plugin'] - CSS selector defining the sortable item elements.
 *
 * @example
 * // Basic usage with options object:
 * new StyxSortable('#my-list', {
 *     handle: '.drag-handle',
 *     item: '.sortable-item',
 *     onUpdate: function(droppedItem) {
 *         console.log('Item reordered:', droppedItem);
 *     }
 * });
 *
 * @example
 * // Shorthand usage with callback function:
 * new StyxSortable('.pluginmanager_container', function(droppedItem) {
 *     // Handle reorder event
 * });
 */
class StyxSortable {
    constructor(listContainer, options = {}) {
        this.list = typeof listContainer === 'string' ? document.querySelector(listContainer) : listContainer;

        if (typeof options === 'function') {
            this.onUpdate = options;
            this.handleSelector = null;
            this.itemSelector = 'li, .pluginmanager_plugin';
        } else {
            this.onUpdate = options.onUpdate;
            this.handleSelector = options.handle || null;
            this.itemSelector = options.item || 'li, .pluginmanager_plugin';
        }

        if (!this.list) return;

        this.draggedItem = null;
        this.placeholder = null;
        this.init();
    }

    /**
     * Initializes the component by attaching the primary mousedown event listener to the container.
     *
     * @private
     * @returns {void}
     */
    init() {
        this.list.addEventListener('mousedown', (e) => {
            const handle = this.handleSelector ? e.target.closest(this.handleSelector) : e.target;
            if (!handle) return;

            // Target the actual plugin row element instead of inner div
            const item = e.target.closest(this.itemSelector);
            if (!item || !this.list.contains(item)) return;

            e.preventDefault();

            this.startDrag(item, e);
        });
    }

    /**
     * Prepares the DOM elements, calculates positioning offsets, creates a visual placeholder,
     * and attaches tracking listeners (`mousemove` and `mouseup`) to the document.
     *
     * @private
     * @param {HTMLElement} item - The target element being dragged.
     * @param {MouseEvent} e - The initial mousedown event.
     * @returns {void}
     */
    startDrag(item, e) {
        this.draggedItem = item;

        const rect = item.getBoundingClientRect();
        const offsetY = e.clientY - rect.top;
        const offsetX = e.clientX - rect.left;

        // Create visual placeholder matching row dimensions
        this.placeholder = document.createElement(item.tagName);
        this.placeholder.className = 'styx-sortable-placeholder ';// + item.className;
        //this.placeholder.style.height = `${rect.height}px`;
        //this.placeholder.style.opacity = '0.3';
        //this.placeholder.style.border = '2px dashed #999';

        item.parentNode.insertBefore(this.placeholder, item);

        // Style the floating dragged row
        item.style.position = 'fixed';
        item.style.zIndex = '9999';
        item.style.width = `${rect.width}px`;
        item.style.height = `${rect.height}px`;
        item.style.pointerEvents = 'none';
        item.classList.add('dragging');
        document.body.classList.add('dragging');

        const moveAt = (pageX, pageY) => {
            item.style.left = `${pageX - offsetX}px`;
            item.style.top = `${pageY - offsetY}px`;
        };

        moveAt(e.clientX, e.clientY);

        const onMouseMove = (moveEvent) => {
            moveAt(moveEvent.clientX, moveEvent.clientY);

            // Temporarily hide element to find drop target underneath cursor
            item.style.display = 'none';
            const elemBelow = document.elementFromPoint(moveEvent.clientX, moveEvent.clientY);
            item.style.display = '';

            if (!elemBelow) return;

            const targetContainer = elemBelow.closest('.pluginmanager_container');
            const targetItem = elemBelow.closest('.pluginmanager_plugin');

            if (targetContainer) {
                if (targetItem && targetItem !== this.placeholder) {
                    const bounding = targetItem.getBoundingClientRect();
                    const offset = moveEvent.clientY - bounding.top - (bounding.height / 2);

                    if (offset > 0) {
                        targetItem.after(this.placeholder);
                    } else {
                        targetItem.before(this.placeholder);
                    }
                } else if (!targetContainer.contains(this.placeholder)) {
                    targetContainer.appendChild(this.placeholder);
                }
            }
        };

        const onMouseUp = () => {

            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            // Reset inline styling on dropped row
            item.style.position = '';
            item.style.zIndex = '';
            item.style.width = '';
            item.style.height = '';
            item.style.left = '';
            item.style.top = '';
            item.style.pointerEvents = '';
            item.classList.remove('dragging');
            document.body.classList.remove('dragging');

            if (this.placeholder && this.placeholder.parentNode) {
                this.placeholder.parentNode.insertBefore(item, this.placeholder);
                this.placeholder.remove();
            }

            this.placeholder = null;
            const droppedItem = this.draggedItem;
            this.draggedItem = null;

            if (droppedItem && typeof this.onUpdate === 'function') {
                this.onUpdate(droppedItem);
            }
        };

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    }
}


/**
 * Styx Plugin Update Overlay Controller
 * Manages the UI state during multi-plugin updates without external polyfills.
 */
class StyxProgressWidget {
    constructor(containerId = 'progressWidget') {
        this.containerId = containerId;
        this.init();
    }

    /**
     * Bind or re-bind DOM elements
     */
    init() {
        this.widget = document.getElementById(this.containerId);
        if (!this.widget) return false;

        this.messageEl = this.widget.querySelector('#updateMessage');
        this.progressEl = this.widget.querySelector('#updateProgress');
        this.indicatorEl = this.widget.querySelector('#updateIndicator');
        return true;
    }

    /**
     * Update progress bar and text message dynamically
     * @param {number} value - Progress percentage (0 to 100)
     * @param {string} [message] - Optional status message
     * @returns {boolean} Success status
     */
    update(value, message = null) {
        // Auto-init if DOM element wasn't available when script loaded
        if (!this.widget && !this.init()) return false;

        if (this.progressEl) {
            this.progressEl.value = Math.min(Math.max(value, 0), 100);
        }

        if (message && this.messageEl) {
            this.messageEl.textContent = message;
        }

        return true; // Send clear feedback instead of void/undefined
    }

    /**
     * Complete progress state
     * @param {string} [finalMessage]
     */
    finish(finalMessage = null) {
        this.update(100, finalMessage);
        if (this.indicatorEl) {
            this.indicatorEl.classList.remove('spinner');
        }
    }
}

// Immediately register on window scope
window.StyxProgress = new StyxProgressWidget();


/**
 * Serendipity Styx Administration Interface Logic
 * Refactored & Modernized (ES6+)
 */
(function ($) {
    "use strict";

    // Helper: HTML Encoding to Prevent XSS
    const escapeHTML = (str) => {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    // Helper: URL Sanitizer (Protection against JavaScript-style URIs)
    const sanitizeURL = (url) => {
        if (!url || typeof url !== 'string') return '';

        const sanitizedInput = url.trim().replace(/[\u0000-\u001F\u007F-\u009F]/g, '');

        // Check for explicit JavaScript protocols (XSS Protection)
        if (/^(javascript|data|vbscript):/i.test(sanitizedInput)) {
            return '';
        }

        // If it is a standard relative URL (without a protocol or scheme)
        if (!/^[a-z][a-z0-9+.-]*:/i.test(sanitizedInput)) {
            return encodeURI(sanitizedInput);
        }

        try {
            const parsed = new URL(sanitizedInput);
            // Only allow HTTP(S) and Mailto
            if (['http:', 'https:', 'mailto:'].includes(parsed.protocol)) {
                return parsed.href;
            }
            return '';
        } catch (e) {
            return '';
        }
    };

    // Global Namespace Setup
    window.serendipity = window.serendipity || {};
    window.browserFeatures = window.browserFeatures || {};

    /* ==========================================================
     * PART 1: Core-Utilities & Standard-Funktions, Language Definements,
     *         Media Management, AJAX-Operations & Utilities
     * ========================================================== */

    // Browser Feature Detection
    window.browserFeatures.touch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;

    window.browserFeatures.dateInput = (function () {
        const input = document.createElement('input');
        input.setAttribute('type', 'datetime-local');
        input.value = '2018-01-01T00:00';
        return input.value !== '2018-01-01T00:00';
    })();

    window.browserFeatures.idxDB = (function () {
        const db = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        return !!db;
    })();

    window.browserFeatures.isMinWidth = (minWidth) => {
        if (window.matchMedia) {
            return window.matchMedia(`(min-width: ${minWidth}px)`).matches;
        }
        return $(window).width() >= minWidth;
    };


    // Ensure serendipity and language containers exist
    window.serendipity = window.serendipity || {};
    window.serendipity.lang = window.serendipity.lang || {};

    const StyxConfig = {
        /**
         * Safely read server/backend configuration variables.
         * Usage: StyxConfig.get('varName', varValue);
         */
        get(key, fallback = null) {
            return window.serendipity?.[key] ?? fallback;
        }
    };

    const StyxLang = {
        /**
         * Safely read localized language strings.
         * Usage: StyxLang.get('varName', 'varValue');
         */
        get(key, fallback = '') {
            return window.serendipity?.lang?.[key] ?? fallback;
        }
    };

    /* ==========================================================
     * Cookie Compatibility Bridge (Replaces js-cookie & Old Helpers)
     * Matches PHP Smarty 'ifRemember' logic & StyxCookie
     * ========================================================== */
    /**
     * Normalizes input keys to match Serendipity's legacy PHP cookie array.
     * Example: 'linkThumbnail' -> 'serendipity_linkThumbnail'
     */
    function _toSerendipityCookieName(name) {
        if (!name) return name;

        // If the name is already raw/prefixed, bracketed or a dynamic session key, keep it as-is
        if (name.includes('[') || name.startsWith('serendipity_') || name.startsWith('s9y_')) {
            return name;
        }

        // Attach 'serendipity_' so PHP's $serendipity['COOKIE']['serendipity_' . $name] picks it up
        return 'serendipity_' + name;
    }

    // Mimic the js-cookie Library (Cookies.get, Cookies.set, Cookies.remove)
    window.Cookies = {
        get(name) {
            return StyxCookie.get(_toSerendipityCookieName(name));
        },
        set(name, value, options) {
            StyxCookie.set(_toSerendipityCookieName(name), value, options);
        },
        remove(name, options = {}) {
            StyxCookie.remove(_toSerendipityCookieName(name), options);
        }
    };

    // Serendipity Namespace Wrappers (for serendipity.SetCookie / GetCookie / PurgeCookie)
    $.extend(window.serendipity, {
        SetCookie(name, value, days = 365) {
            StyxCookie.set(_toSerendipityCookieName(name), value, { expires: days });
        },
        GetCookie(name) {
            return StyxCookie.get(_toSerendipityCookieName(name));
        },
        PurgeCookie(name) {
            StyxCookie.remove(_toSerendipityCookieName(name));
        }
    });

    // Global fallbacks for old calls in legacy scripts
    window.SetCookie = function(name, value, days) {
        window.serendipity.SetCookie(name, value, days);
    };
    window.GetCookie = function(name) {
        return window.serendipity.GetCookie(name);
    };

    /* ==========================================================
     * Serendipity Namespace API
     * ========================================================== */

    // Status flag for image container (<picture>)
    let pictureSubmit = false;

    $.extend(window.serendipity, {

        // --- Core Utilities & Helpers ---

        /**
         * Legacy wrapper method for setting persistent Styx cookies.
         * Delegates directly to the modern StyxCookie utility with a default 1-year expiration.
         *
         * @param {string} name - The cookie identifier name.
         * @param {string|number} value - The cookie payload value.
         * @param {number} [days=365] - Optional expiration in days.
         */
        SetCookie(name, value, days = 365) {
            StyxCookie.set(name, value, { expires: days });
        },

        /**
         * Legacy wrapper method for purging persistent Styx cookies.
         *
         * @param {string} name - The cookie identifier name.
         */
        PurgeCookie(name) {
            StyxCookie.remove(name);
        },

        // --- Generic Helpers, Entries, Dashboard, Comments ---

        /**
         * Scrolls the page to a specified target anchor.
         *
         * @param {string} target - The element selector, ID, or href anchor (e.g. "#tags" or "index.php#tags").
         * @param {number} [time=250] - Animation duration in milliseconds.
         */
        skipScroll(target, time = 250) {
            if (!target || typeof target !== 'string') return;

            // Extract ID from full URL if needed (e.g., 'page.html#section' -> 'section')
            const hashIndex = target.indexOf('#');
            const selector = hashIndex !== -1 ? target.substring(hashIndex) : target;
            const $target = $(selector);

            if ($target.length) {
                $('html, body').animate({
                    scrollTop: $target.offset().top
                }, time);
            }
        },

        /**
         * Central Styx Display Mode Resolver
         * Returns whether a feature should be rendered directly into the form ('embedded')
         * or as an overlay dialog ('inline_modal').
         *
         * @param {string} feature - Feature key ('categories', 'tags', 'links', 'images', 'comments')
         * @returns {'embedded'|'inline_modal'}
         */
        getDisplayMode(feature) {
            // ⚡ STYX-CONFIG CONVERTED VARIABLES:
            const forcePopups = StyxConfig.get('forceBackendPopups', {});

            // Check if current feature is explicitly flagged for forced embedded mode
            if (Boolean(forcePopups && forcePopups[feature])) {
                return 'embedded';
            }

            // Standard for all others is the modern Inline-Modal
            return 'inline_modal';
        },

        /**
         * Opens a modal dialog for media pickers, comment replies, and internal tools via StyxModal.
         *
         * @param {string} url - The URL to load inside the modal iframe.
         * @param {string} [name='popup'] - The target identifier (retained for signature compatibility).
         */
        openPopup(url, name = 'popup') {
            const safeURL = sanitizeURL(url);
            if (!safeURL) {
                console.error('Invalid URL provided to openPopup');
                return;
            }

            // Case 1: If executed inside an iframe, delegate to top window
            if (window !== window.top && window.top.serendipity && typeof window.top.serendipity.openPopup === 'function') {
                return window.top.serendipity.openPopup(safeURL, name);
            }

            // Case 2: Open content inside native StyxModal Engine (<dialog>)
            const modalEngine = window.StyxModalInstance || (window.top && window.top.StyxModalInstance);
            if (modalEngine && typeof modalEngine.open === 'function') {
                modalEngine.open({
                    html: `<iframe src="${safeURL}" style="width:100%; height:75vh; border:none; display:block; box-shadow: 0 0 8px rgba(0, 0, 0, 0.6); background: #000;"></iframe>`,
                    mainClass: 'styx-media-picker-modal'
                });
                return;
            }

            console.error('StyxModalInstance not available to render popup content for:', safeURL);
        },

        /**
         * Closes the comment reply modal overlay via StyxModal.
         */
        closeCommentPopup() {
            // Locate the native StyxModalInstance across window contexts
            const modalEngine = window.StyxModalInstance
                             || window.parent?.StyxModalInstance
                             || window.top?.StyxModalInstance;

            if (modalEngine && typeof modalEngine.close === 'function') {
                modalEngine.close();
                return;
            }

            console.warn('StyxModalInstance not available to close comment modal.');
        },

        /**
         * Inserts a self-dismissing status notice banner before a specified target DOM element.
         * Automatically removes existing notices with the same ID and handles manual/auto fadeout.
         *
         * @param {string} message - The notice message text.
         * @param {string} id - The unique ID attribute for the notice element.
         * @param {jQuery|HTMLElement} $targetElement - The target element before which the notice will be inserted.
         */
        showStatusNotice(message, id, $targetElement) {
            const safeMessage = escapeHTML(message);
            const safeId = escapeHTML(id);

            const $msg = $(`
                <span id="${safeId}" class="msg_notice">
                    <span class="icon-info-circled" aria-hidden="true"></span>
                    ${safeMessage}
                    <a class="remove_msg" href="#${safeId}" aria-label="Hide">
                        <span class="icon-cancel" aria-hidden="true"></span>
                    </a>
                </span>
            `);

            $(`#${safeId}`).remove();
            $msg.insertBefore($targetElement);

            $msg.find('.remove_msg').on('click', function (ev) {
                ev.preventDefault();
                $(this).parent().fadeOut(250, function () { $(this).remove(); });
            });

            setTimeout(() => $msg.fadeOut(250, function () { $(this).remove(); }), 5000);
        },

        // --- Editor & Markup Helpers ---

        /**
         * Triggers editor or markup generator initializations.
         * Serves as a bridge to global legacy helper scripts (e.g., HTML nuggets or WYSIWYG spawners).
         */
        spawn() {
            if (typeof serendipity_spawn === 'function') {
                serendipity_spawn();
            }
        },

        /**
         * Returns the currently selected text in a target input/textarea.
         *
         * @param {jQuery|HTMLElement|string} target - The target element or selector.
         * @returns {string} The selected text string.
         */
        getSelection(target) {
            const $element = $(target);
            if (!$element.length) return '';
            const element = $element.get(0);

            if (element.selectionStart !== undefined && element.selectionStart !== null) {
                return element.value.substring(element.selectionStart, element.selectionEnd);
            }
            return '';
        },

        /**
         * Used by PlainText Editor toolbar buttons
         * Wraps the currently selected text inside a target element (e.g. textarea)
         * with specified opening and closing tags, maintaining selection and scroll position.
         *
         * @param {jQuery|HTMLElement|string} target - The target textarea element or selector.
         * @param {string} openTag - The opening tag or markup string (e.g., '<strong>' or '**').
         * @param {string} closeTag - The closing tag or markup string (e.g., '</strong>' or '**').
         */
        wrapSelection(target, openTag, closeTag) {
            const $element = $(target);
            if (!$element.length) return;
            const element = $element.get(0);

            if (element.selectionStart !== undefined && element.selectionStart !== null) {
                // Standard Browsers
                const startPos = element.selectionStart;
                const endPos = element.selectionEnd;
                const scrollTop = element.scrollTop;
                const selectedText = element.value.substring(startPos, endPos);

                element.value = element.value.substring(0, startPos) + openTag + selectedText + closeTag + element.value.substring(endPos);
                element.focus();
                element.selectionStart = startPos + openTag.length;
                element.selectionEnd = startPos + openTag.length + selectedText.length;
                element.scrollTop = scrollTop;
            } else {
                // Fallback: Append text at the end if selection is not supported
                element.value += openTag + closeTag;
                element.focus();
            }
        },

        /**
         * Prompts the user for URL, Description, and Title/Tooltip,
         * then wraps the selection (or inserts a new link) via wrapSelection().
         *
         * @param {jQuery|HTMLElement|string} target - The target textarea element or selector.
         */
        wrapSelectionWithLink(target) {
            const $element = $(target);
            if (!$element.length) return;

            const myLink = prompt('Enter URL:', 'https://');
            if (!myLink || myLink === 'https://' || myLink === 'http://') {
                return;
            }

            let myDesc = '';
            const selectedText = this.getSelection($element);

            // Ask for description only if no text was highlighted
            if (!selectedText) {
                myDesc = prompt('Enter Description:', '') || '';
            }

            const myTitle = prompt('Enter title/tooltip:', '') || '';

            // Optional: Target Query
            const openInNewWindow = confirm('Open in a new Window/Tab (target="_blank")?');

            // Construct link tags safely
            const htmlTitle = myTitle.trim() ? ` title="${myTitle.trim()}"` : '';
            const htmlTarget = openInNewWindow ? ' target="_blank" rel="noopener"' : '';

            const openTag = `<a href="${myLink.trim()}"${htmlTitle}${htmlTarget}>`;
            const closeTag = myDesc.trim() ? `${myDesc.trim()}</a>` : '</a>';

            // Leverage existing wrapSelection method
            this.wrapSelection($element, openTag, closeTag);
        },

        /**
         * Fallback helper for plain textareas to insert an <img> tag via prompt dialogs.
         * Used by non-WYSIWYG toolbar buttons (.wrap_insimg).
         *
         * @param {jQuery|HTMLElement} txtarea - Target textarea element or jQuery object.
         */
        wrapInsImage(txtarea) {
            const $tarea = $(txtarea);
            if (!$tarea.length) return;

            const loc = prompt('Enter the image location:');
            if (!loc) return;

            const alttxt = prompt('Enter alternative text for this image:') || '';
            const imgTag = `<img src="${loc}" alt="${alttxt}">`;

            // Modern cursor-aware insertion fallback
            const el = $tarea[0];
            if (typeof el.selectionStart === 'number' && typeof el.selectionEnd === 'number') {
                const start = el.selectionStart;
                const end = el.selectionEnd;
                const val = el.value;

                el.value = val.substring(0, start) + imgTag + val.substring(end);
                el.selectionStart = el.selectionEnd = start + imgTag.length;
                el.focus();
            } else if (typeof serendipity.insertText === 'function') {
                // Fallback to internal insertText helper if available
                serendipity.insertText($tarea, imgTag);
            } else {
                // Basic append fallback
                $tarea.val($tarea.val() + imgTag);
            }
        },

        /**
         * Modern Styx Entry Preview Handler
         * Binds automatically to #serendipity_iframe to sync content height without inline scripts
         */
        initPreviewIframe(iframeId = 'serendipity_iframe') {
            const previewFrame = document.getElementById(iframeId);
            if (!previewFrame) return;

            // 1. Set iframe container styles once
            Object.assign(previewFrame.style, {
                border: '0',
                width: '100%',
                overflow: 'hidden',
                transition: 'height 0.15s ease-out'
            });
            previewFrame.scrolling = 'no';

            // Helper: Safely measure and update height
            const syncHeight = () => {
                try {
                    const frameDoc = previewFrame.contentDocument || previewFrame.contentWindow?.document;
                    if (!frameDoc || !frameDoc.documentElement) return;

                    const height = frameDoc.documentElement.scrollHeight;
                    if (height > 0) {
                        previewFrame.style.height = `${height}px`;
                    }
                } catch (e) {
                    console.warn('Styx Preview: Access to iframe content restricted', e);
                }
            };

            // 2. Clear old load listeners and attach fresh handler
            previewFrame.onload = function() {
                syncHeight();

                const frameWindow = previewFrame.contentWindow;
                const frameDoc = previewFrame.contentDocument;

                if (!frameWindow || !frameDoc) return;

                // 3. Dynamic ResizeObserver inside iframe document
                if ('ResizeObserver' in frameWindow) {
                    const observer = new frameWindow.ResizeObserver(() => syncHeight());
                    observer.observe(frameDoc.documentElement);
                } else {
                    // Fallback for asynchronous image loading
                    frameDoc.querySelectorAll('img').forEach(img => {
                        if (!img.complete) {
                            img.addEventListener('load', syncHeight, { once: true });
                        }
                    });
                }
            };

            // Trigger initial calculation if iframe is already loaded
            if (previewFrame.contentDocument?.readyState === 'complete') {
                previewFrame.onload();
            }
        },

        // --- Media Integration & Field Helpers ---

        /**
         * Updates the image source of a preview container based on the value of a media input field.
         * Accommodates picture element containers as well as standard <img> elements.
         *
         * @param {string} inputId - The ID of the input element containing the image URL.
         * @param {string} outputId - The base ID of the preview wrapper container.
         */
        change_preview(inputId, outputId) {
            const inputEl = document.getElementById(inputId);
            if (!inputEl) return;

            const filename = inputEl.value;
            const $previewTarget = $(`#${outputId}_preview img`);

            if ($previewTarget.length && filename) {
                $previewTarget.attr('src', filename);
            }
        },

        /**
         * Opens the Media Library selector dialog in a popup window for configuration or custom fields.
         *
         * Note: Parameters like 'filename_only' serve as initial default seeds.
         * Once the modal is active, these are overridden by subsequent user interactions via
         * serendipity_generateImageSelectorParems() and the template's $media.extraParems.
         *
         * @param {string} targetId - The target input/textarea HTML ID to receive the selected image URL.
         */
        choose_media(targetId) {
            if (!targetId) return;

            // Construct query parameters using native URLSearchParams
            const params = new URLSearchParams({
                'serendipity[adminModule]': 'media',
                'serendipity[popupContent]': 'true',
                'serendipity[showUpload]': 'false',
                'serendipity[filename_only]': 'true',
                'serendipity[htmltarget]': targetId
            });

            // decodeURIComponent prevents double-encoding (%255B) when passing the
            // URL string to openPopup, ensuring PHP correctly receives array keys.
            const popupUrl = 'serendipity_admin.php?' + decodeURIComponent(params.toString());

            if (typeof serendipity.openPopup === 'function') {
                serendipity.openPopup(popupUrl);
            }
        },

        /**
         * Appends a keyword to the media item keyword input field ('#keyword_input'),
         * separated by a semicolon.
         * Used in media properties dialogs.
         *
         * @param {string} keyword - The keyword string to append.
         */
        AddKeyword(keyword) {
            const $entry = $('#keyword_input');
            if ($entry.length) {
                const currentVal = $entry.val();
                $entry.val(currentVal ? `${currentVal};${keyword}` : keyword);
            }
        },

        // --- Entries List, on Search helpers - REMEMBERED ---

        /**
         * Pins or unpins a filtered entry temporarily.
         * Synchronizes browser localStorage with StyxCookie management (30 days TTL).
         *
         * @param {string|number} el - Entry ID to pin/unpin.
         */
        PinFilter(el) {
            if (!el) return;

            const entryId = (typeof el === 'object')
                ? ($(el).data('entry-id') || $(el).val())
                : el;

            if (!entryId) return;

            const storageKey = `pin_entry_${entryId}`;
            const cookieKey = `entrylist_pin_entry_${entryId}`;

            try {
                if (localStorage.getItem(storageKey) === null) {
                    localStorage.setItem(storageKey, Date.now().toString());
                    // Store cookie with matching 30-day lifetime
                    StyxCookie.set(cookieKey, 'true', { expires: 30 });
                } else {
                    localStorage.removeItem(storageKey);
                    StyxCookie.remove(cookieKey);
                }
            } catch (e) {
                console.warn('localStorage failure in PinFilter:', e);
            }
        },

        /**
         * Calculates and appends the expiration time for a pinned entry DOM element title.
         * Automatically cleans up entries older than 30 days.
         *
         * @param {string|number} el - Entry ID to check expiration for.
         */
        GetPinExpireTime(el) {
            if (!el) return;
            const storageKey = `pin_entry_${el}`;
            const cookieKey = `entrylist_pin_entry_${el}`;

            try {
                const expStr = localStorage.getItem(storageKey);
                if (expStr !== null) {
                    const exp = Number(expStr);
                    if (isNaN(exp)) return;

                    const now = Date.now();
                    const daysPassed = Math.floor((now - exp) / (1000 * 60 * 60 * 24));

                    // Auto-cleanup expired pins (> 30 days)
                    if (daysPassed >= 30) {
                        localStorage.removeItem(storageKey);
                        StyxCookie.remove(cookieKey);
                        return;
                    }

                    const daysRemaining = 30 - daysPassed;
                    const $pinnedElem =$(`#entry_${el} .ucc-pinned-to`);

                    if ($pinnedElem.length) {
                        let baseTitle = $pinnedElem.data('original-title');
                        if (!baseTitle) {
                            baseTitle = $pinnedElem.attr('title') || '';
                            $pinnedElem.data('original-title', baseTitle);
                        }

                        // Clean localization fetch via StyxLang
                        const langTemplate = StyxLang.get('expires_in', '. Expires in %d days.');
                        const expireMsg = langTemplate.replace('%d', daysRemaining);

                        $pinnedElem.attr('title', `${baseTitle}${expireMsg}`);
                    }
                }
            } catch (e) {
                console.warn('localStorage access failed in GetPinExpireTime:', e);
            }
        },

        /**
         * Batch initializes pin expiration titles for all pinned items in the list.
         */
        InitPinExpireTimes() {
            $('.pinpoint:checked, .ucc-pinned-to').each((_, el) => {
                const entryId = $(el).data('entry-id') || $(el).closest('[id^="entry_"]').attr('id')?.replace('entry_', '');
                if (entryId) {
                    this.GetPinExpireTime(entryId);
                }
            });
        },

        // --- Entry Form only relations ---

        /**
         * Reads selected categories strictly from the primary form
         * and updates the summary list inside '#cats_list ul'.
         */
        catsList() {
            const $target = $('#cats_list ul');
            if (!$target.length) return;

            $target.empty();

            // Scope selection strictly to primary hidden form to avoid counting modal clones
            const $selected = $('#meta_data #edit_entry_category input[type="checkbox"]:checked');

            if ($selected.length > 0) {
                $selected.each(function () {
                    const catText = $(this).siblings('label').text().trim();
                    $('<li>').addClass('selected').text(catText).appendTo($target);
                });
            } else {
                $('<li><em>No categories</em></li>').appendTo($target);
            }
        },

        /**
         * Reads the comma-separated freetags from the entry input element
         * and renders them as individual list items inside '#tags_list ul'.
         */
        tagsList() {
            const $input = $('#properties_freetag_tagList').length
                ? $('#properties_freetag_tagList')
                : $('#properties_Tags');
            const $target = $('#tags_list ul');

            if (!$target.length) return;

            $target.empty();

            const rawVal = $input.val();
            if (typeof rawVal !== 'undefined' && rawVal.trim() !== '') {
                const tags = rawVal.split(',').map(tag => tag.trim()).filter(Boolean);

                if (tags.length > 0) {
                    tags.forEach(tag => {
                        $('<li>').addClass('tagged').text(tag).appendTo($target);
                    });
                } else {
                    $('<li><em>No tags</em></li>').appendTo($target);
                }
            } else {
                $('<li><em>No tags</em></li>').appendTo($target);
            }
        },

        // --- Toggle bars in entry forms - partly REMEMBERED ---

        /**
         * Toggles the visibility of the extended entry editor and its tools,
         * dynamic button generation, state persistence in localStorage and cookie.
         *
         * @param {boolean} [setCookie=false] - Whether to persist state in document.cookie.
         */
        toggle_extended(setCookie) {
            const $toggleButton = $('#toggle_extended');

            if (!$toggleButton.length) {
                // Initial setup: Wrap label with toggle button
                const $editor = $('#extended_entry_editor');
                if (!$editor.length) return;

                $editor.parent().find('label').first().wrap('<button id="toggle_extended" class="icon_link" type="button"></button>');

                const $btn = $('#toggle_extended');
                $btn.prepend('<span class="icon-down-dir" aria-hidden="true"></span> ');
                $btn.on('click', (e) => {
                    e.preventDefault();
                    this.toggle_extended(true);
                });

                if (window.localStorage && localStorage.getItem('show_extended_editor') === 'true') {
                    return;
                }
            }

            const $extEditor = $('#extended_entry_editor');
            const $toolsExt = $('#tools_extended');
            const $icon = $('#toggle_extended').find('> [class*="icon-"]');

            if ($extEditor.is(':hidden')) {
                $extEditor.show();
                $toolsExt.show();
                $icon.removeClass('icon-right-dir').addClass('icon-down-dir');

                if (window.localStorage) {
                    localStorage.setItem('show_extended_editor', 'true');
                }
            } else {
                $extEditor.hide();
                $toolsExt.hide();
                $icon.removeClass('icon-down-dir').addClass('icon-right-dir');

                if (window.localStorage) {
                    localStorage.setItem('show_extended_editor', 'false');
                }
            }

            if (setCookie) {
                const isVisible = !$extEditor.is(':hidden');
                document.cookie = `serendipity[toggle_extended]=${isVisible ? 'true' : ''};SameSite=Lax;`;
            }
        },

        /**
         * Holds stored category IDs when collapsing a multi-select category list
         * to single-select mode, preserving selection state.
         * @type {string|null}
         */
        _categoryselector_stored_categories: null,

        /**
         * Toggles the category selector element between single-select and multi-select mode.
         * Dynamic button generation, height calculation, and selection state restoration.
         *
         * @param {string} id - The ID of the category select element (e.g. 'categoryselector').
         */
        toggle_category_selector(id) {
            const $select = $(`#${id}`);
            if (!$select.length) return;

            const toggleBtnId = `toggle_${id}`;
            const $toggleButton = $(`#${toggleBtnId}`);

            if (!$toggleButton.length) {
                // Initial setup: Insert toggle button before select element
                $select.before(`<button id="${toggleBtnId}" class="button_link" type="button" href="#${id}" aria-label="Toggle All"><span class="icon-right-dir" aria-hidden="true"></span></button>`);

                $(`#${toggleBtnId}`).on('click', (e) => {
                    e.preventDefault();
                    $(`#${toggleBtnId}`).toggleClass('active');
                    this.toggle_category_selector(id);
                });

                $select.on('change', () => {
                    this._categoryselector_stored_categories = null;
                });

                // Preserve list height and icon if multiple items were pre-selected (e.g., after preview)
                if ($select.children('*[selected="selected"]').length > 1) {
                    $select.attr('size', $select.children().length);
                    $(`#${toggleBtnId}`).find('> .icon-right-dir').removeClass('icon-right-dir').addClass('icon-down-dir');
                    return;
                }
            }

            const $icon = $(`#${toggleBtnId}`).find('> [class*="icon-"]');

            if ($select.attr('multiple')) {
                // Collapse to single-select: store current multi-selection
                const selected = [];
                $select.children(':selected').filter('[value!="0"]').each((_, child) => {
                    selected.push($(child).val());
                });

                if (selected.length > 1) {
                    this._categoryselector_stored_categories = selected.join(',');
                }

                $select.removeAttr('multiple');
                $select.removeAttr('size');
                $icon.removeClass('icon-down-dir').addClass('icon-right-dir');

            } else {
                // Expand to multi-select: restore stored selection
                $select.attr('multiple', '');
                $select.attr('size', $select.children().length);
                $icon.removeClass('icon-right-dir').addClass('icon-down-dir');

                if (this._categoryselector_stored_categories) {
                    const catIds = this._categoryselector_stored_categories.split(',');
                    catIds.forEach((catId) => {
                        if (catId) {
                            $select.find(`[value="${catId}"]`).prop('selected', true);
                        }
                    });
                }
            }
        },

        // --- MEDIA LIBRARY - BY SECTION ---
        // ----- I.   Upload & File API     [ 1 to 6 ]
        // ----- II.  ML & Selection API    [ 1 to 7 ]
        // ----- III. Tooling API           [ 1 to 6 ]

        /* ==========================================================
         * I. Media Upload Helpers & Filename Extraction (media_upload.tpl)
         *                      & File Management API [ 1 to 6 ]
         * ========================================================== */

        /**
         * Storage array tracking original filenames for multi-file upload inputs.
         */
        inputStorage: [],

        /**
         * Extracts the filename from a given path or URL string.
         * Handles both POSIX (/) and Windows (\) path separators.
         *
         * @param {string} value - The file path or input value.
         * @returns {string} The extracted filename.
         */
        getFileName(value) {
            if (!value || typeof value !== 'string') {
                return '';
            }
            // Strips path (slash/backslash) and optional query params/anchors
            return value.replace(/^.*[\\\/]/, '').split('?')[0].split('#')[0];
        },

        /**
         * Alias method for camelCase consistency across modern modules.
         */
        getfilename(value) {
            return this.getFileName(value);
        },

        /**
         * Auto-fills the target filename field based on the selected upload file.
         *
         * @param {number|string} source - Index of the userfile input.
         * @param {number|string} target - Index of the target filename input.
         */
        fillInput(source, target) {
            const sourceEl = document.getElementById(`userfile_${source}`);
            const targetEl = document.getElementById(`target_filename_${target}`);

            if (!sourceEl || !targetEl) return;

            const sourceVal = this.getFileName(sourceEl.value);

            if (sourceVal.length > 0) {
                targetEl.value = sourceVal;
                this.inputStorage[target] = sourceVal;
            }
        },

        /**
         * Iterates over all active upload fields and updates target filenames
         * if they haven't been manually overwritten by the user.
         */
        checkInputs() {
            const uploadFieldCount = $('.uploadform_userfile').length;

            for (let i = 1; i <= uploadFieldCount; i++) {
                const targetEl = document.getElementById(`target_filename_${i}`);
                const currentTargetVal = targetEl ? targetEl.value : '';

                if (!this.inputStorage[i] || this.inputStorage[i] === currentTargetVal) {
                    this.fillInput(i, i);
                }
            }
        },

        /**
         * Clones the last upload form field group and appends it to #uploads.
         */
        addUploadField() {
            const fieldCount = $('.uploadform_userfile').length + 1;
            const $lastField = $('#uploads > div:last-child');
            if (!$lastField.length) return;

            // Clone the last element
            const $newFields = $lastField.clone(true);
            $newFields.attr('id', `upload_form_${fieldCount}`);

            // File Input & Label
            $('.uploadform_userfile', $newFields)
                .attr('id', `userfile_${fieldCount}`)
                .attr('name', `serendipity[userfile][${fieldCount}]`)
                .val('');
            $('.uploadform_userfile_label', $newFields)
                .attr('for', `userfile_${fieldCount}`);

            // Target Filename Input & Label
            $('.uploadform_target_filename', $newFields)
                .attr('id', `target_filename_${fieldCount}`)
                .attr('name', `serendipity[target_filename][${fieldCount}]`)
                .val('');
            $('.uploadform_target_filename_label', $newFields)
                .attr('for', `target_filename_${fieldCount}`);

            // Target Directory Select & Label
            const $prevDir = $(`#target_directory_${fieldCount - 1}`);
            const prevVal = $prevDir.length ? $prevDir.val() : '';

            $('.uploadform_target_directory', $newFields)
                .attr('id', `target_directory_${fieldCount}`)
                .attr('name', `serendipity[target_directory][${fieldCount}]`)
                .val(prevVal);

            $('.uploadform_target_directory_label', $newFields)
                .attr('for', `target_directory_${fieldCount}`);

            // Append cloned row to wrapper
            $newFields.appendTo('#uploads');
        },

        /**
         * Saves the selected target upload/download directory into the 'addmedia_directory' cookie
         * so Styx preselects it on future media actions, and displays the progress spinner.
         */
        rememberUploadOptions() {
            // Determine active select field: Priority to Upload tab (#target_directory_1), fallback to Download tab (#imagetargetdirectory)
            const $targetDir = $('#target_directory_1').length
                ? $('#target_directory_1')
                : $('#imagetargetdirectory');

            if ($targetDir.length) {
                const dirVal = $targetDir.val();
                if (dirVal !== undefined && dirVal !== null) {
                    StyxCookie.set('addmedia_directory', dirVal);
                }
            }

            // Reveal the progress spinner during form processing
            $('#waitingspin').show();
        },

        /* ==============================================================
         * II. Serendipity Media Library & Selection API [ 1 to 7 ]
         * ============================================================== */

        /**
         * Persists user selection state (alignment, link targets, thumbnail choices, etc.) in cookies.
         * Form values are mapped with the required 'serendipity_' prefix for PHP/Smarty 'ifRemember' compatibility.
         */
        rememberMediaOptions: function() {
            const selForm = document.forms['serendipity[selForm]']
                         || document.forms['serendipity_selForm']
                         || document.querySelector('form');

            if (!selForm) return;

            // Strictly match keys processed by PHP's Smarty ifRemember helper
            const fields = ['linkThumbnail', 'align', 'isLink', 'target', 'comment'];

            fields.forEach((fieldName) => {
                const element = selForm.elements[`serendipity[${fieldName}]`]
                             || selForm.elements[`serendipity_${fieldName}`]
                             || selForm.elements[fieldName];

                if (element) {
                    let val = undefined;

                    // Resolve values for radio button groups, preserving empty strings
                    if (element.length && (element[0].type === 'radio' || element.type === 'radio')) {
                        for (let i = 0; i < element.length; i++) {
                            if (element[i].checked) {
                                val = element[i].hasAttribute('value') ? element[i].value : '';
                                break;
                            }
                        }
                    } else if (element.type === 'checkbox') {
                        val = element.checked ? (element.value || 'true') : '';
                    } else {
                        val = element.value;
                    }

                    if (val !== undefined && val !== null) {
                        // Ensure key is prefixed with 'serendipity_' for Smarty ifRemember compatibility
                        const cookieKey = fieldName.startsWith('serendipity_') ? fieldName : `serendipity_${fieldName}`;

                        // StyxCookie clears potential un-prefixed shadow cookies by itself and sets the path correct
                        StyxCookie.set(cookieKey, val);
                    }
                }
            });
        },

        /**
         * Flags the submission state for Picture Mode (<picture> tag generation)
         * prior to inserting media into the editor.
         */
        mediaPictureSubmit: function() {
            pictureSubmit = true;
        },

        /**
         * Media Gallery insertion handler. Loops over a payload of multiple files,
         * constructs gallery grid wrappers (using AVIF/WebP responsive sources or standard img tags),
         * and routes the final gallery block to the target editor.
         *
         * @param {string} [textarea] - Target textarea DOM ID or field identifier.
         * @param {Object} g - Gallery data object containing alignment, orientation, defcols, and file list.
         */
        serendipity_imageGallerySelector_done: function(textarea, g) {
            if (!g || !Array.isArray(g.files) || g.files.length === 0) {
                this.closeMediaModal();
                return false;
            }

            let galleryHtml = '';

            const float  = g.align || 'center';
            const orient = g.orient || 'col';
            let dc       = g.defcols || '3';

            if (orient !== 'col') {
                dc = 'null';
            }

            const totalFiles  = g.files.length;
            const columnClass = (orient === 'col' && totalFiles < 3) ? totalFiles : dc;

            // Open the gallery block element
            galleryHtml += `<div class="serendipity_image_block ${orient} c${columnClass}">`;

            g.files.forEach(v => {
                const picEl   = !!(v.full_thumb_avif || v.full_file_avif || v.full_thumb_webp || v.full_file_webp);
                const iAVFrmt = v.sizeAVIF > 252 && (v.sizeWebp === 0 || v.sizeAVIF <= v.sizeWebp || !v.full_thumb_webp);
                const thbAVFt = v.thumbSizeAVIF > 252 && (v.thumbSizeWebp === 0 || v.thumbSizeAVIF <= v.thumbSizeWebp || !v.thumbSizeWebp);

                const imgID   = v.id;
                const imgWdth = v.thumbWidth || '';
                const imgHght = v.thumbHeight || '';
                let imgName   = v.full_thumb;

                const ilink   = picEl ? ((v.full_file_avif && iAVFrmt) ? v.full_file_avif : v.full_file_webp) : v.full_file;
                const ilinkfb = v.full_file; // Fallback URL

                const title   = (v.prop_title && v.prop_title.trim() !== '') ? v.prop_title.replace(/"/g, "&quot;") : (v.realname ? v.realname.replace(/"/g, "&quot;") : '');
                const imgalt  = v.prop_alt ? v.prop_alt.replace(/"/g, "&quot;") : (v.realname ? v.realname.replace(/"/g, "&quot;") : '');
                const iftavif = v.full_thumb_avif || '';
                const iftwebp = v.full_thumb_webp || '';

                if (v.hotlink) {
                    imgName = v.realfile;
                }

                let itemHtml = '';

                if (pictureSubmit && picEl) {
                    const oExt = imgName.split('.').pop().toLowerCase();
                    const isWebP = (oExt === 'webp');

                    itemHtml = `<!-- s9ymdb:${imgID} --><picture>`
                             + (thbAVFt ? `<source type="image/avif" srcset="${iftavif}">` : '')
                             + (!isWebP ? `<source type="image/webp" srcset="${iftwebp}">` : '')
                             + `<img class="serendipity_image_${float}" width="${imgWdth}" height="${imgHght}" src="${imgName}"${(title !== '' && g.isLink === 'no') ? ` title="${title}"` : ''} loading="lazy" alt="${imgalt}">`
                             + `</picture>`;
                } else {
                    itemHtml = `<!-- s9ymdb:${imgID} --><img class="serendipity_image_${float}" width="${imgWdth}" height="${imgHght}" src="${imgName}"${(title !== '' && g.isLink === 'no') ? ` title="${title}"` : ''} loading="lazy" alt="${imgalt}">`;
                }

                if (g.isLink === 'yes') {
                    itemHtml = `<a class="serendipity_image_link"${title !== '' ? ` title="${title}"` : ''} href="${ilink}" data-fallback="${ilinkfb}">${itemHtml}</a>`;
                }

                if (v.prop_imagecomment && v.prop_imagecomment.trim() !== '') {
                    const comment = v.prop_imagecomment;
                    itemHtml = `<div class="serendipity_imageComment_${float}" style="width: ${imgWdth}px">`
                             +     `<div class="serendipity_imageComment_img">${itemHtml}</div>`
                             +     `<div class="serendipity_imageComment_txt">${comment}</div>`
                             + `</div>`;
                }

                galleryHtml += itemHtml;
            });

            // Close the gallery block element
            galleryHtml += '</div>';

            // Wrap in a div container for RichText / WYSIWYG editors when picture mode is active
            if (pictureSubmit) {
                galleryHtml = `<div>${galleryHtml}</div>`;
            }

            // Determine target context window (parent window when inside an iframe overlay, otherwise current window)
            const targetWindow = (window.parent && window.parent !== window) ? window.parent : window;

            // Standardize target textarea identifier
            textarea = (textarea === 'serendipity[body]') ? 'serendipity_textarea_body' : textarea;
            textarea = (textarea === 'serendipity[extended]') ? 'serendipity_textarea_extended' : textarea;

            // Dispatch payload to target window
            if (targetWindow.serendipity && typeof targetWindow.serendipity.serendipity_imageSelector_addToBody === 'function') {
                targetWindow.serendipity.serendipity_imageSelector_addToBody(galleryHtml, textarea);
            }

            // Reset state and close modal
            pictureSubmit = false;
            this.closeMediaModal();

            // Ensure page scrolling is unlocked and focus returns to the target editor
            setTimeout(() => {
                const targetDoc = targetWindow.document;
                if (targetDoc) {
                    targetDoc.body.style.overflow = '';
                    targetDoc.documentElement.style.overflow = '';

                    const areaEl = targetDoc.getElementById(textarea);
                    if (areaEl && typeof areaEl.focus === 'function') {
                        areaEl.focus();
                    }
                }
            }, 50);

            return false;
        },

        /**
         * Main media insertion handler. Reads form options, constructs formatted HTML output
         * (standard img tag, link-wrapped media, or responsive picture element), persists user preferences,
         * and routes the final payload to the target editor or input field.
         *
         * @param {string} [textarea] - Optional target textarea DOM ID or field identifier.
         */
        serendipity_imageSelector_done: function(textarea) {
            const selForm = document.forms['serendipity[selForm]'];
            if (!selForm) {
                console.error('[Styx] Form serendipity[selForm] not found!');
                return false;
            }
            const f = selForm.elements;

            // --- A. Read Data from the Form ---
            let img       = f['imgName'] ? f['imgName'].value : '';
            let imgWidth  = f['imgWidth'] ? f['imgWidth'].value : '';
            let imgHeight = f['imgHeight'] ? f['imgHeight'].value : '';
            const imgID   = f['imgID'] ? f['imgID'].value : 0;

            const imgAVIFth = f['avifThumbName'] ? f['avifThumbName'].value : '';
            const imgWebPth = f['webPthumbName'] ? f['webPthumbName'].value : '';
            const imgAVIFfu = f['avifFileName'] ? f['avifFileName'].value : '';
            const imgWebPfu = f['webPfileName'] ? f['webPfileName'].value : '';
            const imgAVFrmt = f['srcAvifBestFormatSize'] ? f['srcAvifBestFormatSize'].value : '';

            let imgAVIF = '';
            let imgWebP = '';
            let imgVariFullHref = '';

            // Thumbnail vs. Original checkup
            if (f['serendipity[linkThumbnail]']) {
                if (f['serendipity[linkThumbnail]'][0].checked === true) {
                    img       = f['thumbName'].value;
                    imgWidth  = f['imgThumbWidth'].value;
                    imgHeight = f['imgThumbHeight'].value;
                    imgAVIF   = imgAVIFth;
                    imgWebP   = imgWebPth;
                } else {
                    imgAVIF   = imgAVIFfu;
                    imgWebP   = imgWebPfu;
                }
                imgVariFullHref = (imgAVIFfu !== '' && imgAVFrmt) ? imgAVIFfu : (imgWebPfu !== '' ? imgWebPfu : '');
            }

            // Determine target context: Main window when inside an iframe overlay, otherwise current window
            const targetWindow = (window.parent && window.parent !== window) ? window.parent : window;

            // Special Case: Return Only the File Name/ID (e.g., for category icons)
            if (f['serendipity[filename_only]']) {
                const targetEl = f['serendipity[htmltarget]'] ? f['serendipity[htmltarget]'].value : textarea;
                let val = img;
                if (f['serendipity[filename_only]'].value === 'id') val = imgID;
                if (f['serendipity[filename_only]'].value === 'thumb') val = f['thumbName'].value;
                if (f['serendipity[filename_only]'].value === 'big') val = f['imgName'].value;

                if (targetWindow.serendipity && typeof targetWindow.serendipity.serendipity_imageSelector_addToElement === 'function') {
                    targetWindow.serendipity.serendipity_imageSelector_addToElement(val, targetEl);
                }
                this.closeMediaModal();
                return true;
            }

            // --- B. Build HTML Markup ---
            const altxt    = f['serendipity[alt]'] ? f['serendipity[alt]'].value.replace(/"/g, "&quot;") : '';
            const title    = f['serendipity[title]'] ? f['serendipity[title]'].value.replace(/"/g, "&quot;") : '';
            const isLink   = $(':input[name="serendipity[isLink]"]:checked').val() === "yes";
            const noLink   = $(':input[name="serendipity[isLink]"]:checked').val() === "no";
            const floating = $(':input[name="serendipity[align]"]:checked').val() || "center";

            let html = '';

            if (pictureSubmit) {
                const isWebP = img.split('.').pop().toLowerCase() === 'webp';
                html = '<!-- s9ymdb:'+ imgID +' --><picture>'
                     + (imgAVIF.length > 0 ? '<source type="image/avif" srcset="' + imgAVIF + '">' : '')
                     + (!isWebP && imgWebP.length > 0 ? '<source type="image/webp" srcset="' + imgWebP + '">' : '')
                     + '<img class="serendipity_image_'+ floating +'" width="'+ imgWidth +'" height="'+ imgHeight +'" src="'+ img +'"'+ ((title !== '' && noLink) ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ altxt +'">'
                     + '</picture>';
            } else {
                html = '<!-- s9ymdb:'+ imgID +' --><img class="serendipity_image_'+ floating +'" width="'+ imgWidth +'" height="'+ imgHeight +'" src="'+ img +'"'+ ((title !== '' && noLink) ? ' title="'+ title +'"' : '') +' loading="lazy" alt="'+ altxt +'">';
            }

            // Build the link structure
            if (isLink) {
                const targetval = $('#select_image_target').val();
                const fallback  = (pictureSubmit && imgVariFullHref !== '') ? ' data-fallback="'+ f['serendipity[url]'].value +'"' : '';
                const hasTitle  = title !== '' ? ' title="' + title + '"' : '';
                let sLink       = '<a class="serendipity_image_link"';
                let ilink       = (pictureSubmit && imgVariFullHref !== '' && f['imgName'].value === f['serendipity[url]'].value) ? imgVariFullHref : f['serendipity[url]'].value;
                let itarget     = '';

                switch (targetval) {
                    case 'js':
                        itarget = ' onclick="F1 = window.open(\'' + f['serendipity[url]'].value + '\',\'Zoom\',\'height=' + (parseInt(f['imgHeight'].value) + 15) + ',width=' + (parseInt(f['imgWidth'].value) + 15) + ',top=' + (screen.height - f['imgHeight'].value) / 2 + ',left=' + (screen.width - f['imgWidth'].value) / 2 + ',toolbar=no,menubar=no,location=no,resize=1,resizable=1,scrollbars=yes\'); return false;"';
                        break;
                    case '_blank':
                        itarget = ' rel="noopener" target="_blank"';
                        break;
                    case 'plugin':
                        itarget = ' onclick="javascript:this.href = this.href + \'&amp;serendipity[from]=\' + self.location.href;"';
                        sLink   = sLink + ' id="s9yisphref' + imgID + '"';
                        ilink   = f['baseURL'].value + 'serendipity_admin_image_selector.php?serendipity[step]=showItem&amp;serendipity[image]=' + imgID;
                        break;
                }
                html = sLink + hasTitle + ' href="' + ilink + '"' + itarget + fallback + '>' + html + '</a>';
            }

            // Image Comments (figure / figcaption)
            if ($('#serendipity_imagecomment').val()) {
                const comment = f['serendipity[imagecomment]'].value;
                const ccenter = (floating === 'center' && imgWidth <= 400) ? '; display: block' : '';

                html = '<figure class="serendipity_imageComment_' + floating + '" style="width: ' + imgWidth + 'px' + ccenter + '">'
                     +     '<div class="serendipity_imageComment_img">' + html + '</div>'
                     +     '<figcaption class="serendipity_imageComment_txt">' + comment + '</figcaption>'
                     + '</figure>';
            }

            // RichText / WYSIWYG Wrapper
            if (pictureSubmit && (imgWebPfu !== '' || noLink)) {
                html = '<div>' + html + '</div>';
            }

            // Standardize the name of the target textarea
            textarea = (textarea === 'serendipity[body]') ? 'serendipity_textarea_body' : textarea;
            textarea = (textarea === 'serendipity[extended]') ? 'serendipity_textarea_extended' : textarea;

            // --- C. Transfer to the main window ---
            if (targetWindow.serendipity && typeof targetWindow.serendipity.serendipity_imageSelector_addToBody === 'function') {
                targetWindow.serendipity.serendipity_imageSelector_addToBody(html, textarea);
            }

            // --- D. Cleanup ---
            pictureSubmit = false;
            this.closeMediaModal();
            return false;
        },

        /**
         * Inserts generated media HTML code directly into a target WYSIWYG editor (e.g. TinyMCE)
         * or at the current cursor position of a plain textarea. Automatically closes the media modal.
         *
         * @param {string} html - The formatted HTML string (e.g. <img> or <picture> tag) to insert.
         * @param {string} [textarea='serendipity_textarea_body'] - Optional target element ID or field key.
         */
        serendipity_imageSelector_addToBody: function(html, textarea) {
            const targetId = textarea || 'serendipity_textarea_body';

            if (!html || html.trim() === '') return;

            // A. RichText Editor Check & Insert
            if (typeof tinymce !== 'undefined') {
                const ed = tinymce.get(targetId)
                      || tinymce.get('serendipity[' + targetId.replace('serendipity_textarea_', '') + ']')
                      || tinymce.get(targetId.replace('serendipity_textarea_', ''))
                      || tinymce.activeEditor;

                if (ed && !ed.isHidden()) {
                    ed.execCommand('mceInsertContent', false, html);
                    this.closeMediaModal();
                    return;
                }
            }

            // B. Plain Textarea Fallback
            let $el = $('#' + targetId);
            if (!$el.length) {
                const rawName = targetId.replace('serendipity_textarea_', '');
                $el = $('textarea[name="serendipity[' + rawName + ']"], textarea[name="' + targetId + '"]');
            }

            if ($el.length) {
                const el = $el.get(0);
                const startPos = el.selectionStart || 0;
                const endPos = el.selectionEnd || 0;
                el.value = el.value.substring(0, startPos) + html + el.value.substring(endPos);
                $el.trigger('change');

                this.closeMediaModal();
                return;
            }
        },

        /**
         * Populates a simple target input field with the selected image path or value.
         * Used for category icons and basic media assignment elements.
         *
         * @param {string} inp - The selected image path or string payload to insert.
         * @param {string} htmltarget - The DOM ID of the target input element (without leading #).
         */
        serendipity_imageSelector_addToElement: function(inp, htmltarget) {
            const $el = $('#' + htmltarget);
            if ($el.length) {
                $el.val(inp);
            }
        },

        /**
         * Universal cleanup and dismissal handler for media selector overlays.
         * Closes only the topmost active modal (child/sibling) without destroying parent modals.
         */
        closeMediaModal: function() {
            // Target the topmost open <dialog> directly in DOM
            // Native <dialog[open]> allows us to query all active overlays
            const openDialogs = document.querySelectorAll('dialog[open], .styx-modal[open]');

            if (openDialogs.length > 0) {
                // Get the last appended / topmost child dialog
                const topModal = openDialogs[openDialogs.length - 1];

                // If it's a native <dialog> element
                if (typeof topModal.close === 'function') {
                    topModal.close();
                } else {
                    topModal.removeAttribute('open');
                }

                // If StyxModal maintains an internal stack reference
                if (window.StyxModalInstance && typeof window.StyxModalInstance.popStack === 'function') {
                    window.StyxModalInstance.popStack();
                }

                return;
            }

            // Fallback for Iframe / Parent Contexts
            if (window.parent && window.parent !== window && window.parent.serendipity) {
                window.parent.serendipity.closeMediaModal();
                return;
            }
        },

        /* ==============================================================================
         * III. Serendipity Media Library - Tooling API [ 1 to 6 ]
         * ============================================================================== */

        /**
         * Dynamically rescales image width/height inputs while maintaining aspect ratio.
         *
         * @param {string} dimension - The dimension being altered ('width' or 'height').
         * @param {number|string} value - The new numeric value for the targeted dimension.
         */
        rescale(dimension, value) {
            // Return early if aspect ratio lock is unchecked or value is empty
            const keepProportions = $('#resize_keepprops').is(':checked');
            if (!keepProportions || !value) {
                return;
            }

            const $img = $('#serendipityScaleImg');
            const imgWidth = parseFloat($img.attr('data-imgwidth'));
            const imgHeight = parseFloat($img.attr('data-imgheight'));

            // Validate image dimensions from markup
            if (!imgWidth || !imgHeight) {
                return;
            }

            // Ratio = Height / Width (like in origin)
            const ratio = imgHeight / imgWidth;
            const numericVal = parseFloat(value);

            if (isNaN(numericVal) || numericVal <= 0) {
                return;
            }

            const $width = $('#resize_width');
            const $height = $('#resize_height');

            if (dimension === 'width') {
                // Height = Width * (Height / Width)
                $height.val(Math.round(numericVal * ratio));
            } else if (dimension === 'height') {
                // Width = Height * (Width / Height) = Height * (1 / ratio)
                $width.val(Math.round(numericVal * (1 / ratio)));
            }
        },

        /**
         * Prompts the user for a new filename and submits the rename request via AJAX.
         * Displays the server response or error notice in the native StyxModal Engine.
         *
         * @param {number|string} fileId - The database ID of the file (fid).
         * @param {string} fileName - The current filename.
         */
        rename(fileId, fileName) {
            const promptMsg = StyxLang.get('enterNewName', 'Enter the new name for: ');
            const newName = prompt(promptMsg + fileName, fileName);

            if (!newName || newName === fileName) {
                return;
            }

            const token = serendipity.token || $('input[name*="serendipity[token]"]').val() || '';

            $.ajax({
                type: 'POST',
                url: '?serendipity[adminModule]=images' +
                     '&serendipity[adminAction]=rename' +
                     '&serendipity[fid]=' + encodeURIComponent(fileId) +
                     '&serendipity[newname]=' + encodeURIComponent(newName) +
                     '&serendipity[token]=' + encodeURIComponent(token),
                cache: false
            })
            .done((response) => {
                const trimmed = response.trim();
                let contentHtml = '';

                const goText = StyxLang.get('go', 'Go!');
                const backText = StyxLang.get('back', 'Back');
                const renameHeader = StyxLang.get('renameFileHeader', 'Rename this file');

                if (trimmed === '') {
                    const doneText = StyxLang.get('done', 'Done!');
                    contentHtml = `<p>${doneText}</p>` +
                        `<button id="rename_ok" class="button_link state_submit" type="button"> ${goText} </button>`;
                } else if (trimmed.indexOf('error') > -1) {
                    const noticeText = StyxLang.get('renameErrorNotice', 'If you see this message there was probably an error message...');
                    contentHtml = response +
                        '<br><span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' +
                        noticeText +
                        '</span><br><br>' +
                        `<button id="rename_back" class="button_link" type="button"> ${backText} </button>`;
                } else {
                    contentHtml = response + `<br><br><button id="rename_ok" class="button_link state_submit" type="button"> ${goText} </button>`;
                }

                const modalHtml =
                    '<div id="rename_msg" class="white-popup">' +
                    `<h4>${renameHeader}</h4>` +
                    contentHtml +
                    '</div>';

                // StyxModal Engine verwenden:
                StyxModalInstance.open({
                    html: modalHtml
                });
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                const unknownErrorText = StyxLang.get('unknownError', 'Error');
                const statusLabel = StyxLang.get('status', 'Status');
                const backText = StyxLang.get('back', 'Back');
                const renameHeader = StyxLang.get('renameFileHeader', 'Rename this file');

                const statusText = textStatus || unknownErrorText;
                const errorHtml =
                    '<div id="rename_msg" class="white-popup">' +
                    `<h4>${renameHeader}</h4>` +
                    `<p>${statusLabel}: ${statusText}</p>` +
                    `<p>${errorThrown}</p>` +
                    `<button id="rename_back" class="button_link" type="button"> ${backText} </button>` +
                    '</div>';

                StyxModalInstance.open({
                    html: errorHtml
                });
            });
        },

        /**
         * Tri-state confirmation modal for deleting media items or variations.
         *
         * @param {string} message - Localized confirmation help text.
         * @param {string} fileName - Target filename.
         * @param {jQuery} $el - Source jQuery triggering element.
         */
        confirmDialog(message, fileName, $el) {
            const langYes = StyxLang.get('yes', 'Yes');
            const langNo = StyxLang.get('no', 'No');
            const langAbort = StyxLang.get('abortNow', 'Cancel');
            const langHeader = StyxLang.get('deleteFileHeader', 'Delete file');
            const langContinue = StyxLang.get('continue', 'Continue ?');

            let dialogHtml = '<div class="smp-dialog smp-confirm">';
            if (fileName) {
                dialogHtml += `  <h2>${langHeader} "${fileName}"... ${langContinue}</h2>`;
            }
            dialogHtml += `  <div class="smp-dialog-content"><p>${message}</p></div>`;
            dialogHtml += '  <div class="smp-dialog-actions">';
            dialogHtml += `    <button class="smp-btn smp-btn-primary smp-btn-yes state_submit" type="button" role="button" aria-labelledby="ENTER-key"> ${langYes} </button>`;
            dialogHtml += `    <button class="smp-btn smp-btn-default smp-btn-cancel" type="button" role="button" aria-labelledby="ESC-key"> ${langAbort} </button>`;
            dialogHtml += `    <button class="smp-btn smp-btn-secondary smp-btn-no state_submit" type="button" role="button" aria-labelledby="SPACE-key"> ${langNo} </button>`;
            dialogHtml += '  </div>';
            dialogHtml += '</div>';

            StyxModalInstance.open({
                html: dialogHtml
            });

            // Target the document context where the modal element actually resides (top/parent vs local)
            const targetDoc = window.top?.document || window.parent?.document || document;

            // Retrieve the active top-most dialog directly from the target document scope
            const getActiveDialog = () => {
                const dialogs = targetDoc.querySelectorAll('dialog.styx-modal, dialog[open], .styx-modal');
                return dialogs.length ? dialogs[dialogs.length - 1] : null;
            };

            const attachEvents = (activeDialogEl) => {
                if (!activeDialogEl) return;

                const $dialog = $(activeDialogEl, targetDoc);

                const closeAndCleanup = () => {
                    $(document).off('keydown.confirmDialog');
                    $(targetDoc).off('keydown.confirmDialog');

                    if (activeDialogEl && typeof activeDialogEl.close === 'function') {
                        activeDialogEl.close();
                    } else if (window.StyxModalInstance) {
                        StyxModalInstance.close(activeDialogEl);
                    } else {
                        serendipity.closeMediaModal();
                    }
                };

                // Native Event Delegation directly on the top-level dialog DOM node
                activeDialogEl.addEventListener('click', (e) => {
                    const btnCancel = e.target.closest('.smp-btn-cancel, .styx-btn-cancel');
                    const btnYes    = e.target.closest('.smp-btn-yes, .styx-btn-yes');
                    const btnNo     = e.target.closest('.smp-btn-no, .styx-btn-no');

                    if (btnCancel) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeAndCleanup();
                    } else if (btnYes) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeAndCleanup();
                        serendipity.deleteFromML(
                            $el.attr('data-fileid'),
                            $el.attr('data-filename'),
                            'doDelete',
                            $el.attr('data-getpage')
                        );
                    } else if (btnNo) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeAndCleanup();

                        const confirmMsg = StyxLang.get('deleteConfirmVariations', 'Really delete all variations of this image?');
                        if (confirm(confirmMsg)) {
                            serendipity.deleteFromML(
                                $el.attr('data-fileid'),
                                '.v/' + $el.attr('data-filename'),
                                'doDeleteVariations',
                                $el.attr('data-getpage')
                            );
                        }
                    }
                });

                // Keyboard handlers (ENTER / SPACE / ESC)
                const keydownHandler = (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        $dialog.find('.smp-btn-yes, .styx-btn-yes').first().trigger('click');
                    } else if (e.key === ' ' || e.key === 'Spacebar') {
                        e.preventDefault();
                        $dialog.find('.smp-btn-no, .styx-btn-no').first().trigger('click');
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        $dialog.find('.smp-btn-cancel, .styx-btn-cancel').first().trigger('click');
                    }
                };

                $(document).off('keydown.confirmDialog').on('keydown.confirmDialog', keydownHandler);
                $(targetDoc).off('keydown.confirmDialog').on('keydown.confirmDialog', keydownHandler);
            };

            // Immediate execution with microtask fallback
            let dialogEl = getActiveDialog();
            if (dialogEl) {
                attachEvents(dialogEl);
            } else {
                setTimeout(() => {
                    attachEvents(getActiveDialog());
                }, 0);
            }
        },

        /**
         * Submits deletion requests via AJAX and renders the backend response in StyxModal.
         *
         * @param {number|string} fileId - Target database ID.
         * @param {string} fileName - Target filename or variation string (.v/...).
         * @param {string} action - Deletion action type ('doDelete' or 'doDeleteVariations').
         * @param {number|string} getPage - Optional pagination state.
         */
        deleteFromML(fileId, fileName, action, getPage) {
            const token = serendipity.token || $('input[name*="serendipity[token]"]').val() || '';
            const pageParam = getPage ? '&serendipity[page]=' + encodeURIComponent(getPage) : '';

            const goText = StyxLang.get('go', 'Go!');

            $.ajax({
                type: 'POST',
                url: '?serendipity[adminModule]=images' +
                     '&serendipity[adminAction]=' + encodeURIComponent(action) +
                     '&serendipity[fid]=' + encodeURIComponent(fileId) +
                     '&serendipity[file]=' + encodeURIComponent(fileName) +
                     '&serendipity[token]=' + encodeURIComponent(token) +
                     pageParam,
                cache: false
            })
            .done((response) => {
                const modalHtml =
                    '<div id="delete_result_msg" class="white-popup">' +
                    response +
                    '<br><br><button id="delete_result_ok" class="button_link state_submit" type="button">' +
                    goText +
                    '</button>' +
                    '</div>';

                StyxModalInstance.open({
                    html: modalHtml
                });
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                const unknownErrorText = StyxLang.get('unknownError', 'Error');
                const statusLabel = StyxLang.get('status', 'Status');

                const statusText = textStatus || unknownErrorText;
                const errorHtml =
                    '<div id="delete_result_msg" class="white-popup">' +
                    `<p>${statusLabel}: ${statusText}</p>` +
                    `<p>${errorThrown}</p>` +
                    `<button id="delete_result_ok" class="button_link state_submit" type="button">${goText}</button>` +
                    '</div>';

                StyxModalInstance.open({
                    html: errorHtml
                });
            });
        },

        /**
         * Triggers the generation of additional image variations for a single media item via AJAX.
         * Displays the server response or error notice in a StyxModal dialog.
         *
         * @param {number|string} id - The database ID of the file (fid).
         * @param {string} fname - The filename of the target media item.
         * @param {number|string|null} [page=null] - Optional pagination index for redirect after action.
         */
        addVariationsPerItem(id, fname, page = null) {
            const headline = StyxLang.get('mediaCreateVars', 'Add additional image variations');

            const token = serendipity.token
                || $('input[name*="serendipity[token]"]').val()
                || '';

            // Exact URL from the original
            const postUrl = '?serendipity[adminModule]=images' +
                '&serendipity[adminAction]=variations' +
                '&serendipity[fid]=' + encodeURIComponent(id) +
                '&serendipity[token]=' + encodeURIComponent(token);

            // Helper to blend out the spinner
            const stopLoadingSpinners = () => {
                $('.media_file_preview.dimdark').removeClass('dimdark');
                $('.pulsator').hide().attr('aria-hidden', 'true');
            };

            $.post(postUrl)
                .done((data, textStatus) => {
                    stopLoadingSpinners();

                    if (textStatus === 'success') {
                        const goText = StyxLang.get('go', 'Go!');
                        const modalContent =
                            '<div id="addvar_msg" class="white-popup">' +
                            `<h4>${headline}</h4>` +
                            `<div class="addvar_response">${data}</div>` +
                            `<br><br><button id="addvar_ok" class="button_link state_submit" type="button" data-page="${page ?? ''}"> ${goText} </button>` +
                            '</div>';

                        StyxModalInstance.open({
                            html: modalContent
                        });
                    }
                })
                .fail((jqXHR, textStatus, errorThrown) => {
                    stopLoadingSpinners();

                    if (textStatus != null) {
                        const unknownErrorText = StyxLang.get('unknownError', 'Error');
                        const statusLabel = StyxLang.get('status', 'Status');
                        const goText = StyxLang.get('go', 'Go!');

                        const statusText = textStatus || unknownErrorText;
                        const errorContent =
                            '<div id="addvar_msg" class="white-popup">' +
                            `<h4>${headline}</h4>` +
                            `<p>${statusLabel}: ${statusText}</p>` +
                            `<p>${errorThrown}</p>` +
                            `<button id="addvar_error" class="button_link state_submit" type="button" data-page="${page ?? ''}"> ${goText} </button>` +
                            '</div>';

                        StyxModalInstance.open({
                            html: errorContent
                        });
                    }
                });
        },

        /**
         * Forces a cache-bypassing reload for an image element, its responsive srcset siblings,
         * and the corresponding fullsize view action link by appending a dynamic timestamp query.
         *
         * @param {jQuery|HTMLElement} img - The image element to refresh.
         */
        reloadImage(img) {
            const $img = $(img);
            if (!$img.length) {
                return;
            }

            const cacheBuster = Date.now();

            // 1. Update main image src
            const currentSrc = $img.attr('src');
            if (currentSrc) {
                const cleanSrc = currentSrc.split('?')[0];
                $img.attr('src', `${cleanSrc}?${cacheBuster}`);
            }

            // 2. Update responsive siblings (e.g. <source> tags or variations with srcset)
            $img.siblings().each(function() {
                const $sibling = $(this);
                const currentSrcset = $sibling.attr('srcset');
                if (currentSrcset) {
                    const cleanSrcset = currentSrcset.split('?')[0];
                    $sibling.attr('srcset', `${cleanSrcset}?${cacheBuster}`);
                }
            });

            // 3. Update the fullsize view link in media action list
            const $fullsizeLink = $img.closest('article').find('> ul.media_file_actions .media_fullsize');
            if ($fullsizeLink.length) {
                const currentHref = $fullsizeLink.attr('href');
                if (currentHref) {
                    const cleanHref = currentHref.split('?')[0];
                    $fullsizeLink.attr('href', `${cleanHref}?${cacheBuster}`);
                }
            }
        },

        /* ==============================================================================
         * Serendipity Media Library ↑ API SECTIONS END
         * ============================================================================== */

        // --- UI Filters, Scale blocks by viewport helpers - Media & Theme Lists - REMEMBERED ---

        /**
         * Generic grid column switcher for Media Library and Theme items.
         * @private
         */
        _changeGrid(itemSelector, validClasses, newColClass, cookieName) {
            const elements = document.querySelectorAll(itemSelector);
            if (!elements.length) return;

            elements.forEach(el => {
                el.classList.remove(...validClasses);
                el.classList.add(newColClass);
            });

            StyxCookie.set(cookieName, newColClass);
        },

        /**
         * Changes the Media Library grid column layout (2, 3, or 4 columns).
         * @param {string} col - Target CSS class ('mlDefCol', 'mlMidCol', 'mlMaxCol').
         */
        changeMediaGrid(col) {
            this._changeGrid('.media_file', ['mlMaxCol', 'mlMidCol', 'mlDefCol'], col, 'media_grid');
        },

        /**
         * Changes the Theme list grid column layout (2, 3, or 4 columns).
         * @param {string} col - Target CSS class ('tmMaxCol', 'tmMidCol', 'tmDefCol').
         */
        changeThemeGrid(col) {
            this._changeGrid('.theme_file', ['tmMaxCol', 'tmMidCol', 'tmDefCol'], col, 'theme_grid');
        },

        // --- Selection & Checkbox Helpers ---

        /**
         * Inverts the checked state of all batch selection checkboxes (.multicheck).
         * Syncs UI highlighting and fires change events for dependent UI counters.
         */
        invertSelection() {
            // Target checkboxes in multi-select forms or fall back to general multicheck class
            const $boxes = $('#formMultiSelect .multicheck').length
                ? $('#formMultiSelect .multicheck')
                : $('.multicheck');

            $boxes.each(function () {
                const $box = $(this);
                const newState = !$box.prop('checked');

                // Set property directly
                $box.prop('checked', newState);

                // Sync ARIA state on checkbox itself
                $box.attr('aria-checked', newState ? 'true' : 'false');

                // Optional: Sync comment/media item row highlight if callback exists
                const multixid = $box.attr('data-multixid');
                if (multixid && typeof serendipity.highlightComment === 'function') {
                    serendipity.highlightComment(multixid, newState);
                }

                // Trigger change event so modern UI listeners (e.g. selection counters) react
                $box.trigger('change');
            });
        },

        /**
         * Highlights or de-highlights list items (comments, media items, entries)
         * when their corresponding selection checkbox (.multicheck) is toggled.
         *
         * @param {string} id - Target element ID or multixid reference.
         * @param {boolean} [checked] - Explicit target state (true = highlighted, false = normal).
         */
        highlightComment(id, checked) {
            if (!id) return;

            // Resolve element: supports direct ID ('comment_12') or raw ID ('12')
            let $target = $('#' + id);
            if (!$target.length && !id.startsWith('comment_')) {
                $target = $('#comment_' + id);
            }

            if ($target.length) {
                // If checked state is provided, use boolean; otherwise toggle based on current state
                const isSelected = (typeof checked !== 'undefined') ? Boolean(checked) : !$target.hasClass('multidel_selected');

                // Set both legacy and modern highlight classes for compatibility
                $target.toggleClass('multidel_selected', isSelected)
                       .toggleClass('selected', isSelected);

                // Sync ARIA state on the row container if applicable
                $target.attr('aria-selected', isSelected ? 'true' : 'false');
            }
        },

        // --- Live Filtering Helpers ---

        /**
         * Filters a set of DOM elements based on user input text match.
         *
         * @param {jQuery|HTMLElement|string} input - The input field element.
         * @param {string} containerSelector - Selector for items to show/hide.
         * @param {string} targetSelector - Selector inside item to match text against.
         */
        liveFilters(input, containerSelector, targetSelector) {
            const $input = $(input);
            const term = ($input.val() || '').toLowerCase().trim();
            const $containers = $(containerSelector);

            // Quick bypass for empty filter string: show all items immediately
            if (term === '') {
                $containers.show();
                return;
            }

            $containers.each(function () {
                const $item = $(this);
                const $target = targetSelector ? $item.find(targetSelector) : $item;
                const text = $target.text().toLowerCase();

                $item.toggle(text.includes(term));
            });
        },

        /**
         * Hides or shows section headers (e.g., h3) based on whether their following list contains visible items.
         *
         * @param {string} headerSelector - Selector for the section headers.
         */
        liveFiltersHeader(headerSelector) {
            $(headerSelector).each(function () {
                const $header = $(this);
                // Find next list sibling (ul or ol)
                const $nextList = $header.next('ul, ol');

                if ($nextList.length) {
                    // Header is visible only if at least one child element is currently visible
                    const hasVisibleItems = $nextList.children(':visible').length > 0;
                    $header.toggle(hasVisibleItems);
                }
            });
        },

        /**
         * Generic collapsible section toggle with icon switching, ARIA sync, and optional localStorage persistence.
         *
         * @param {jQuery|HTMLElement|string} toggler - The trigger button or link.
         * @param {jQuery|HTMLElement|string} [target] - Target element to toggle. If omitted, resolved via href/data-href.
         * @param {string} [stateClass='additional_info'] - CSS class to toggle on target.
         * @param {string} [stateIcon='> span'] - Selector inside toggler to find icon span.
         * @param {string} [stateOpen] - Icon class when section is open.
         * @param {string} [stateClosed='icon-right-dir'] - Icon class when section is closed.
         */
        toggle_collapsible(toggler, target, stateClass = 'additional_info', stateIcon = '> span', stateOpen, stateClosed = 'icon-right-dir') {
            const $toggler = $(toggler);
            if (!$toggler.length) return;

            // Resolve target if not explicitly passed
            const targetSelector = target || $toggler.attr('href') || $toggler.data('href');
            if (!targetSelector || targetSelector === '#') return;

            const $target = $(targetSelector);
            if (!$target.length) return;

            // Determine default open icon based on element class context
            if (!stateOpen) {
                const className = $toggler.attr('class') || '';
                stateOpen = className.startsWith('button_link toggle_comment_full') ? 'icon-up-dir' : 'icon-down-dir';
            }

            const $toggleIcon = $toggler.find(stateIcon);
            const isCurrentlyOpen = $toggleIcon.hasClass(stateOpen);

            // Special case: Single comment summary toggle (e.g. #c197_full -> #c197_summary)
            const tgData = $toggler.data('href') || $toggler.attr('href') || '';
            if (/#c[0-9]+_full/.test(tgData)) {
                const summarySelector = tgData.replace('_full', '_summary');
                $(summarySelector).toggleClass(stateClass);
            }

            // Perform toggle state switch
            const togglerId = $toggler.attr('id');
            const storageKey = togglerId ? `show_${togglerId}` : null;

            if (isCurrentlyOpen) {
                $toggler.removeClass('active');
                $toggleIcon.removeClass(stateOpen).addClass(stateClosed);
                if (storageKey && window.localStorage) {
                    localStorage.setItem(storageKey, 'false');
                }
            } else {
                $toggler.addClass('active');
                $toggleIcon.removeClass(stateClosed).addClass(stateOpen);
                if (storageKey && window.localStorage) {
                    localStorage.setItem(storageKey, 'true');
                }
            }

            // Toggle main target visibility class
            const isHiddenNow = $target.toggleClass(stateClass).hasClass(stateClass);

            // Sync ARIA state
            $toggler.attr('aria-expanded', !isHiddenNow ? 'true' : 'false');
            $target.attr('aria-hidden', isHiddenNow ? 'true' : 'false');
        },

        // --- Plugin Batch Update Engine ---

        /**
         * Initializes the batch plugin update overlay and triggers sequential AJAX updates.
         */
        updateAll() {
            const $overlay = $('<div id="overlay" />');

            $.get('?serendipity[adminModule]=plugins&serendipity[adminAction]=renderOverlay')
                .done(function (data) {
                    $overlay.append(data);
                    $overlay.appendTo(document.body);

                    $('#updateProgress').attr('max', $('.plugin_status').length);
                    serendipity.updateNext();
                })
                .fail(function (xhr) {
                    if (xhr && xhr.responseText) {
                        $('#content').prepend(xhr.responseText);
                    }
                });
        },

        /**
         * Sequentially executes AJAX update calls for pending plugin upgrades,
         * updates the progress bar/message, and cleans up cookies upon completion.
         */
        updateNext() {
            const $nextPlugin = $('.plugins_installable > li:visible').first();
            const $nextButton = $('.plugin_status .button_link:visible').first();

            // If no more visible plugins to update, clean up and redirect
            if (!$nextPlugin.length || !$nextButton.length) {
                $('#overlay').fadeOut('normal', function () {
                    const backUrl = $('#back').attr('href') || '?serendipity[adminModule]=plugins';

                    // Purge update-check cookies via StyxCookie or legacy PurgeCookie fallback
                    if (window.StyxCookie && typeof StyxCookie.remove === 'function') {
                        StyxCookie.remove('plugsEvent');
                        StyxCookie.remove('plugsPlugin');
                        StyxCookie.remove('plugsCheckTime');
                    } else if (typeof serendipity.PurgeCookie === 'function') {
                        serendipity.PurgeCookie('plugsEvent');
                        serendipity.PurgeCookie('plugsPlugin');
                        serendipity.PurgeCookie('plugsCheckTime');
                    }

                    window.location = backUrl + '&serendipity[updateAllMsg]=true';
                });
                return;
            }

            // Update UI progress indicator
            const pluginTitle = $nextPlugin.find('h4').text();
            $('#updateMessage').text('Updating ' + pluginTitle);

            // Execute AJAX update call
            $.get($nextButton.attr('href'))
                .done(function () {
                    $nextPlugin.fadeOut('fast', function () {
                        const currentVal = parseInt($('#updateProgress').attr('value') || 0, 10);
                        $('#updateProgress').attr('value', currentVal + 1);

                        // Process next plugin recursively
                        serendipity.updateNext();
                    });
                })
                .fail(function (xhr) {
                    if (xhr && xhr.responseText) {
                        $('#content').prepend(xhr.responseText);
                    }
                    $('#updateAll').hide();
                    $('#overlay').fadeOut();
                });
        },

    });



    /* ==========================================================
     * PART 2: DOM Ready Initialization (jQuery Event Bindings)
     * ========================================================== */

    /* ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••
     * INDEX:
     * P2a: SETUP / INITIALIZATION ON PAGE LOAD
     *      Grouped [1-5]
     *      - Setup Helpers & Feature Detection
     *      - DOM Cloning & Layout Adjustments
     *      - Dynamic Features & Deep-Link Handlers
     *      - Dashboard Sidebar Hook Accordions & State Persistence
     *      - Auto-initialize if overlay is present on page load
     *
     * P2b:  EVENT DELEGATION BINDINGS
     *      Grouped [1-6]
     *      - G1. MEDIA SUBSYSTEM
     *          SUB :: UPLOAD RELATED
     *          SUB :: TOOLBAR ACTIONS
     *      - G2. ENTRIES SUBSYSTEM
     *           SUB :: CATEGORIES
     *           SUB :: FREETAGS
     *           SUB :: MEDIA
     *      - G3. COMMENTS SUBSYSTEM
     *      - G4. STARTPAGE & DASHBOARD SUBSYSTEM
     *      - G5. LIVE FILTERS SUBSYSTEM
     *      - G6. SHARED UI UTILITIES
     *           SUB :: RICH TEXT EDITOR INITS
     *           SUB :: FORM SUBMIT LISTENERS
     *           SUB :: COMMENTS & DIALOGS
     *           SUB :: SPINNERS
     *           SUB :: TOGGLES & DRAGS
     *           SUB :: MISCELLANEOUS
     * ••••••••••••••••••••••••••••••••••••••••••••••••••••••••••
     */

    $(function () {
        'use strict';

        // ====================================================================
        // PART 2:
        //      PART 2a: SETUP / INITIALIZATION ON PAGE LOAD
        //               Code triggered immediately once to build/adjust the UI
        // ====================================================================

        // --------------------------------------------------------------------
        // Group 1: Setup Helpers & Feature Detection
        // --------------------------------------------------------------------

        // Helper: Idempotent clone to prevent duplicate elements on AJAX / re-init
        const cloneIfNotExists = function ($source, $target, action, checkClass) {
            if (!$source.length || !$target.length) return;
            if ($target.children(`.${checkClass}`).length > 0) return; // Already cloned

            const $cloned = $source.clone(true);
            $cloned.addClass(checkClass);

            // Strip duplicate IDs from clone for A11y compliance
            $cloned.find('[id]').removeAttr('id');
            if ($cloned.attr('id')) {
                $cloned.removeAttr('id');
            }

            if (action === 'appendTo') {
                $cloned.appendTo($target);
            } else if (action === 'prependTo') {
                $cloned.prependTo($target);
            }
        };

        // Helper: Feature detection for WebP support
        const supportsWebP = () => {
            const elem = document.createElement('canvas');
            if (elem.getContext && elem.getContext('2d')) {
                return elem.toDataURL('image/webp').indexOf('data:image/webp') === 0;
            }
            return false;
        };

        // --------------------------------------------------------------------
        // Group 2: DOM Cloning & Layout Adjustments
        // --------------------------------------------------------------------

        // Clone Form Submit Buttons
        cloneIfNotExists($('#sort_entries > .form_buttons'), $('#filter_entries'), 'appendTo', 'is-cloned');
        cloneIfNotExists($('#media_pane_sort > .form_buttons'), $('#media_pane_filter'), 'appendTo', 'is-cloned');

        // Clone Pagination (Top & Bottom)
        const panes = ['.media_pane', '.comments_pane', '.entries_pane', '.karma_pane'];
        panes.forEach(function (paneSelector) {
            const $pane = $(paneSelector);
            if ($pane.length) {
                const $pagination = $pane.find('.pagination').first();
                cloneIfNotExists($pagination, $pane, 'prependTo', 'is-cloned-pagination');
            }
        });

        // --------------------------------------------------------------------
        // Group 3: Dynamic Features & Deep-Link Handlers
        // --------------------------------------------------------------------

        // MediaDB: Move image directory via Styx Modal
        const $imageMove = $('.image_move');
        if ($imageMove.length) {
            $imageMove.removeClass('hidden');

            $(document).on('click', '.image_move', function(e) {
                e.preventDefault();

                // Fetch content from the inline container #move-popup
                const $targetPopup = $('#move-popup');
                if ($targetPopup.length) {
                    StyxModalInstance.open({
                        html: $targetPopup.html()
                    });
                }
            });
        }

        // Reopen comment detail section after spamblock or moderation action if URL hash is present
        if ($('#serendipity_comments_list').length > 0 && window.location.hash) {
            try {
                const targetId = window.location.hash.replace(/^#/, '');
                if (targetId) {
                    const $target = $(`#${CSS.escape(targetId)}`);
                    if ($target.length) {
                        const $toggleBtn = $target.find('.toggle_info');
                        if ($toggleBtn.length) {
                            $toggleBtn.trigger('click');
                        }
                    }
                }
            } catch (err) {
                // Fallback for invalid hash selector queries
            }
        }


        // --------------------------------------------------------------------
        // Group 4: Dashboard Sidebar Hook Accordions & State Persistence
        // --------------------------------------------------------------------
        const initSidebarHooks = function () {
            const parseBool = (val) => val === 'true';

            const clickhandler = function (e) {
                if (e) e.preventDefault();

                const isExpanded = parseBool(this.getAttribute('aria-expanded'));
                this.setAttribute('aria-expanded', (!isExpanded).toString());

                const pnode = this.closest('ul');
                if (!pnode || !pnode.id) return;

                const safeId = CSS.escape(pnode.id);
                const lex = document.querySelectorAll('#' + safeId + ' .list-flex');
                const first = this.firstElementChild;
                const next = this.lastElementChild;

                if (first && first.className === 'open') {
                    first.className = 'hide';
                    for (let i = 0; i < lex.length; i++) {
                        lex[i].classList.remove('ease-out');
                        lex[i].classList.add('ease-in');
                        lex[i].removeAttribute('style');
                    }
                    try {
                        sessionStorage.setItem('sidebar_expand_' + pnode.id, 'true');
                    } catch (err) {}
                } else if (first) {
                    first.className = 'open';
                    for (let i = 0; i < lex.length; i++) {
                        lex[i].classList.remove('ease-in');
                        lex[i].classList.add('ease-out');
                    }
                    try {
                        sessionStorage.removeItem('sidebar_expand_' + pnode.id);
                    } catch (err) {}
                }

                if (next) {
                    next.className = (next.className === 'open') ? 'hide' : 'open';
                }

                if (['entry_hooks', 'activity_hooks', 'user_hooks'].includes(pnode.id)) {
                    const animIn = document.querySelectorAll('#' + safeId + ' .list-flex.ease-in');
                    const animOut = document.querySelectorAll('#' + safeId + ' .list-flex.ease-out');

                    for (let i = 0; i < animIn.length; i++) {
                        animIn[i].classList.remove('ao');
                        void animIn[i].offsetWidth; // Reflow
                        animIn[i].classList.add('ai');
                    }
                    for (let i = 0; i < animOut.length; i++) {
                        animOut[i].classList.remove('ai');
                        void animOut[i].offsetWidth; // Reflow
                        animOut[i].classList.add('ao');
                    }
                }
            };

            const hooks = ['entry_hooks', 'activity_hooks', 'user_hooks'];
            const activeHooks = [];

            for (let i = 0; i < hooks.length; i++) {
                const hookEl = document.getElementById(hooks[i]);
                if (!hookEl) continue;

                const flexes = hookEl.querySelectorAll('.list-flex').length;
                if (flexes > 1) {
                    activeHooks.push(hooks[i]);
                    const buttons = hookEl.querySelectorAll('.expandable-group > .ex > button');
                    for (let j = 0; j < buttons.length; j++) {
                        buttons[j].addEventListener('click', clickhandler);
                    }
                } else {
                    const nonAnimated = hookEl.querySelectorAll('.list-flex:not(.ease-in,.ease-out)');
                    for (let j = 0; j < nonAnimated.length; j++) {
                        nonAnimated[j].className = 'remain-flex';
                    }
                    const tgrp = hookEl.querySelector('.expandable-group');
                    if (tgrp) {
                        tgrp.remove();
                    }
                }
            }

            for (let i = 0; i < activeHooks.length; i++) {
                const hookEl = document.getElementById(activeHooks[i]);
                if (!hookEl) continue;

                const links = hookEl.querySelectorAll('.list-flex a');
                for (let j = 0; j < links.length; j++) {
                    links[j].addEventListener('click', function () {
                        try {
                            sessionStorage.setItem('sidebar_expand_' + activeHooks[i], 'true');
                        } catch (err) {}
                    });
                }

                const otherLis = hookEl.querySelectorAll('li:not(.list-flex)');
                for (let j = 0; j < otherLis.length; j++) {
                    otherLis[j].addEventListener('click', function () {
                        try {
                            sessionStorage.removeItem('sidebar_expand_' + activeHooks[i]);
                        } catch (err) {}
                    });
                }
            }

            // Restore state from sessionStorage
            for (let i = 0; i < activeHooks.length; i++) {
                try {
                    if (sessionStorage.getItem('sidebar_expand_' + activeHooks[i]) === 'true') {
                        sessionStorage.removeItem('sidebar_expand_' + activeHooks[i]);
                        const btn = document.querySelector('#' + CSS.escape(activeHooks[i]) + ' .expandable-group > .ex > button');
                        if (btn && btn.getAttribute('aria-expanded') !== 'true') {
                            btn.click();
                        }
                    }
                } catch (err) {}
            }

            // Auto-open on active subpages / forms
            for (let i = 0; i < activeHooks.length; i++) {
                const hookEl = document.getElementById(activeHooks[i]);
                if (!hookEl) continue;

                const links = hookEl.querySelectorAll('.list-flex a');
                const mainUrls = [];
                const adminActions = [];

                for (let j = 0; j < links.length; j++) {
                    const href = links[j].href;
                    const mainMatch = href.match(/(serendipity_admin\.php\?[^#]*serendipity\[adminAction\]=[^&#]*)/);
                    if (mainMatch) mainUrls.push(mainMatch[1]);

                    const actionMatch = href.match(/serendipity\[adminAction\]=([^&#]*)/);
                    if (actionMatch) adminActions.push(actionMatch[1]);
                }

                let shouldOpen = false;
                const currentUrl = window.location.href;
                const currentMainMatch = currentUrl.match(/(serendipity_admin\.php\?[^#]*serendipity\[adminAction\]=[^&#]*)/);

                if (currentMainMatch && mainUrls.includes(currentMainMatch[1])) {
                    shouldOpen = true;
                }

                const contentContainer = document.getElementById('content');
                if (contentContainer) {
                    const forms = contentContainer.querySelectorAll('form');
                    for (let j = 0; j < forms.length; j++) {
                        const adminActionInput = forms[j].querySelector('input[name="serendipity[adminAction]"]');
                        if (adminActionInput && adminActions.includes(adminActionInput.value)) {
                            shouldOpen = true;
                        }
                    }
                }

                if (shouldOpen) {
                    const btn = document.querySelector('#' + CSS.escape(activeHooks[i]) + ' .expandable-group > .ex > button');
                    if (btn && btn.getAttribute('aria-expanded') !== 'true') {
                        btn.click();
                    }
                }
            }
        };

        // Initialize initPreviewIframe on DOM ready
        serendipity.initPreviewIframe();

        // Initialize sidebar hooks on DOM ready
        initSidebarHooks();

        // Group 5
        // Auto-initialize StyxProgress if overlay is present on page load
        if (document.getElementById('progressWidget')) {
            window.StyxProgress.init();
        }

        // ====================================================================
        // PART 2:
        //      2b. EVENT DELEGATION BINDINGS
        //          Listeners attached to $(document) for dynamic content
        // ====================================================================


        /* ==========================================================================
        G1. MEDIA SUBSYSTEM
          (Processing, Upload, Rebuild Cache, Manipulation, Lightbox, Aspect Ratios,
          Rotate, Canvas Resize, WebP)
          SUB :: UPLOAD RELATED
          SUB :: TOOLBAR ACTIONS
        ========================================================================== */

        // Handle target directory selection submission inside move popup
        $(document).on('submit', '#move-popup form, dialog #move-popup form', function(e) {
            e.preventDefault();
            const $form = $(this);
            const selectedDir = $form.find('select').val();

            // Set selected directory into main hidden form field
            $('#newDir').val(selectedDir);

            // Close modern Styx Modal instance
            StyxModalInstance.close();

            // Trigger main move execution input
            const $toggleMoveBtn = $('input[name="toggle_move"]');
            if ($toggleMoveBtn.length) {
                $toggleMoveBtn.trigger('click');
            }
        });

        // MediaDB category filter instant submit
        $(document).on('change', 'input[name="serendipity[filter][fileCategory]"]', function () {
            const $form = $('#media_library_control');
            if ($form.length) {
                // Trigger spinner if present
                const $spinner = $('#waitingspin');
                if ($spinner.length) {
                    $spinner.show().attr('aria-hidden', 'false');
                }
                $form.submit();
            }
        });

        // Media Library input selection triggers (single checkbox change & select all click)
        $(document).on('change', '.check_input', function () {
            if (typeof serendipity.checkInputs === 'function') {
                serendipity.checkInputs();
            }
        });

        $(document).on('click', '.check_inputs', function () {
            if (typeof serendipity.checkInputs === 'function') {
                serendipity.checkInputs();
            }
        });

    // SUB - MEDIA :: UPLOAD RELATED start

        // Media Upload & Download Interface Handling
        if ($('#uploadform').length) {

            // 1. Initialize StyxTabs for Upload / Download Panels
            if (typeof StyxTabs === 'function' && document.getElementById('mediaupload_tabs')) {
                new StyxTabs('#mediaupload_tabs', {
                    tabhead: 'h3',
                    tabbody: '.panel',
                    storageKey: 'styx_mediaupload_tab'
                });
            }

            // 2. Add extra file upload row on "+" button click
            $('#add_upload').off('click.add_field').on('click.add_field', function (e) {
                e.preventDefault();
                serendipity.addUploadField();
            });

            // 3. Save selected directory to cookie & show spinner (Submit & Button Click Fallback)
            $('#uploadform').off('submit.remember').on('submit.remember', function () {
                serendipity.rememberUploadOptions();
            });

            // Fallback: Bind directly to submit buttons inside uploadform
            $('#uploadform input[type="submit"], #uploadform button[type="submit"]').off('click.remember').on('click.remember', function () {
                serendipity.rememberUploadOptions();
            });
        }

        // Media DB Download: Auto-populate target filename from URL input
        $('#imageurl').on('change input', function () {
            const sourceVal = serendipity.getFileName($(this).val());

            if (sourceVal.length > 0) {
                $('#imagefilename').val(sourceVal);
            }
        });

        // Media Library Multi-File Upload UI Flow
        $(document).on('change', '.uploadform_userfile', function () {
            const input = this;
            const $input = $(this);
            const $targetRow = $input.parent().siblings(':first');
            const currentName = $input.attr('name') || '';

            if (input.files && input.files.length > 1) {
                // Multiple selection: hide target filename input and clear value
                $targetRow.fadeOut().find('input').val('');

                // Append [] array suffix if not already present
                if (!currentName.endsWith('[]')) {
                    $input.attr('name', currentName + '[]');
                }
            } else if (input.files && input.files.length === 1) {
                // Single selection: restore target filename input visibility
                $targetRow.fadeIn();

                // Remove [] array suffix if present
                if (currentName.endsWith('[]')) {
                    $input.attr('name', currentName.slice(0, -2));
                }
            }
        });

        // =========================================================================
        // Client-side Image Resizing & AJAX Upload Before Submission
        // (Approach based on javascript-image-upload)
        // =========================================================================
        const uploadResizeEnabled = StyxConfig.get('uploadResize', false);
        const maxImgWidth = parseInt(StyxConfig.get('maxImgWidth', 0), 10);
        const maxImgHeight = parseInt(StyxConfig.get('maxImgHeight', 0), 10);
        const maxImgWidthPortrait = parseInt(StyxConfig.get('maxImgWidthPortrait', 0), 10);

        // Active only if upload resize is enabled AND limits are set
        if (uploadResizeEnabled && (maxImgWidth > 0 || maxImgHeight > 0) && $('#uploadform').length > 0) {

            $('input[name="go_properties"]').hide();

            const progressIcon = document.createElement('span');
            progressIcon.className = 'uploadIcon icon-info-circled';

            const errorIcon = document.createElement('span');
            errorIcon.className = 'uploadIcon icon-attention-circled';

            const successIcon = document.createElement('span');
            successIcon.className = 'uploadIcon icon-ok-circled';

            $('#uploadform').off('submit.image_minify').on('submit.image_minify', function (event) {

                // If fetching via remote image URL, skip client-side file canvas resizing
                if (!$('#imageurl').val()) {
                    event.preventDefault();
                    $('#uploadform .check_inputs').prop('disabled', true);

                    const sendDataToML = function (data, progressContainer, progress) {
                        $.ajax({
                            type: 'post',
                            url: $('#uploadform').attr('action'),
                            data: data,
                            cache: false,
                            processData: false,
                            contentType: false,
                            xhr: function () {
                                const xhr = $.ajaxSettings.xhr();
                                xhr.upload.onprogress = function (e) {
                                    if (e.lengthComputable) {
                                        progress.value = (e.loaded / e.total) * 100;
                                    }
                                };
                                return xhr;
                            }
                        }).done(function () {
                            progress.value = 100;
                            progressContainer.className = "msg_success";
                            $(progressContainer).find('.uploadIcon').replaceWith(successIcon.cloneNode(true));
                            $('#mediaupload_tabs, #imageselectorplus, .form_buttons input.check_inputs').hide();
                        }).fail(function () {
                            progressContainer.className = "msg_error";
                            progress.disabled = true;
                            progressContainer.innerHTML += StyxLang.get('unknownUpload', 'Error uploading file.');
                            $(progressContainer).find('.uploadIcon').replaceWith(errorIcon.cloneNode(true));
                        }).always(function () {
                            if ($('#ml_link').length === 0) {
                                const mlLink = document.createElement('a');
                                mlLink.id = "ml_link";
                                mlLink.className = "button_link";
                                mlLink.href = $('#uploadform').attr('action');
                                mlLink.innerHTML = StyxLang.get('mediaLibrary', 'Media Library');
                                $(mlLink).hide();
                                $('.form_buttons').prepend(mlLink);
                                $(mlLink).fadeIn();
                            }
                            // Stop waiting pulsator spinner after first item finishes
                            $('#waitingspin').css({ 'display': 'none' });
                            $('#uploadform .check_inputs').prop('disabled', false);
                        });
                    };

                    $('.uploadform_userfile').each(function () {
                        const files = this.files;
                        if (!files || !files.length) return;

                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.file = files[i];

                            reader.onload = function (readerEvent) {
                                const image = new Image();
                                const file = this.file;
                                const type = file.type;
                                const data = new FormData();

                                data.append('serendipity[action]', 'admin');
                                data.append('serendipity[adminModule]', 'media');
                                data.append('serendipity[adminAction]', 'add');
                                data.append('serendipity[token]', $('input[name*="serendipity[token]"]').val());
                                data.append('serendipity[target_filename][1]', $('input[name*="serendipity[target_filename][1]"]').val());
                                data.append('serendipity[target_directory][1]', $('select[name*="serendipity[target_directory][1]"]').val());

                                const progress = document.createElement('progress');
                                const progressContainer = document.createElement('span');
                                progressContainer.className = 'msg_notice';
                                progress.max = 100;
                                progress.value = 0;

                                $(progressContainer).append(progressIcon.cloneNode(true));
                                progressContainer.innerHTML += file.name + ": ";
                                $(progressContainer).append(progress);
                                $('.form_buttons').append(progressContainer);

                                if (type.substring(0, 6) === "image/") {
                                    image.onload = function () {
                                        let width = image.width;
                                        let height = image.height;

                                        // Calculate target dimensions via Styx core utility
                                        const finalSize = serendipity.resizeImageCalc(
                                            width,
                                            height,
                                            maxImgWidth,
                                            maxImgHeight,
                                            maxImgWidthPortrait
                                        );

                                        // High-quality canvas stepping down loop (prevents aliasing/jagged edges)
                                        const canvas = document.createElement('canvas');
                                        const ctx = canvas.getContext('2d');
                                        ctx.imageSmoothingEnabled = true;
                                        ctx.imageSmoothingQuality = 'high';

                                        let currSource = image;

                                        while (width > finalSize.width * 2 && height > finalSize.height * 2) {
                                            width = Math.floor(width / 2);
                                            height = Math.floor(height / 2);

                                            const tempCanvas = document.createElement('canvas');
                                            tempCanvas.width = width;
                                            tempCanvas.height = height;
                                            tempCanvas.getContext('2d').drawImage(currSource, 0, 0, width, height);

                                            currSource = tempCanvas;
                                        }

                                        // Final scaling to precise dimensions
                                        canvas.width = finalSize.width;
                                        canvas.height = finalSize.height;
                                        ctx.drawImage(currSource, 0, 0, finalSize.width, finalSize.height);

                                        // Output to Blob and dispatch via AJAX
                                        canvas.toBlob(function (blob) {
                                            data.append('serendipity[userfile][1]', blob, file.name);
                                            sendDataToML(data, progressContainer, progress);
                                        }, type, 0.92);
                                    };

                                    image.src = readerEvent.target.result;
                                } else {
                                    // Non-image files uploaded directly
                                    data.append('serendipity[userfile][1]', file, file.name);
                                    sendDataToML(data, progressContainer, progress);
                                }
                            };

                            reader.readAsDataURL(reader.file);
                        }
                    });
                }
            });
        }

    // SUB - MEDIA :: UPLOAD RELATED end
// todo: ist der nicht filzter ... oder tatsächlich upload?
        // Persist media path selection in cookie across page loads
        $(document).on('change', '#serendipity_only_path', function () {
            const pathValue = $(this).val();
            StyxCookie.set('serendipity_only_path', pathValue);
        });

        // MediaDB: Generate single image variations via [+] icon
        $(document).on('click', '.media_addvar', function(e) {
            e.preventDefault();
            const $el = $(this);

            // Selector adjustment to determine the preview
            const $itemContainer = $el.closest('.media_file, .media_file_detail, .serendipity_mediagrid_item');
            const $preview = $itemContainer.length
                ? $itemContainer.find('.media_file_preview')
                : $el.parent().parent().siblings().find('.media_file_preview');

            if ($preview.length) {
                $preview.addClass('dimdark');
                $preview.find('.pulsator').show().attr('aria-hidden', 'false');
            }

            const fileId = $el.attr('data-fileid');
            const fileName = $el.attr('data-filename');
            const getPage = $el.attr('data-getpage');

            serendipity.addVariationsPerItem(fileId, fileName, getPage);
        });

        // Event Bindings for Image Rescaling
        $('#resize_width').on('input change', function() {
            serendipity.rescale('width', $(this).val());
        });

        $('#resize_height').on('input change', function() {
            serendipity.rescale('height', $(this).val());
        });

        // Delegate file renaming click events
        $(document).on('click', '.media_rename', function(e) {
            e.preventDefault();
            const $el = $(this);
            serendipity.rename($el.attr('data-fileid'), $el.attr('data-filename'));
        });

        // Delegate media deletion click events with localized confirm message
        $(document).on('click', '.media_delete', function(e) {
            e.preventDefault();
            const $el = $(this);

            const confirmMessage = StyxLang.get(
                'deleteConfirmHelp',
                'Yes [ENTER-key] will delete all occurrences of this file; ' +
                'No [SPACE-key] only deletes the image variations (if any), so that they can be rebuilt afterwards via the [+] icon; ' +
                'Cancel [ESC-key] will do nothing! "Yes" and "No" confirmation actions in the following can also be aborted.'
            );

            serendipity.confirmDialog(confirmMessage, $el.attr('data-filename'), $el);
        });

        // MediaDB: Rename Modal Button Actions
        $(document).on('click', '#rename_ok', function() {
            // 1. Resolve current page state from URL or passed attributes
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = $(this).attr('data-page')
                || urlParams.get('serendipity[page]')
                || null;

            // 2. Build clean default redirect URL
            let redirectUrl = '?serendipity[adminModule]=images&serendipity[adminAction]=default';
            if (currentPage) {
                redirectUrl += '&serendipity[page]=' + encodeURIComponent(currentPage);
            }

            // 3. Perform redirect preserving pagination state
            window.parent.parent.location.href = redirectUrl;
        });

        $(document).on('click', '#rename_back', function() {
            StyxModalInstance.close();
        });

        // MediaDB: Close deletion result modal and cleanly reload the view via GET
        $(document).on('click', '#delete_result_ok', function(e) {
            e.preventDefault();
            StyxModalInstance.close();

            // 1. Preserve current pagination state when refreshing after deletion
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = $(this).attr('data-page')
                || urlParams.get('serendipity[page]')
                || null;

            let redirectUrl = '?serendipity[adminModule]=images&serendipity[adminAction]=default';
            if (currentPage) {
                redirectUrl += '&serendipity[page]=' + encodeURIComponent(currentPage);
            }

            // 2. Clean GET redirect to prevent form re-submission while staying on the same page
            window.location.href = redirectUrl;
        });

        // MediaDB: Close variation addition modal and refresh page view
        $(document).on('click', '#addvar_ok, #addvar_error', function(e) {
            e.preventDefault();
            StyxModalInstance.close();

            // 1. Resolve current page from the clicked button or current URL
            const page = $(this).attr('data-page')
                || new URLSearchParams(window.location.search).get('serendipity[page]');

            // 2. Build redirect base URL
            let redirectUrl = '?serendipity[adminModule]=images&serendipity[adminAction]=default';
            if (page) {
                redirectUrl += '&serendipity[page]=' + encodeURIComponent(page);
            }

            // 3. Clean redirect without POST payload re-submission
            if (window.location.search.indexOf('adminAction=images') > -1 || window.location.search.indexOf('adminModule=images') > -1) {
                // Redirect to explicit page URL instead of blind reload to preserve pagination state
                window.location.href = redirectUrl;
            } else {
                window.parent.parent.location.href = redirectUrl;
            }
        });

        // =========================================================================
        // Media Library Image Scaling Confirmation
        // =========================================================================
        $(document).off('click.image_scale', '.image_scale').on('click.image_scale', '.image_scale', function (e) {
            const confirmMsg = StyxLang.get('reallyScaleImage', 'Do you really want to scale this image?');

            if (window.confirm(confirmMsg)) {
                if (document.serendipityScaleForm) {
                    document.serendipityScaleForm.submit();
                } else {
                    $(this).closest('form').submit();
                }
                $('#waitingspin').toggle();
            } else {
                e.preventDefault();
            }
        });

        // =========================================================================
        // Media Library & Theme Preview Lightbox & WebP Fallback
        // =========================================================================
        if ($('.media_fullsize').length > 0) {

            // 1. Native WebP feature detection
            const supportsWebP = () => {
                const elem = document.createElement('canvas');
                if (elem.getContext && elem.getContext('2d')) {
                    return elem.toDataURL('image/webp').indexOf('data:image/webp') === 0;
                }
                return false;
            };

            const hasWebPSupport = supportsWebP();

            // 2. Adjust href to fallback attribute if WebP is unsupported
            if (!hasWebPSupport) {
                $('.media_fullsize').each(function () {
                    const fallback = $(this).data('fallback');
                    if (fallback) {
                        $(this).attr('href', fallback);
                    }
                });
            }

            // 3. Native Styx Modal Overlay Lightbox
            $(document).off('click.media_modal', '.media_fullsize').on('click.media_modal', '.media_fullsize', function (e) {
                e.preventDefault();
                const $el = $(this);

                const imgSrc = (!hasWebPSupport && $el.data('fallback'))
                    ? $el.data('fallback')
                    : $el.attr('href');

                if (!imgSrc) return;

                const imgTitle = $el.attr('title') || $el.attr('aria-label') || '';

                // Build default modal lightbox markup without an initial wrapping <a> tag for relative (+15%) in-size previews
                const lightboxHtml = `
                    <div class="smp-figure media_fullsize_figure">
                        <figure>
                            <img class="smp-img" src="${imgSrc}" alt="${imgTitle}">
                            ${imgTitle ? `
                                <figcaption>
                                    <div class="smp-title">${imgTitle}</div>
                                </figcaption>
                            ` : ''}
                        </figure>
                    </div>
                `;

                const modalEngine = window.StyxModalInstance || window.top?.StyxModalInstance;
                if (modalEngine && typeof modalEngine.open === 'function') {
                    modalEngine.open({
                        html: lightboxHtml
                    });

                    // Target the document context where the modal dialog has been injected
                    const docContext = window.top?.document || document;

                    const checkAndApplyLink = () => {
                        const img = docContext.querySelector('.smp-img');
                        if (!img) return;

                        const evaluate = () => {
                            // Compare actual rendered dimensions in the modal against original image resolution
                            const renderedWidth = img.clientWidth;
                            const renderedHeight = img.clientHeight;
                            const naturalWidth = img.naturalWidth;
                            const naturalHeight = img.naturalHeight;

                            // Scaling threshold: Require natural size to be at least 15% larger than modal preview
                            const threshold = 1.15;
                            const isScaled = (naturalWidth > renderedWidth * threshold) ||
                                             (naturalHeight > renderedHeight * threshold);

                            if (isScaled) {
                                // Apply CSS class to trigger zoom-in cursor
                                img.classList.add('is-scaled');

                                // Dynamically wrap the image inside an <a> tag for direct new-tab viewing
                                if (img.parentElement.tagName !== 'A') {
                                    const a = docContext.createElement('a');
                                    a.href = imgSrc;
                                    a.target = '_blank';
                                    a.rel = 'noopener';
                                    a.title = `Open original in new tab (${naturalWidth}x${naturalHeight}px)`;

                                    img.parentNode.insertBefore(a, img);
                                    a.appendChild(img);
                                }
                            }
                        };

                        if (img.complete && img.naturalWidth > 0) {
                            evaluate();
                        } else {
                            img.addEventListener('load', evaluate, { once: true });
                        }
                    };

                    // Defer evaluation slightly to ensure the modal DOM node is fully painted
                    requestAnimationFrame(() => setTimeout(checkAndApplyLink, 50));
                }
            });
        }

        // Set tooltip hint for media library items with unregistered dimensions
        $('.emptydim.imgctlabel').attr('title', '[i] may contain unregistered media !');

        // Media Action: Image Rotation via AJAX (without global $.ajaxSetup side-effects)
        $(document).on('click', '.media_rotate_right, .media_rotate_left', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const $mediaFile = $btn.closest('.media_file, .media_file_detail, article');

            // Scope lookup via closest container rather than fragile parent chains
            const $preview = $mediaFile.find('.media_file_preview');
            const $image = $mediaFile.find('img');
            const $spinner = $preview.find('.pulsator');

            if ($preview.length) {
                $preview.addClass('dimdark');
            }
            if ($image.length) {
                $image.addClass('media_file_rotate');
            }
            if ($spinner.length) {
                $spinner.show().attr('aria-hidden', 'false');
            }

            $.ajax({
                url: $btn.attr('href'),
                method: 'GET',
                cache: false
            })
            .done(() => {
                serendipity.reloadImage($image);
            })
            .always(() => {
                if ($spinner.length) {
                    $spinner.hide();
                }
                if ($image.length) {
                    $image.removeClass('media_file_rotate');
                }
                if ($preview.length) {
                    $preview.removeClass('dimdark');
                }
            });
        });

/* GROUP 2: +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

        /* ==========================================================================
           G2. ENTRIES SUBSYSTEM
              (Quickfilter, Multi-Select Checkboxes, Bulk Bar, Cookie Clears)
              SUB :: CATEGORIES
              SUB :: FREETAGS
              SUB :: MEDIA
           ========================================================================== */
        // ENTRIES SUBSYSTEM (Live Filtering, Cookie Resets, Selection States)

        // =========================================================================
        // Entry Editor: Autosave / Draft Caching (Fires on Page Load)
        // =========================================================================
        const use_autosave = StyxConfig.get('autosave', false);

        if ($('#serendipityEntry').length > 0 && typeof use_autosave !== 'undefined' && use_autosave) {
            if (typeof serendipity !== 'undefined' && typeof serendipity.startEntryEditorCache === 'function') {
                serendipity.startEntryEditorCache();
            }
        }

        // =========================================================================
        // Entry Editor: Reset Timestamp Handler (Fires on Button Click)
        // =========================================================================
        $(document).off('click.reset_ts', '#reset_timestamp').on('click.reset_ts', '#reset_timestamp', function (e) {
            e.preventDefault();
            const currTime = $(this).attr('data-currtime');

            if (currTime) {
                $('#serendipityNewTimestamp').val(currTime);

                const noticeMsg = StyxLang.get('timestampReset', 'Timestamp reset to current time.');

                if (typeof serendipity !== 'undefined' && typeof serendipity.showStatusNotice === 'function') {
                    serendipity.showStatusNotice(noticeMsg, 'msg_timestamp', '#edit_entry_title');
                }
            }
        });

        // Delegated change handler for pinned entries
        $(document).off('change.pin_filter', '.pinpoint').on('change.pin_filter', '.pinpoint', function (e) {
            const entryId = $(this).data('entry-id') || $(this).val();

            if (entryId && typeof serendipity !== 'undefined' && typeof serendipity.PinFilter === 'function') {
                serendipity.PinFilter(entryId);
            }
        });

        // Add keyword click trigger
        $('.add_keyword').on('click', function (e) {
            e.preventDefault();
            const keyword = $(this).attr('data-keyword');
            if (keyword) {
                serendipity.AddKeyword(keyword);
            }
        });

        // Entry Status Switcher
        $('#switch_entry_status').on('click', function (e) {
            e.preventDefault();
            const $el = $(this);
            const oldState = $el.attr('title');
            const newState = $el.attr('data-title-alt');
            const $options = $('#entry_status option');
            const $stateIcon = $el.find("[class^='icon']");

            $options.each(function () {
                const $opt = $(this);
                $opt.prop('selected', $opt.text() === newState);
            });

            $el.attr({ 'title': newState, 'data-title-alt': oldState })
               .find('> .sr-only').text(newState);

            if ($stateIcon.hasClass('icon-toggle-on')) {
                $stateIcon.removeClass('icon-toggle-on').addClass('icon-toggle-off');
                setTimeout(() => $('.entry_status_stripe').show(), 5000);
            } else {
                $stateIcon.removeClass('icon-toggle-off').addClass('icon-toggle-on');
                $('.entry_status_stripe').hide();
            }

            serendipity.showStatusNotice(`Entry status: ${newState}`, 'msg_entrystatus', '#edit_entry_title');
        });

        // ---- PlainText Editor Selection & Link Handling ----

        // PlainText Link Toolbar Button Binding
        $('.wrap_insurl').off('click.wrap_insurl').on('click.wrap_insurl', function (e) {
            e.preventDefault();
            const targetId = $(this).attr('data-tarea');
            serendipity.wrapSelectionWithLink('#' + targetId);
        });

        // Editor Toolbar Actions
        $('.wrap_selection').on('click', function () {
            const $el = $(this);
            const tagOpen = $el.attr('data-tag-open');
            const tagClose = $el.attr('data-tag-close');
            const $target = $(`#${$el.attr('data-tarea')}`);
            const isHtml = $el.hasClass('lang-html');

            const open = isHtml ? `<${tagOpen}>` : tagOpen;
            const close = isHtml ? `</${tagClose}>` : tagClose;

            serendipity.wrapSelection($target, open, close);
        });

        $('.wrap_insmedia').on('click', function () {
            serendipity.openPopup(`serendipity_admin.php?serendipity[adminModule]=media&serendipity[popupContent]=true&serendipity[showUpload]=true&serendipity[textarea]=${$(this).attr('data-tarea')}`);
        });

        // Quick insert image wrapper for target textarea (Markup / Plain Editor) per browser dialog
        $(document).on('click', '.wrap_insimg', function (e) {
            e.preventDefault();
            const targetId = $(this).attr('data-tarea');
            if (targetId) {
                const $target = $(`#${targetId}`);
                if ($target.length && typeof serendipity.wrapInsImage === 'function') {
                    serendipity.wrapInsImage($target);
                }
            }
        });

        $('.wrap_insgal').on('click', function () {
            serendipity.openPopup(`serendipity_admin.php?serendipity[adminModule]=media&serendipity[popupContent]=true&serendipity[showGallery]=true&serendipity[textarea]=${$(this).attr('data-tarea')}`);
        });

    // G2 SUB - ENTRIES :: CATEGORIES start

        // Category View Switcher (Hierarchical / Compact)
        if ($('#serendipityEntry').length) {
            $('#toggle_cat_view').on('click', function () {
                const $el = $(this);
                const $target = $('.smp-content');
                const isCompact = $target.hasClass('compact_categories');

                $target.toggleClass('compact_categories', !isCompact);
                $el.find('span').toggleClass('icon-th-list', !isCompact).toggleClass('icon-th', isCompact);

                if (window.localStorage) {
                    localStorage.cat_view_state = isCompact ? 'hierarchical' : 'compact';
                }
            });
        }

        // =========================================================================
        // 2. Category Selector Binding
        // =========================================================================
        if ($('#serendipityEntry').length > 0) {

            // ---------------------------------------------------------------------
            // Category View Switcher (Hierarchical vs Compact)
            // Toggles compact view mode on active Dialog or embedded container
            // ---------------------------------------------------------------------
            $(document).off('click.toggle_cat_view', '#toggle_cat_view').on('click.toggle_cat_view', '#toggle_cat_view', function (e) {
                e.preventDefault();
                const $el = $(this);

                // Target either the active open Modal/Dialog or the embedded category container
                const $target = $('dialog[open]').length > 0 ? $('dialog[open]') : $('#edit_entry_category');

                if ($target.hasClass('compact_categories')) {
                    $target.removeClass('compact_categories');
                    $el.find('.icon-th-list, .icon-th').removeClass('icon-th-list').addClass('icon-th');
                    if (window.localStorage) {
                        localStorage.cat_view_state = 'hierarchical';
                    }
                } else {
                    $target.addClass('compact_categories');
                    $el.find('.icon-th, .icon-th-list').removeClass('icon-th').addClass('icon-th-list');
                    if (window.localStorage) {
                        localStorage.cat_view_state = 'compact';
                    }
                }
            });

            // ---------------------------------------------------------------------
            // Category Live Filter
            // Filters category list on keyup (works in Modal & Embedded)
            // ---------------------------------------------------------------------
            $(document).off('keyup.cat_filter', '#categoryfilter').on('keyup.cat_filter', '#categoryfilter', function () {
                if (typeof serendipity.liveFilters === 'function') {
                    serendipity.liveFilters($(this), '#edit_entry_category .form_check', 'label');
                }
            });

            // ---------------------------------------------------------------------
            // Category Selection Helper & State Sync
            // Catches clicks on Labels/Checkboxes, syncs state to hidden DOM, updates UI
            // ---------------------------------------------------------------------
            $(document).off('click.cat_select', '#edit_entry_category label, dialog label')
                       .on('click.cat_select', '#edit_entry_category label, dialog label', function (e) {
                // If label has 'for' attribute, browser handles checkbox toggle natively,
                // but for compact/custom UI we ensure the input state is toggled & synced.
                const $label = $(this);
                const $input = $label.siblings('input[type="checkbox"]');

                if (!$input.length) return;

                // Toggle state (if click directly triggered label without auto-toggling input)
                if (e.target.tagName.toLowerCase() !== 'input') {
                    e.preventDefault();
                    $input.prop('checked', !$input.is(':checked')).trigger('change');
                    return;
                }
            });

            // ---------------------------------------------------------------------
            // Category Selection Helper & State Sync
            // Syncs modal state live to primary hidden form and updates UI summary
            // ---------------------------------------------------------------------
            $(document).off('change.cat_select', '#edit_entry_category input[type="checkbox"], dialog input[type="checkbox"]')
                       .on('change.cat_select', '#edit_entry_category input[type="checkbox"], dialog input[type="checkbox"]', function () {
                const $input = $(this);
                const isChecked = $input.is(':checked');
                const catValue = $input.val();

                // 1. Highlight sibling label in the active element's container
                $input.siblings('label').toggleClass('selected', isChecked);

                // 2. If event originated inside modal dialog, sync state ONLY to the primary hidden form
                if ($input.closest('dialog[open]').length > 0) {
                    const $origInput = $('#meta_data #edit_entry_category input[value="' + catValue + '"]');
                    if ($origInput.length && $origInput.prop('checked') !== isChecked) {
                        $origInput.prop('checked', isChecked);
                        $origInput.siblings('label').toggleClass('selected', isChecked);
                    }
                }

                // 3. Update summary list (#cats_list) live
                if (typeof serendipity.catsList === 'function') {
                    serendipity.catsList();
                }
            });

            // ---------------------------------------------------------------------
            // Category Display Mode Resolver Execution
            // ---------------------------------------------------------------------
            const catDisplayMode = (typeof serendipity.getDisplayMode === 'function')
                ? serendipity.getDisplayMode('categories')
                : (typeof this.getDisplayMode === 'function' ? this.getDisplayMode('categories') : 'inline_modal');

            if (catDisplayMode === 'embedded') {

                // Case 1: Embedded scroll/jump mode, when forced embedding mode is chosen in Personal Preferences
                $('#meta_data #edit_entry_category').removeClass('hidden');
                $('#category_filter a.svg-button_link').removeClass('hidden');

                // Apply initial localStorage view state to embedded form if present
                if (window.localStorage && localStorage.cat_view_state === 'compact') {
                    $('#edit_entry_category').addClass('compact_categories');
                    $('#toggle_cat_view').find('.icon-th').removeClass('icon-th').addClass('icon-th-list');
                }

                $('#select_category').off('click.cats_scroll').on('click.cats_scroll', function (e) {
                    e.preventDefault();
                    if (typeof handleTaxonomyJump === 'function') {
                        handleTaxonomyJump('#select_category', '#edit_entry_category');
                    } else {
                        if ($('#meta_data').hasClass('additional_info')) {
                            $('#toggle_metadata').trigger('click');
                        }
                        serendipity.skipScroll($(this).attr('href'));
                    }
                });

                $('#cats_list').off('click.cats_list').on('click.cats_list', 'h3, li', function (e) {
                    e.preventDefault();
                    $('#select_category').trigger('click');
                });

            } else if (catDisplayMode === 'inline_modal') {

                // Case 2: Styx Modal Overlay Mode when forced embedding mode is disabled in Personal Preferences
                $('#meta_data #edit_entry_category').addClass('hidden');
                $('#category_filter a.svg-button_link').addClass('hidden');

                // Track initial category selection state on modal open to support rollback on cancel
                let initialCategoryState = [];

                $('#select_category').off('click.cats_popup').on('click.cats_popup', function (e) {
                    e.preventDefault();

                    const $catForm = $('#edit_entry_category');
                    if (!$catForm.length) return;

                    // 1. Snapshot currently selected category IDs from primary form before opening
                    initialCategoryState = $('#meta_data #edit_entry_category input[type="checkbox"]:checked')
                        .map(function () { return $(this).val(); })
                        .get();

                    const modalEngine = window.StyxModalInstance || (window.top && window.top.StyxModalInstance);
                    if (modalEngine && typeof modalEngine.open === 'function') {

                        // Recreate MagnificPopup's dynamic closeMarkup behavior
                        const btnText = StyxLang.get('done', 'Done!');
                        const closeBtnMarkup = '<button title="' + btnText + '" class="smp-close button_link" type="button">' + btnText + '</button>';

                        // Wrap content in original #edit_entry_category container and append dynamic popup submit button
                        const wrappedHTML = '<div id="edit_entry_category" class="clearfix">' +
                                            $catForm.html() +
                                            closeBtnMarkup +
                                            '</div>';

                        modalEngine.open({
                            html: wrappedHTML
                        });

                        const $modalDialog = $('dialog[open]');
                        if (!$modalDialog.length) return;

                        $modalDialog.find('.form_check input[type="checkbox"]').attr('aria-hidden', 'true');

                        // 2. Explicitly sync checked property and .selected label class from primary form to modal
                        $modalDialog.find('.form_check input[type="checkbox"]').each(function () {
                            const $modalChk = $(this);
                            const val = $modalChk.val();
                            const isCheckedInForm = initialCategoryState.includes(val);

                            $modalChk.prop('checked', isCheckedInForm);
                            $modalChk.siblings('label').toggleClass('selected', isCheckedInForm);
                        });

                        // Restore saved view preference inside newly rendered Modal
                        if (window.localStorage && localStorage.cat_view_state === 'compact') {
                            $modalDialog.addClass('compact_categories');
                            $modalDialog.find('#toggle_cat_view .icon-th, #toggle_cat_view .icon-th-list')
                                        .removeClass('icon-th')
                                        .addClass('icon-th-list');
                        }

                        // -----------------------------------------------------------------
                        // Dynamically Injected Submit Button Handler (.smp-close / Done!)
                        // Submits selection changes and closes modal with 'submit' status
                        // -----------------------------------------------------------------
                        $modalDialog.off('click.cat_submit', '.smp-close')
                                    .on('click.cat_submit', '.smp-close', function (e) {
                            e.preventDefault();

                            const dialogEl = $modalDialog[0];
                            if (dialogEl && typeof dialogEl.close === 'function') {
                                dialogEl.close('submit');
                            } else if (modalEngine && typeof modalEngine.close === 'function') {
                                dialogEl.returnValue = 'submit';
                                modalEngine.close();
                            }
                        });

                        // -----------------------------------------------------------------
                        // Direct Close Listener (Native HTML5 'close' event does NOT bubble)
                        // -----------------------------------------------------------------
                        $modalDialog.off('close.dialog_cats').on('close.dialog_cats', function () {
                            const dialogEl = this;

                            // Check if dialog was closed/cancelled via Header [X] button, Click Outside, or ESC key
                            const wasCancelled = dialogEl.returnValue !== 'submit' && dialogEl.returnValue !== 'confirm';

                            // ONLY rollback if cancelled. On submit ('Done!'), keep live state changes as they are!
                            if (wasCancelled && Array.isArray(initialCategoryState)) {
                                $('#meta_data #edit_entry_category input[type="checkbox"]').each(function () {
                                    const $chk = $(this);
                                    const val = $chk.val();
                                    const shouldBeChecked = initialCategoryState.includes(val);

                                    $chk.prop('checked', shouldBeChecked);
                                    $chk.siblings('label').toggleClass('selected', shouldBeChecked);
                                });
                            }

                            $('#edit_entry_category .form_check input[type="checkbox"]').attr('aria-hidden', 'false');

                            // Refresh summary list (#cats_list) with final state
                            if (typeof serendipity.catsList === 'function') {
                                serendipity.catsList();
                            }
                        });
                    }
                });

                $('#cats_list').off('click.cats_list').on('click.cats_list', 'h3, li', function (e) {
                    e.preventDefault();
                    $('#select_category').trigger('click');
                });

            }
        }

    // G2 SUB - ENTRIES :: CATEGORIES end

    // G2 SUB - ENTRIES :: MEDIA start

        // Open Media Library selector for standard configuration fields
        $(document).on('click', '.choose_media', function (e) {
            e.preventDefault();
            const $targetInput = $(this).parent().find('.change_preview');
            const configItem = $targetInput.attr('id');

            if (configItem && typeof serendipity.choose_media === 'function') {
                serendipity.choose_media(configItem);
            }
        });

        // Open Media Library selector for entry custom fields (textareas/inputs)
        $(document).on('click', '.customfieldMedia', function (e) {
            e.preventDefault();
            const $targetTextarea = $(this).parent().find('textarea, input');
            const configItem = $targetTextarea.attr('id');

            if (configItem && typeof serendipity.choose_media === 'function') {
                try {
                    serendipity.choose_media(configItem);
                } catch (e) {
                    console.error('Failed to open media chooser:', e);
                }
            }
        });

        // Category selection highlighting & ARIA sync in Entry Editor
        $(document).on('change', '#edit_entry_category .form_check input[type="checkbox"]', function () {
            const $el = $(this);
            const isChecked = $el.is(':checked');

            // Highlight neighboring label
            $el.siblings('label').toggleClass('selected', isChecked);

            // Accessibility: Sync ARIA state on the checkbox itself
            $el.attr('aria-checked', isChecked ? 'true' : 'false');
        });

        // Initial setup for pre-checked categories on page load
        $('#edit_entry_category .form_check input[type="checkbox"]:checked').each(function () {
            $(this).siblings('label').addClass('selected');
        });

        // Entry preview trigger (sets form element serendipity[preview] to true)
        $(document).on('click', '.entry_preview:not(.comment_preview)', function () {
            const form = document.forms['serendipityEntry'];
            if (form && form.elements['serendipity[preview]']) {
                form.elements['serendipity[preview]'].value = 'true';
            }
        });

    // G2 SUB - ENTRIES :: MEDIA end

    // G2 SUB - ENTRIES: FREETAGS

        // =========================================================================
        // Freetags (Tags) Selector Binding
        // =========================================================================
        if ($('#serendipityEntry').length > 0) {
            const tagDisplayMode = serendipity.getDisplayMode('tags');

            if (tagDisplayMode === 'embedded') {

                // Case 1: Native Embedded Mode (Direct Page Interaction)
                $('#adv_opts #edit_entry_freetags').removeClass('hidden');

                $('#select_tags').off('click.tags_scroll').on('click.tags_scroll', function (e) {
                    e.preventDefault();

                    if ($('#adv_opts').hasClass('additional_info')) {
                        $('#toggle_advanced').trigger('click');
                    }

                    serendipity.skipScroll($(this).attr('href'));
                });

                $('#tags_list').off('click.tags_list').on('click.tags_list', 'h3, li', function (e) {
                    e.preventDefault();
                    $('#select_tags').trigger('click');
                });

            } else if (tagDisplayMode === 'inline_modal') {

                // Case 2: Styx Modal Overlay Mode
                $('#adv_opts #edit_entry_freetags').addClass('hidden');

                // Track initial tags state on open to support rollback on cancel
                let initialTagState = '';

                const getMainInput = () => $('#adv_opts #properties_freetag_tagList, #adv_opts #properties_Tags').first();

                $('#select_tags').off('click.tags_popup').on('click.tags_popup', function (e) {
                    e.preventDefault();

                    const $tagForm = $('#edit_entry_freetags');
                    if (!$tagForm.length) return;

                    // 1. Snapshot input value from primary form before opening
                    const $mainInput = getMainInput();
                    initialTagState = $mainInput.length ? $mainInput.val() : '';

                    const modalEngine = window.StyxModalInstance || (window.top && window.top.StyxModalInstance);
                    if (modalEngine && typeof modalEngine.open === 'function') {

                        // Recreate dynamic submit button markup (analogous to categories)
                        const btnText = StyxLang.get('done', 'Done!');
                        const closeBtnMarkup = '<button title="' + btnText + '" class="smp-close button_link" type="button">' + btnText + '</button>';

                        // Wrap content in original #edit_entry_freetags container and append dynamic popup submit button
                        const wrappedHTML = '<div id="edit_entry_freetags" class="clearfix">' +
                                            $tagForm.html() +
                                            closeBtnMarkup +
                                            '</div>';

                        modalEngine.open({
                            html: wrappedHTML
                        });

                        const $modalDialog = $('dialog[open]');
                        if (!$modalDialog.length) return;

                        // Sync initial state to modal's input field
                        const $modalInput = $modalDialog.find('#properties_freetag_tagList, #properties_Tags').first();
                        if ($modalInput.length) {
                            $modalInput.val(initialTagState);
                        }

                        // -----------------------------------------------------------------
                        // Dynamically Injected Submit Button Handler (.smp-close / Done!)
                        // -----------------------------------------------------------------
                        $modalDialog.off('click.tag_submit', '.smp-close')
                                    .on('click.tag_submit', '.smp-close', function (e) {
                            e.preventDefault();

                            const dialogEl = $modalDialog[0];
                            if (dialogEl) {
                                dialogEl.returnValue = 'submit';
                            }

                            // Call modalEngine.close() first so engine cleanup (unlock scrollbar) triggers
                            if (modalEngine && typeof modalEngine.close === 'function') {
                                modalEngine.close();
                            } else if (dialogEl && typeof dialogEl.close === 'function') {
                                dialogEl.close('submit');
                            }
                        });

                        // -----------------------------------------------------------------
                        // Tag Selection from Suggestion Lists (Manual & Auto-Suggest)
                        // -----------------------------------------------------------------
                        $modalDialog.off('click.freetag_select', '#backend_freetag_list a, #properties_freetag_suggested a')
                                    .on('click.freetag_select', '#backend_freetag_list a, #properties_freetag_suggested a', function (e) {
                            e.preventDefault();

                            const clickedTag = $(this).text().trim();
                            const $currentModalInput = $modalDialog.find('#properties_freetag_tagList, #properties_Tags').first();

                            if ($currentModalInput.length) {
                                let currentTags = $currentModalInput.val().split(',').map(t => t.trim()).filter(Boolean);

                                if (!currentTags.includes(clickedTag)) {
                                    currentTags.push(clickedTag);
                                    $currentModalInput.val(currentTags.join(', '));
                                }
                            }
                        });

                        // -----------------------------------------------------------------
                        // Native Add-Tag Button Intercept inside Modal
                        // -----------------------------------------------------------------
                        $modalDialog.off('click.modal_add_tag', 'input[name*="addTag"]')
                                    .on('click.modal_add_tag', 'input[name*="addTag"]', function (e) {
                            // Allows native routine to update input inside modal context
                            setTimeout(() => {
                                const $currentModalInput = $modalDialog.find('#properties_freetag_tagList, #properties_Tags').first();
                                if ($currentModalInput.length && $mainInput.length) {
                                    $mainInput.val($currentModalInput.val());
                                }
                            }, 20);
                        });

                        // -----------------------------------------------------------------
                        // Direct Close Listener (Rollback on ESC / Cancel, Sync on Submit)
                        // -----------------------------------------------------------------
                        $modalDialog.off('close.dialog_tags').on('close.dialog_tags', function () {
                            const dialogEl = this;
                            const wasCancelled = dialogEl.returnValue !== 'submit' && dialogEl.returnValue !== 'confirm';

                            if (wasCancelled) {
                                // Rollback main input to snapshot state
                                if ($mainInput.length) {
                                    $mainInput.val(initialTagState);
                                }
                            } else {
                                // Confirmed ("Done!"): Copy modal input value to main form input
                                const $modalInput = $modalDialog.find('#properties_freetag_tagList, #properties_Tags').first();
                                if ($mainInput.length && $modalInput.length) {
                                    $mainInput.val($modalInput.val());
                                }
                            }

                            // Refresh summary tag list with final state
                            if (typeof serendipity.tagsList === 'function') {
                                serendipity.tagsList();
                            }

                            // Force cleanup: release page scroll lock regardless of how modal was closed
                            $('html, body').removeClass('noscroll dialog-open smp-is-open').css('overflow', '');
                        });
                    }
                });

                $('#tags_list').off('click.tags_list').on('click.tags_list', 'h3, li', function (e) {
                    e.preventDefault();
                    $('#select_tags').trigger('click');
                });

            }
        }

        /* ==========================================================================
           G3. COMMENTS SUBSYSTEM
              (Status Filters, Author Tooltips, Batch Resets, Quick Approve)
              see G6 SUB - SHARED UI UTILITIES :: COMMENTS relation
           ========================================================================== */

        // =========================================================================
        // Comments Management Filters & Dark Mode Code Highlight Tooltips
        // =========================================================================
        const $commentsForm = $('#filter_comments');

        if ($commentsForm.length > 0 || $('.comment_full').length > 0) {

            // Ensure class binding for reset button
            $commentsForm.find('.reset_comment_filters').addClass('reset_filter');

            // Handle filter reset click
            $(document).off('click.reset_comments', '.reset_filter').on('click.reset_comments', '.reset_filter', function (e) {
                // 1. Clear input text fields cleanly
                $('#filter_author, #filter_email, #filter_url, #filter_ip, #filter_body, #filter_referer').val('').attr('value', '');

                // 2. Reset dropdown selects (select first/empty option)
                $('#filter_perpage, #filter_show, #filter_type').each(function() {
                    $(this).find('option').prop('selected', false);
                    $(this).find('option:first').prop('selected', true);
                    $(this).val($(this).find('option:first').val());
                });
            });

            // Trigger reset ONCE when changing filter mode
            $(document).off('click.filter_mode', '.filter_mode').one('click.filter_mode', '.filter_mode', function () {
                $('.reset_filter').trigger('click.reset_comments');
            });

            // Tooltip hint for Dark Mode Prism/Code highlight previews in comments
            if (typeof STYX_DARKMODE !== 'undefined' && STYX_DARKMODE === true) {
                const codeNotice = 'Simple preview only. View this snippets codehighlight color either in Rich Text comment edit form or in your frontend when supported.';
                $('.comment_full').find('code[class^="language-"]').parent('pre').attr('title', codeNotice);
                $('.comment_full').find('pre[class^="language-"]').attr('title', codeNotice);
            }
        }

        // Open comment reply dialog in a popup window
        $(document).on('click', '.comments_reply', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href && typeof serendipity.openPopup === 'function') {
                serendipity.openPopup(href);
            }
        });

        // Close comment reply modal/popup on confirmation button click
        if ($('#comment_replied').length) {
            $('#comment_replied').on('click', function (e) {
                e.preventDefault();
                serendipity.closeCommentPopup();
            });
        }

        /* ==========================================================================
           G4. STARTPAGE & DASHBOARD SUBSYSTEM
              (Sidebar, Integrity Checks, Tickers, Quicktips)
           ========================================================================== */

        // Responsive Mobile Navigation
        if ($('#main_menu').length) {
            $('#nav-toggle').on('click', function (e) {
                e.preventDefault();
                const $body = $('body').toggleClass('active_nav');
                const $icon = $(this).find('span:first-child');

                if ($body.hasClass('active_nav')) {
                    $icon.removeClass('icon-menu').addClass('icon-cancel');
                } else {
                    $icon.removeClass('icon-cancel').addClass('icon-menu');
                }
            });
        }

        // =========================================================================
        // Dashboard Core Checks & Notification Blending (Cookie Managed)
        // =========================================================================
        if ($('#dashboard').length > 0 || $('#dashboard_header').length > 0) {

            // 1. JS Integrity Check
            if (typeof serendipity !== 'object' || typeof serendipity.spawn !== 'function') {
                const failureMsg = StyxLang.get('js_failure', 'JavaScript components failed to load.');

                $('#dashboard_header').after(
                    `<span class="msg_error"><span class="icon-attention-circled"></span> ${failureMsg}</span>`
                );
            }

            // 2. Ticker Blend / Fadeout
            const $ticker = $('#dashboard_ticker');
            if ($ticker.hasClass('blend')) {
                if (StyxCookie.get('styx_tickerBlend')) {
                    $ticker.hide();
                } else {
                    $ticker.delay(5000).fadeOut(2500, 'linear');
                    StyxCookie.set('styx_tickerBlend', 'true', { expires: 1 }); // Multi-prefix & path resilient
                }
            }

            // 3. Plugin Updates Blend / Fadeout
            const $plugup = $('#dashboard_plugup');
            if ($plugup.hasClass('blend')) {
                if (StyxCookie.get('styx_plugupBlend')) {
                    $plugup.hide();
                } else {
                    $plugup.delay(5000).fadeOut(2500, 'linear');
                    StyxCookie.set('styx_plugupBlend', 'true', { expires: 1 });
                }
            }
        }

        // =========================================================================
        // Dashboard Quicktip / Links Toggle
        // =========================================================================
        if ($('#dashboard').length > 0) {
            const linkDisplayMode = serendipity.getDisplayMode('links');

            if (linkDisplayMode === 'embedded') {

                // Case 1: Embedded mode - links toggle directly inline
                $('.toggle_links').off('click.links_scroll').on('click.links_scroll', function (e) {
                    e.preventDefault();

                    $('#s9y_links').toggleClass('hidden');
                    $('#s9y_quicktip').toggleClass('hidden');

                    serendipity.skipScroll($(this).attr('href'));
                });

            } else if (linkDisplayMode === 'inline_modal') {

                // Case 2: Native Styx Modal Overlay Mode
                $('.toggle_links').off('click.links_popup').on('click.links_popup', function (e) {
                    e.preventDefault();

                    const targetId = $(this).attr('href');
                    const $linksContent = $(targetId);

                    if (!$linksContent.length) return;

                    const modalEngine = window.StyxModalInstance || window.top?.StyxModalInstance;
                    if (modalEngine && typeof modalEngine.open === 'function') {
                        modalEngine.open({
                            html: $linksContent.html()
                        });
                    }
                });

            }
        }

        /* ==========================================================================
           G5. LIVE FILTERS SUBSYSTEM
              (Entries, Categories, Plugins)
           ========================================================================== */

        // =========================================================================
        // Entry List Filter & Sort Reset Handlers (Stateful Reset)
        // =========================================================================
        const $filterForm = $('#filter_entries');
        const $sortForm = $('#sort_entries');

        if ($filterForm.length > 0 || $sortForm.length > 0) {

            // Ensure class bindings for reset triggers
            $filterForm.find('.reset_entry_filters').addClass('reset_filter');
            $sortForm.find('.reset_entry_filters').addClass('reset_sort');

            // Reset Filter state & submit form
            $(document).off('click.reset_entry_filter', '.reset_filter').on('click.reset_entry_filter', '.reset_filter', function (e) {
                // Clear input field values
                $('#filter_author, #filter_category').val('');
                $('#filter_draft').val('all');
                $('#filter_content').val('');

                // Invalidate stored filter cookies via StyxCookie
                if (typeof StyxCookie !== 'undefined') {
                    StyxCookie.set('entrylist_filter_author', '');
                    StyxCookie.set('entrylist_filter_isdraft', '');
                    StyxCookie.set('entrylist_filter_category', '');
                    StyxCookie.set('entrylist_filter_body', '');
                }

                // Native submit proceeds via button click (sends entry_filters_reset parameter)
            });

            // Reset Sort state & submit form
            $(document).off('click.reset_entry_sort', '.reset_sort').on('click.reset_entry_sort', '.reset_sort', function (e) {
                // Clear sort dropdowns
                $('#sort_order, #sort_ordermode, #sort_perpage').val('');

                // Invalidate stored sort cookies via StyxCookie
                if (typeof StyxCookie !== 'undefined') {
                    StyxCookie.set('entrylist_sort_order', '');
                    StyxCookie.set('entrylist_sort_ordermode', '');
                    StyxCookie.set('entrylist_sort_perpage', '');
                }

                // Native submit proceeds via button click
            });

            // Quick-reset via active filter badge/icon click
            $(document).off('click.entry_filter_mode', '.filter_mode, .filter_entry').on('click.entry_filter_mode', '.filter_mode, .filter_entry', function (e) {
                e.preventDefault();
                $('.reset_filter').first().trigger('click');
            });
        }

        // =========================================================================
        // Category & Plugin Live Filters
        // =========================================================================

        // Live filter for Category selection in Entry Editor
        $(document).on('input keyup', '#categoryfilter', function () {
            serendipity.liveFilters(this, '#edit_entry_category .form_check', 'label');
        });

        // Live filter for Plugin Installation dialog
        $(document).on('input keyup', '#pluginfilter', function () {
            serendipity.liveFilters(this, '.plugins_installable > li', '.plugin_features');
            serendipity.liveFiltersHeader('#content h3');
        });

        // Reset button for live filter inputs
        $(document).on('click', '.reset_livefilter', function (e) {
            e.preventDefault();
            const targetId = $(this).attr('data-target');
            if (targetId) {
                const $target = $(`#${targetId}`);
                $target.val('').trigger('input').trigger('keyup').focus();
            }
        });


        /* ==========================================================================
           G6. SHARED UI UTILITIES
              (RT-Editor, Collapsibles, StyxSortable, StyxTabs)
              SUB :: RICH TEXT EDITOR INITS
              SUB :: FORM SUBMIT LISTENERS
              SUB :: COMMENTS & DIALOGS
              SUB :: SPINNERS
              SUB :: TOGGLES & DRAGS
              SUB :: MISCELLANEOUS
           ========================================================================== */

    // SUB - SHARED UI UTILITIES :: RICH TEXT EDITOR INITS start

        // =========================================================================
        // Plugin Config WYSIWYG Nuggets Auto-Initialization
        // =========================================================================
        const $nuggetContainer = $('#styx_plugin_nuggets');
        if ($nuggetContainer.length > 0 && typeof window.Spawnnuggets === 'function') {
            try {
                const nuggets = JSON.parse($nuggetContainer.attr('data-nuggets') || '[]');
                nuggets.forEach(function (nuggetId) {
                    window.Spawnnuggets(nuggetId);
                });
            } catch (e) {
                console.error('Failed to initialize plugin nuggets:', e);
            }
        }

        // WYSIWYG & RT Editor Setup
        serendipity.spawn();

        if ($('#serendipityEntry').length) {
            serendipity.catsList();
            serendipity.tagsList();
            serendipity.toggle_category_selector('categoryselector');
            serendipity.toggle_extended();

            if (!window.browserFeatures.dateInput) {
                const $ts = $('#serendipityNewTimestamp');
                $ts.val($ts.val().replace('T', ' '));
            }
        }

    // SUB - SHARED UI UTILITIES :: RICH TEXT EDITOR INITS end

    // SUB - SHARED UI UTILITIES :: FORM SUBMIT LISTENERS start

        // =========================================================================
        // User Management: Relocate Random Password Hint
        // =========================================================================
        const $rpex = $('#rpex');
        const $passwordInfo = $('#password_info');

        if ($rpex.length > 0 && $passwordInfo.length > 0) {
            $rpex.insertAfter($passwordInfo);
        }

        // =========================================================================
        // Template/Theme Grid Initialization (StyxCookie Managed) (from templates.inc.tpl)
        // =========================================================================
        if ($('#template_select, #templates').length > 0) {
            const stcol = StyxCookie.get('theme_grid');
            if (typeof stcol === 'undefined' || stcol === null || stcol === 'undefined') {
                serendipity.changeThemeGrid('tmDefCol');
            } else {
                serendipity.changeThemeGrid(stcol);
            }
        }

        // =========================================================================
        // MediaLibrary Grid Initialization (StyxCookie Managed)  (from media_toolbar.tpl)
        // =========================================================================
        if ($('#media_library, #media_pane_filter').length > 0) {
            const smcol = StyxCookie.get('media_grid');
            if (typeof smcol === 'undefined' || smcol === null) {
                serendipity.changeMediaGrid('mlDefCol');
            } else {
                serendipity.changeMediaGrid(smcol);
            }
        }

        // =========================================================================
        // Media Toolbar Filter & Sort Reset Handlers (from media_toolbar.tpl)
        // =========================================================================
        if ($('#media_pane_filter').length > 0 || $('#media_pane_sort').length > 0) {

            // Ensure class bindings
            $('#media_pane_filter').find('.reset_media_filters').addClass('reset_filter');
            $('#media_pane_sort').find('.reset_media_filters').addClass('reset_sort');

            // Helper: Reset ALL Filter Inputs & Options to Default
            const resetMediaFilters = function ($form) {
                // 1. Text & Date Inputs in Filter Pane
                $('#media_pane_filter').find('input[type="text"], input[type="date"]').val('');

                // 2. Selects in Filter Pane (e.g. Authors dropdown)
                $('#media_pane_filter').find('select').prop('selectedIndex', 0);

                // 3. Keywords Input
                $('#keyword_input').val('');

                // 4. File Category Radio Buttons (Reset to 'All')
                $form.find('input[name="serendipity[filter][fileCategory]"]').prop('checked', false);
                $form.find('input[name="serendipity[filter][fileCategory]"][value="all"]').prop('checked', true);
            };

            // Helper: Reset ALL Sort Controls to Default
            const resetMediaSort = function ($form) {
                // 1. Select Dropdowns in Sort Pane (Order, OrderMode, PerPage)
                $("#serendipity_sortorder_order").prop('selectedIndex', 0);
                $("#serendipity_sortorder_ordermode").val('DESC');
                $("#serendipity_sortorder_perpage").val('8'); // Default or first option

                // 2. Hide Subdirectories Radio (Reset to 'no')
                $form.find('input[name="serendipity[hideSubdirFiles]"]').prop('checked', false);
                $form.find('#radio_link_no').prop('checked', true);

                // 3. Page Input
                $('#select_page').val('');
            };

            // Helper: Safely Submit Form
            const submitForm = function ($form) {
                if ($form.length) {
                    if (typeof $form[0].requestSubmit === 'function') {
                        $form[0].requestSubmit();
                    } else {
                        $form[0].submit();
                    }
                }
            };

            // Reset Media Filter Event
            $(document).off('click.reset_media_filter', '.reset_filter').on('click.reset_media_filter', '.reset_filter', function (e) {
                e.preventDefault();
                const $form = $('#media_library_control').length ? $('#media_library_control') : $(this).closest('form');

                resetMediaFilters($form);
                submitForm($form);
            });

            // Reset Media Sort Event
            $(document).off('click.reset_media_sort', '.reset_sort').on('click.reset_media_sort', '.reset_sort', function (e) {
                e.preventDefault();
                const $form = $('#media_library_control').length ? $('#media_library_control') : $(this).closest('form');

                resetMediaSort($form);
                submitForm($form);
            });

            // Trigger filter reset when changing filter mode tabs
            $(document).off('click.media_filter_mode', '.filter_mode').on('click.media_filter_mode', '.filter_mode', function () {
                $('.reset_filter').trigger('click.reset_media_filter');
            });
        }

        // Media Selector Form submission (Insert image into entry editor)
        $(document).on('submit', '#imageForm', function (e) {
            e.preventDefault();
            serendipity.serendipity_imageSelector_done(this);
        });

        /**
         * Unified Toggle Handler for Info Panels, Filters, and Media/Template Info.
         * Excludes sidebar accordion expanders (.btn-expander) to avoid conflict.
         */
        const infoSelectors = '.media_show_info, .template_show_info, .filters_toolbar li > a[href*="#"], .toggle_info:not(.btn-expander)';

        $(document).on('click', infoSelectors, function (e) {
            e.preventDefault();

            const $el = $(this);
            const targetSelector = $el.attr('href') || $el.data('href');

            if (!targetSelector || targetSelector === '#') {
                return;
            }

            // Check whether the click occurs within an open dialog or modal
            const $modalDialog = $el.closest('dialog[open], .smp-wrap');
            let $target;

            if ($modalDialog.length) {
                // If in the modal: Search strictly WITHIN the modal!
                $target = $modalDialog.find(targetSelector);
            } else {
                // Otherwise, perform a normal global search
                $target = $(targetSelector);
            }

            if ($target.length) {
                // 1. Toggle main visibility class & capture state
                const isExpanded = $target.toggleClass('additional_info').hasClass('additional_info');

                // 2. Accessibility: Sync ARIA attributes
                $el.attr('aria-expanded', isExpanded ? 'true' : 'false');
                $target.attr('aria-hidden', isExpanded ? 'false' : 'true');

                // 3. Comment pingback offset edge case
                const $pingback = $el.parent().siblings('div.comment_type.pingback');
                if ($pingback.length) {
                    $pingback.toggleClass('pingup', isExpanded);
                }

                // 4. Mobile view breakpoint state
                if (typeof mq_small !== 'undefined' && mq_small) {
                    $el.closest('.has_info').toggleClass('info_expanded', isExpanded);
                }

                // 5. Active state on trigger button/link
                $el.toggleClass('active', isExpanded);
            }
        });

        // --- Theme / Plugin / Custom Field Media Selectors & Previews ---

        // Update image preview thumbnail when configuration media input changes
        $(document).on('change', '.change_preview', function () {
            const $el = $(this);
            const id = $el.attr('id');
            const configItem = $el.attr('data-configitem');

            if (id && typeof serendipity.change_preview === 'function') {
                serendipity.change_preview(id, configItem);
            }
        });

        // Invert checkboxes batch selection (Media Library / Comments / Entry lists)
        $(document).on('click', '.invert_selection', function (e) {
            e.preventDefault();
            serendipity.invertSelection();
        });

        // Sync row highlighting when a multi-selection checkbox is clicked
        $(document).on('click change', '.multicheck', function () {
            const $el = $(this);
            const multixid = $el.attr('data-multixid') || $el.attr('id');

            if (multixid) {
                serendipity.highlightComment(multixid, $el.prop('checked'));
            }
        });

    // SUB - SHARED UI UTILITIES :: FORM SUBMIT LISTENERS end

    // SUB - SHARED UI UTILITIES :: COMMENTS & DIALOGS start

        // Confirmation dialog for comment deletion, multi-delete, and entryproperties maintenance cache rebuilding :: note added for reference in G3
        $(document).on('click', '.comments_delete, .comments_multidelete, .build_cache', function (e) {
            const delMsg = $(this).attr('data-delmsg');
            if (delMsg && !confirm(delMsg)) {
                e.preventDefault();
                return false;
            }
        });

    // SUB - SHARED UI UTILITIES :: COMMENTS & DIALOGS end

    // SUB - SHARED UI UTILITIES :: SPINNERS start

        // --- UI Loader Spinners & Smooth Scroll for Long-Running Actions ---

        // Main Admin Area Operations (Plugin Install, Update, Import, Maintenance Checks)
        $(document).on('click', '.button_link.state_update, .button_link.state_install, .button_link.state_import, #maintenance_integrity a.button_link, #upgrade_notice', function () {
            // Smooth scroll up to top/admin header
            const $target = $('#serendipity_admin_page');
            const targetTop = $target.length ? $target.offset().top : 0;
            $('html, body').animate({ scrollTop: targetTop }, 500);

            // Show global admin spinner
            const $spinner = $('#waitingspin');
            if ($spinner.length) {
                $spinner.show().attr('aria-hidden', 'false');
            }
        });

        // Index / Dashboard Level Operations (Plugin Stats, Maintenance Thumbs Generation)
        $(document).on('click', '#plugin_stats, #maintenance_thumbs input[type="submit"]', function () {
            // Smooth scroll up to top/admin header
            const $target = $('#serendipity_admin_page');
            const targetTop = $target.length ? $target.offset().top : 0;
            $('html, body').animate({ scrollTop: targetTop }, 500);

            // Show index/dashboard specific spinner
            const $idxSpinner = $('#idx_waitingspin');
            if ($idxSpinner.length) {
                $idxSpinner.show().attr('aria-hidden', 'false');
            }
        });

        // Media property form submit feedback (Scroll & Spinner)
        $(document).on('submit', '#mediaPropertyForm', function () {
            const $form = $(this);

            // Smooth scroll to top of the form
            if ($form.length && typeof $form.offset === 'function') {
                $('html, body').animate({ scrollTop: $form.offset().top }, 300);
            }

            // Deterministic spinner display
            const $spinner = $('#waitingspin');
            if ($spinner.length) {
                $spinner.show().attr('aria-hidden', 'false');
            }
        });

        // Jump/Filter to specific Entry ID on Enter key in Entry List
        $(document).on('keydown', 'input#skipto_entry', function (e) {
            if (e.key === 'Enter' || e.which === 13) {
                e.preventDefault();
                $('input[name="serendipity[editSubmit]"]').trigger('click');
            }
        });

    // SUB - SHARED UI UTILITIES :: SPINNERS end

    // SUB - SHARED UI UTILITIES :: TOGGLES & DRAGS start

        // Toggle plugin information container visibility in plugin management
        $(document).on('click', '.plugin_show_info.toggle_info.button_link', function (e) {
            e.preventDefault();
            $('#plugin_options div.plugin_info.tgroup_info').toggleClass('additional_info');
        });

        // Toggle extended comment view (summary vs. full text)
        $(document).on('click', '.toggle_comment_full', function (e) {
            e.preventDefault();
            const $el = $(this);
            const dataHref = $el.data('href');
            const attrHref = $el.attr('href');

            let cArray = null;
            if (dataHref && typeof dataHref === 'string' && dataHref.indexOf('#') > -1) {
                cArray = dataHref.split(',');
            }

            if (Array.isArray(cArray)) {
                cArray.forEach(function (item) {
                    if (!item) return;
                    const idNum = item.trim().substring(1);
                    const $full = $(`#c${idNum}_full`);
                    const $summary = $(`#c${idNum}_summary`);

                    if (typeof serendipity.toggle_collapsible === 'function') {
                        serendipity.toggle_collapsible($el, $(dataHref.replace(/,$/, '')));
                    }

                    $full.toggleClass('additional_info');
                    $summary.toggleClass('additional_info');
                });
            } else {
                const targetSelector = attrHref || dataHref;
                const $toggled = $(targetSelector);

                if (typeof serendipity.toggle_collapsible === 'function') {
                    serendipity.toggle_collapsible($el, $toggled);
                }

                $toggled.prev().toggleClass('additional_info');
            }
        });

        // =========================================================================
        // 1. Unified Taxonomy Navigation Helper (Jumps to Categories & Freetags)
        // =========================================================================
        /**
         * Handles smooth scroll/jump to a taxonomy section (categories or freetags).
         * Expands metadata section if collapsed.
         */
        const handleTaxonomyJump = (triggerSelector, targetSelector) => {
            const $target = $(targetSelector);
            if (!$target.length) return;

            // 1. Ensure metadata collapsible section is expanded
            if ($('#meta_data').hasClass('additional_info')) {
                $('#toggle_metadata').trigger('click');
            }

            // 2. Perform scroll / focus
            $target[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            if ($target.is('input, select, textarea')) {
                $target.focus();
            }

            // 3. Optional Auto-Tags Floating Sidebar Trigger
            if (triggerSelector === '#select_tags' || targetSelector.includes('freetag')) {
                const $freeToc = $('#freetoc');
                if ($freeToc.length && !$freeToc.hasClass('can-float')) {
                    $freeToc.addClass('can-float');
                }
            }
        };

        // --- Collapsible Sections & Options ---

        // Clean up empty user_hooks for editors
        const $userHooks = $('#user_hooks[data-editor-check="true"]');
        if ($userHooks.length > 0 && $.trim($userHooks.html()) === '') {
            $userHooks.closest('li').hide();
        }

        // Entry Metadata Collapsible Toggle
        if ($('#edit_entry_metadata').length) {
            $(document).on('click', '#toggle_metadata', function (e) {
                e.preventDefault();
                serendipity.toggle_collapsible(this, '#meta_data');
            });

            if (window.localStorage && localStorage.getItem('show_toggle_metadata') === 'true') {
                $('#toggle_metadata').trigger('click');
            }
        }

        // Advanced Options Collapsible Toggle
        if ($('#advanced_options').length) {
            $(document).on('click', '#toggle_advanced', function (e) {
                e.preventDefault();
                serendipity.toggle_collapsible(this, '#adv_opts');
            });

            if (window.localStorage && localStorage.getItem('show_toggle_advanced') === 'true') {
                $('#toggle_advanced').trigger('click');
            }
        }

        // Generic Collapsible Config Elements (Categories, Configuration, Directory Forms)
        if ($('#serendipity_config_options, #serendipity_category, #image_directory_edit_form').length > 0) {
            let optsCollapsed = true;
            let defaultConfigState = true;

            // Single option-group toggles
            $(document).on('click', '.show_config_option', function (e) {
                e.preventDefault();
                const $el = $(this);
                const targetSelector = $el.attr('href') || $el.data('href');
                serendipity.toggle_collapsible($el, targetSelector);
            });

            // Toggle ALL config option groups at once
            $(document).on('click', '#show_config_all', function (e) {
                e.preventDefault();
                const $el = $(this);
                const containerSelector = $el.attr('href') || $el.data('href');
                const $container = $(containerSelector);

                const $toggleIcons = $container.find('.show_config_option > span');
                const $toggleOption = $container.find('.config_optiongroup');
                const $mainIcon = $el.find('span:first-child');

                if (optsCollapsed) {
                    $toggleIcons.removeClass('icon-right-dir').addClass('icon-down-dir');
                    $toggleOption.removeClass('additional_info');
                    $mainIcon.removeClass('icon-right-dir').addClass('icon-down-dir');
                    optsCollapsed = false;
                } else {
                    $toggleIcons.removeClass('icon-down-dir').addClass('icon-right-dir');
                    $toggleOption.addClass('additional_info');
                    $mainIcon.removeClass('icon-down-dir').addClass('icon-right-dir');
                    optsCollapsed = true;
                }
                $el.toggleClass('active', !optsCollapsed);
                defaultConfigState = false;
            });

            // Restore initial default states for specific views
            if (defaultConfigState && ($('#plugin_options').length > 0 || $('#template_options').length > 0)) {
                // Plugin & Theme options: keep default HTML state
            } else if (defaultConfigState && $('#serendipity_config_options').length > 0) {
                // Main Serendipity configuration: reset & open first group
                $('#show_config_all').trigger('click').trigger('click');
                $('#optionel1').find('span').removeClass('icon-right-dir').addClass('icon-down-dir');
                $('#el0').removeClass('additional_info');
            } else {
                // Default: Expand options that are not explicitly hidden
                $('.show_config_option:not(.show_config_option_hide)').trigger('click');
            }
        }

        /* ==========================================================
         * Plugin Manager Drag & Drop (Styx Native HTML5 / Pointer)
         * ========================================================== */
        if (!window.browserFeatures || !window.browserFeatures.touch) {
            document.querySelectorAll('.pluginmanager_container').forEach(container => {
                new StyxSortable(container, {
                    handle: '.pluginmanager_grablet',
                    onUpdate: (draggedItem) => {
                        if (!draggedItem) return;

                        const targetContainer = draggedItem.closest('.pluginmanager_container');
                        const placement = targetContainer ? targetContainer.dataset.placement : null;

                        if (placement) {
                            const select = draggedItem.querySelector('select[name$="[placement]"]');
                            if (select) {
                                select.value = placement;
                            }
                        }
                    }
                });
            });
        }

    // SUB - SHARED UI UTILITIES :: TOGGLES & DRAGS end

    // SUB - SHARED UI UTILITIES :: MISCELLANEOUS start

        // Jump/Filter to specific Entry ID on Enter key in Entry List
        $(document).on('keydown', 'input#skipto_entry', function (e) {
            if (e.key === 'Enter' || e.which === 13) {
                e.preventDefault();
                $('input[name="serendipity[editSubmit]"]').trigger('click');
            }
        });

        // Recalculate plugin list container heights on tab change (legacy layout fallback)
        $(document).on('click', '#pluginlist_tabs a', function () {
            if (typeof serendipity.sync_heights === 'function') {
                serendipity.sync_heights();
            }
        });

        // Trigger batch update for all pending plugin upgrades
        $(document).on('click', '#updateAll', function (e) {
            e.preventDefault();
            serendipity.updateAll();
        });

        // Universal history back trigger
        $(document).on('click', '.go_back', function (e) {
            e.preventDefault();
            history.go(-1);
        });

        // Accessibility Tabs Call
        document.querySelectorAll('.tabs').forEach(el => {
            new StyxTabs(el, {
                tabhead: 'h3',
                tabbody: '.panel',
                saveState: true
            });
        });

    }); // Closes DOM Ready Event Bindings

})(jQuery);
