/**
 * Adds or removes class on passed element, array of elements or nodeList based on passed boolean
 *
 * @param target
 * @param cls
 */
export function boolClass(target, cls, bool) {
    function run(el) {
        if (!el.classList.contains(cls) && bool) {
            el.classList.add(cls);
        }

        if (el.classList.contains(cls) && !bool) {
            el.classList.remove(cls);
        }
    }

    if (target.constructor === NodeList || target.constructor === Array) {
        target.forEach((el) => {
            run(el);
        });
    } else {
        run(target);
    }
}

/**
 * Returns siblings of passed element
 *
 * @param el
 * @returns {*}
 */
export function getSiblings(el, filter) {
    const siblings = [];
    el = el.parentNode.firstChild;
    do {
        if (!filter || filter(el)) {
            siblings.push(el);
        }
    } while ((el = el.nextSibling));
    return siblings;
}

/**
 * Check on the browser to determine if the device is touch enabled
 *
 * @returns {boolean}
 */
export function isTouch() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
}

/**
 * Check on the browser to determine if the window is less than the passed width
 *
 * @param width
 * @returns {boolean}
 */
export function maxWidth(width) {
    let result = false;

    const w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    result = w < width;

    return result;
}

/**
 * Determine if a passed event string is native or not
 *
 * @param name
 * @returns {boolean}
 */
export function isNativeEvent(name) {
    return typeof document.body['on' + name] !== 'undefined';
}

/**
 * Trigger a native or custom event on the passed element
 *
 * @param name
 * @param el
 * @todo Update to use Event()
 */
export function trigger(name, el, data = {}) {
    let event;

    if (isNativeEvent(name)) {
        event = new Event(name, { bubbles: true, cancelable: false });
    } else {
        event = new CustomEvent(name, { bubbles: true, cancelable: true, detail: data });
    }

    el.dispatchEvent(event);
}

/**
 * Calculate the position of a passed element on the window
 *
 * @param el
 * @returns {{top: number, left: number}}
 */
export function getPosition(el) {
    const box = el.getBoundingClientRect(),
        docEl = document.documentElement,
        scrollTop = window.pageYOffset,
        scrollLeft = window.pageXOffset,
        clientTop = docEl.clientTop || 0,
        clientLeft = docEl.clientLeft || 0,
        top = box.top + scrollTop - clientTop,
        left = box.left + scrollLeft - clientLeft;

    return { top: Math.round(top), left: Math.round(left) };
}

/**
 * Determine weather a given string is valid JSON
 *
 * @param str
 * @returns {boolean}
 */
export function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
