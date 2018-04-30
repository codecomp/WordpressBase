/**
 * Checks if a passed element has a passed class
 *
 * @param el
 * @param cls
 * @returns {boolean}
 */
export function hasClass (el, cls) {
    let result = false;

    if (el.classList) {
        result = el.classList.contains(cls);
    } else {
        result = new RegExp('(^| )' + cls + '( |$)', 'gi').test(el.cls);
    }

    return result;
}

/**
 * Adds passed class to passed element, array of elements or nodeList
 *
 * @param target
 * @param cls
 */
export function addClass (target, cls) {
    function run(el){
        if (!hasClass(el, cls)) {
            if (el.classList) {
                el.classList.add(cls);
            } else {
                el.className += ' ' + cls;
            }
        }
    }

    if( target.constructor === NodeList || target.constructor === Array ){
        target.forEach((el) => {
            run(el);
        });
    } else {
        run(target);
    }
}

/**
 * Removed passed class from passed element, array of elements or nodeList
 *
 * @param target
 * @param cls
 */
export function removeClass (target, cls) {
    function run(el){
        if (hasClass(el, cls)) {
            if (el.classList) {
                el.classList.remove(cls);
            } else {
                el.cls = el.cls.replace(new RegExp('(^|\\b)' + cls.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
            }
        }
    }

    if( target.constructor === NodeList || target.constructor === Array ){
        target.forEach((el) => {
            run(el);
        });
    } else {
        run(target);
    }
}

/**
 * Toggles passed class on passed element, array of elements or nodeList
 *
 * @param target
 * @param cls
 */
export function toggleClass (target, cls) {
    function run(el){
        if (!hasClass(el, cls)) {
            addClass(el, cls);
        } else {
            removeClass(el, cls);
        }
    }

    if( target.constructor === NodeList || target.constructor === Array ){
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
        if (!filter || filter(el)){
            siblings.push(el);
        }
    } while (el = el.nextSibling);
    return siblings;
}

/**
 * Check on the browser to determine if the device is touch enabled
 *
 * @returns {boolean}
 */
export function isTouch () {
    let result = false;

    if( Modernizr.touchevents ) {
        result = Modernizr.touchevents;
    } else {
        result = 'ontouchstart' in window || navigator.maxTouchPoints;
    }

    return result;
}

/**
 * Check on the browser to determine if the window is less than the passed width
 *
 * @param width
 * @returns {boolean}
 */
export function maxWidth (width) {
    let result = false;

    if( Modernizr.mq ) {
        result = Modernizr.mq('(max-width: '+width+'px)');
    } else {
        const w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        result = w < width
    }

    return result;
}

/**
 * Check on the browser to determine if the window is greater than the passed width
 *
 * @param width
 * @returns {boolean}
 */
export function minWidth (width) {
    let result = false;

    if( Modernizr.mq ) {
        result = Modernizr.mq('(min-width: '+width+'px)');
    } else {
        const w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        result = w > width
    }

    return result;
}

/**
 * Calls passed function upon document being ready
 *
 * @param fn
 */
export function documentReady (fn) {
    if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

/**
 * Determine if a passed event string is native or not
 *
 * @param name
 * @returns {boolean}
 */
export function isNativeEvent (name) {
    return typeof document.body["on" + name] !== "undefined";
}

/**
 * Trigger a native or custom event on the passed element
 *
 * @param name
 * @param el
 */
export function trigger (name, el) {
    let event;

    if(isNativeEvent(name)){
        event = document.createEvent('HTMLEvents');
        event.initEvent(name, true, false);
    } else {
        if (window.CustomEvent) {
            event = new CustomEvent(name, {detail: {some: 'data'}});
        } else {
            event = document.createEvent('CustomEvent');
            event.initCustomEvent(name, true, true, {some: 'data'});
        }
    }

    el.dispatchEvent(event);
}

/**
 * Calculate the position of a passed element on the window
 *
 * @param el
 * @returns {{top: number, left: number}}
 */
export function getPosition (el) {
    const box = el.getBoundingClientRect(),
        docEl = document.documentElement,
        scrollTop = window.pageYOffset,
        scrollLeft = window.pageXOffset,
        clientTop = docEl.clientTop || 0,
        clientLeft = docEl.clientLeft || 0,
        top  = box.top + scrollTop - clientTop,
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