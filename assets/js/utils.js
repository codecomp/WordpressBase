var utils ={

    hasClass: function(el, cls) {
        return el.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
    },

    addClass: function(el, cls) {
        if (!this.hasClass(el, cls)) {
            el.className += " " + cls;
        }
    },

    removeClass: function(el, cls) {
        if (this.hasClass(el, cls)) {

            var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
            el.className = el.className.replace(reg,' ');
        }
    },

    toggleClass: function(el, cls) {
        if (!this.hasClass(el, cls)) {
            el.className += " " + cls;
        } else {
            var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
            el.className = el.className.replace(reg,' ');
        }
    },

    is_touch: Modernizr.touchevents,

    maxWidth: function(width){

        return Modernizr.mq('(max-width: '+width+'px)');
    },
    minWidth: function(width){

        return Modernizr.mq('(min-width: '+width+'px)');
    },

    jax: {
        _handleResponse: function(request, success) {
            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {

                    if (typeof request.responseText == 'string') {
                        data = request.responseText;
                    } else {
                        data = JSON.parse(request.responseText);
                    }

                    success(data);

                } else {
                    return request.status + ' failed request: '+ JSON.parse(request.responseText);
                }
            };

            request.onerror = function() {
                return request.status + ' failed request: '+ JSON.parse(request.responseText);
            };

            request.send();
        },

        get: function(url, success) {
            var request = new XMLHttpRequest();

            request.open('GET', url, true);

            this.jax._handleResponse(request, success);

        },
        post: function(url, data, success) {
            var request = new XMLHttpRequest();

            request.open('POST', url, true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.send(data);

            this.jax._handleResponse(request, success);
        }
    },

    serialize: function(form) {
        var field, s = [];

        if (typeof form == 'object' && form.nodeName == "FORM") {

            var len = form.elements.length;

            for (i = 0; i < len; i++) {

                field = form.elements[i];

                if ( field.name && !field.disabled &&
                    field.type != 'file' && field.type != 'reset' &&
                    field.type != 'submit' && field.type != 'button') {

                    if (field.type == 'select-multiple') {

                        for (var j = form.elements[i].options.length-1; j >= 0; j--) {

                            if (field.options[j].selected) {

                                s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                            }
                        }

                    } else if ( (field.type != 'checkbox' && field.type != 'radio') || field.checked) {

                        s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                    }
                }
            }
        }

        return s.join('&').replace(/%20/g, '+');
    },

    extend: function(out) {
        out = out || {};

        for (var i = 1; i < arguments.length; i++) {
            if (!arguments[i]) {
                continue;
            }

            for (var key in arguments[i]) {
                if (arguments[i].hasOwnProperty(key)){
                    out[key] = arguments[i][key];
                }
            }
        }

        return out;
    },

    throttle: function(func, wait, options) {
        var context, args, result,

            timeout = null,
            previous = 0;

        if (!options) {
            options = {};
        }

        var later = function() {
            previous = options.leading === false ? 0 : Date.now();
            timeout = null;
            result = func.apply(context, args);

            if (!timeout) {
                context = args = null;
            }
        };

        return function() {
            var now = Date.now();

            if (!previous && options.leading === false){
                previous = now;
            }

            var remaining = wait - (now - previous);

            context = this;
            args = arguments;

            if (remaining <= 0 || remaining > wait) {

                if (timeout) {

                    clearTimeout(timeout);
                    timeout = null;
                }

                previous = now;
                result = func.apply(context, args);

                if (!timeout) {
                    context = args = null;
                }

            } else if (!timeout && options.trailing !== false) {

                timeout = setTimeout(later, remaining);
            }

            return result;
        };
    },

    debounce: function(func, wait, immediate) {
        var timeout, args, context, timestamp, result;

        var later = function() {
            var last = Date.now() - timestamp;

            if (last < wait && last >= 0) {

                timeout = setTimeout(later, wait - last);

            } else {

                timeout = null;

                if (!immediate) {

                    result = func.apply(context, args);

                    if (!timeout) {
                        context = args = null;
                    }
                }
            }
        };

        return function() {
            context = this;
            args = arguments;
            timestamp = Date.now();

            var callNow = immediate && !timeout;

            if (!timeout) {
                timeout = setTimeout(later, wait);
            }

            if (callNow) {
                result = func.apply(context, args);
                context = args = null;
            }

            return result;
        };
    },

    position: function(el){
        var box = el.getBoundingClientRect();

        var docEl = document.documentElement;

        var scrollTop = window.pageYOffset;
        var scrollLeft = window.pageXOffset;

        var clientTop = docEl.clientTop || 0;
        var clientLeft = docEl.clientLeft || 0;

        var top  = box.top +  scrollTop - clientTop;
        var left = box.left + scrollLeft - clientLeft;

        return { top: Math.round(top), left: Math.round(left) };
    },

    trigger: function(eventName, node) {
        // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
        var doc, event;
        if (node.ownerDocument) {
            doc = node.ownerDocument;
        } else if (node.nodeType == 9) {
            // the node may be the document itself, nodeType 9 = DOCUMENT_NODE
            doc = node;
        } else {
            throw new Error("Invalid node passed to fireEvent: " + node.id);
        }

        if (node.dispatchEvent) {
            // Gecko-style approach (now the standard) takes more work
            var eventClass = "";

            // Different events have different event classes.
            // If this switch statement can't map an eventName to an eventClass,
            // the event firing is going to fail.
            switch (eventName) {
                case "click": // Dispatching of 'click' appears to not work correctly in Safari. Use 'mousedown' or 'mouseup' instead.
                case "mousedown":
                case "mouseup":
                    eventClass = "MouseEvents";
                    break;

                case "focus":
                case "change":
                case "blur":
                case "select":
                    eventClass = "HTMLEvents";
                    break;

                default:
                    throw "fireEvent: Couldn't find an event class for event '" + eventName + "'.";
            }
            event = doc.createEvent(eventClass);

            var bubbles = eventName == "change" ? false : true;
            event.initEvent(eventName, bubbles, true); // All events created as bubbling and cancelable.

            event.synthetic = true; // allow detection of synthetic events
            // The second parameter says go ahead with the default action
            node.dispatchEvent(event, true);
        } else if (node.fireEvent) {
            // IE-old school style
            event = doc.createEventObject();
            event.synthetic = true; // allow detection of synthetic events
            node.fireEvent("on" + eventName, event);
        }
    },

    setCookie: function(name, value, days){
        var d = new Date();
        d.setTime(d.getTime() + 24*60*60*1000*days);
        document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
    },

    getCookie: function(name) {
        var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    },

    docReady: function(callback, context) {
        var readyList = [];
        var readyFired = false;
        var readyEventHandlersInstalled = false;

        if (typeof callback !== "function") {
            throw new TypeError("callback for docReady(fn) must be a function");
        }
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        // IE only safe when readyState is "complete", others safe when readyState is "interactive"
        if (document.readyState === "complete" || (!document.attachEvent && document.readyState === "interactive")) {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }

        // call this when the document is ready
        // this function protects itself against being called more than once
        function ready() {
            if (!readyFired) {
                // this must be set to true before we start calling callbacks
                readyFired = true;
                for (var i = 0; i < readyList.length; i++) {
                    // if a callback here happens to add new ready handlers,
                    // the docReady() function will see that it already fired
                    // and will schedule the callback to run right after
                    // this event loop finishes so all handlers will still execute
                    // in order and no new ones will be added to the readyList
                    // while we are processing the list
                    readyList[i].fn.call(window, readyList[i].ctx);
                }
                // allow any closures held by these functions to free
                readyList = [];
            }
        }

        function readyStateChange() {
            if ( document.readyState === "complete" ) {
                ready();
            }
        }
    },

    getSiblings: function(el) {
        return Array.prototype.filter.call(el.parentNode.children, function(child){
            return child !== el;
        });
    },

    isInViewport: function(el) {
        var rect = el.getBoundingClientRect();
        var html = document.documentElement;
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || html.clientHeight) &&
            rect.right <= (window.innerWidth || html.clientWidth)
        );
    }

};

module.exports = utils;