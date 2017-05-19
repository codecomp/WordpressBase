jQuery(document).ready(function($) {
    Site.init();
});


var Site = (function($) {

    // DOM caching
    var win = window;

    // Globals
    var w = {
        width: 	win.innerWidth,
        height: win.innerHeight,
        scroll: win.pageYOffset
    };

    var ui = {
        fast: 0.2,
        slow: 0.4,
        step: 0.03,
        easeout: Power4.easeOut,
        easein: Power4.easeIn
    };


    function updateGlobals(){
        w.width  = win.innerWidth;
        w.height = win.innerHeight;
        w.scroll = win.pageYOffset;
    }

    win.addEventListener('resize', updateGlobals, true);
    win.addEventListener('scroll', updateGlobals, true);
    win.addEventListener('load', updateGlobals, true);


    document.documentElement.setAttribute('data-ua',  navigator.userAgent);


    return {
        init: function(){

            for (var prop in this.modules) {
                if ( this.modules.hasOwnProperty(prop) ) {
                    this.modules[prop]();
                }
            }

            for (var props in this.autoInits) {
                if ( this.autoInits.hasOwnProperty(props) ) {
                    var $selector = $(props);

                    if ($selector.length) {
                        this.autoInits[props]($selector);
                    }
                }
            }
        },
        ui: ui,
        w: w
    };

})(jQuery);



(function(u){

    function _handleResponse(request, success) {
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
    }

    u.hasClass = function(el, cls) {

        return el.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
    };

    u.addClass = function(el, cls) {
        if (!this.hasClass(el, cls)) {
            el.className += " " + cls;
        }
    };

    u.removeClass = function(el, cls) {
        if (this.hasClass(el, cls)) {

            var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
            el.className = el.className.replace(reg,' ');
        }
    };

    u.is_touch = Modernizr.touchevents;

    u.maxWidth = function(width){

        return Modernizr.mq('(max-width: '+width+'px)');
    };
    u.minWidth = function(width){

        return Modernizr.mq('(min-width: '+width+'px)');
    };

    u.jax = {
        get: function(url, success) {
            var request = new XMLHttpRequest();

            request.open('GET', url, true);

            _handleResponse(request, success);

        },
        post: function(url, data, success) {
            var request = new XMLHttpRequest();

            request.open('POST', url, true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.send(data);

            _handleResponse(request, success);
        }
    };

    u.serialize = function(form) {
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
    };

    u.extend = function(out) {
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
    };

    u.throttle = function(func, wait, options) {
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
    };

    u.debounce = function(func, wait, immediate) {
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
    };

    u.position = function(el){
        var box = el.getBoundingClientRect();

        var docEl = document.documentElement;

        var scrollTop = window.pageYOffset;
        var scrollLeft = window.pageXOffset;

        var clientTop = docEl.clientTop || 0;
        var clientLeft = docEl.clientLeft || 0;

        var top  = box.top +  scrollTop - clientTop;
        var left = box.left + scrollLeft - clientLeft;

        return { top: Math.round(top), left: Math.round(left) };
    };



    u.trigger = function(eventName, node) {
        // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
        var doc;
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
                    break;
            }
            var event = doc.createEvent(eventClass);

            var bubbles = eventName == "change" ? false : true;
            event.initEvent(eventName, bubbles, true); // All events created as bubbling and cancelable.

            event.synthetic = true; // allow detection of synthetic events
            // The second parameter says go ahead with the default action
            node.dispatchEvent(event, true);
        } else if (node.fireEvent) {
            // IE-old school style
            var event = doc.createEventObject();
            event.synthetic = true; // allow detection of synthetic events
            node.fireEvent("on" + eventName, event);
        }
    };

})(Site.utils = Site.utils || {});



(function(a, ui){
    a.to = TweenMax.to;
    a.stagger = TweenMax.staggerTo;

    a.fx = {
        fadeIn: { opacity: 1, display: 'block', ease: ui.easing },
        fadeOut: { opacity: 0, display: 'none', ease: ui.easing },

        fadeIn_left: { opacity: 1, x: '0px', display: 'block', ease: ui.easing },
        fadeOut_left: { opacity: 0, x: '-40px', display: 'none', ease: ui.easing },

        fadeIn_right: { opacity: 1, x: '0px', display: 'block', ease: ui.easing },
        fadeOut_right: { opacity: 0, x: '40px', display: 'none', ease: ui.easing },

        fadeIn_up: { opacity: 1, y: '0px', display: 'block', ease: ui.easing },
        fadeIn_down: { opacity: 1, y: '0px', display: 'block', ease: ui.easing },

        fadeOut_up: { opacity: 0, y: '-40px', display: 'none', ease: ui.easing },
        fadeOut_down: { opacity: 0, y: '40px', display: 'none', ease: ui.easing },
    };

    // TODO: THIS, Crossbrowser
    function prepFadeIn(el) {
        el.style.opacity = 0;
        el.style.display = 'none';
    }

    a.fadeIn = function(el){
        a.to(el, ui.slow, a.fx.fadeIn);
    };

    a.fadeOut = function(el){
        a.to(el, ui.fast, a.fx.fadeIn);
    };

    a.fadeInUp = function(el){
        a.to(el, ui.slow, a.fx.fadeIn_up);
    };

    a.fadeInDown = function(el){
        a.to(el, ui.slow, a.fx.fadeIn_down);
    };

    a.fadeOutUp = function(el){
        a.to(el, ui.fast, a.fx.fadeOut_down);
    };

    a.fadeOutDown = function(el){
        a.to(el, ui.fast, a.fx.fadeOut_down);
    };

    a.fadeInLeft = function(el){
        a.to(el, ui.slow, a.fx.fadeIn_left);
    };

    a.fadeOutLeft = function(el){
        a.to(el, ui.fast, a.fx.fadeOut_left);
    };

    a.fadeInRight = function(el){
        a.to(el, ui.slow, a.fx.fadeIn_right);
    };

    a.fadeOutRight = function(el){
        a.to(el, ui.fast, a.fx.fadeOut_right);
    };

    a.stepFadeIn = function(el) {
        a.stagger(el, ui.fast, a.fx.fadeIn_down, ui.step);
    };

})(Site.anim = Site.anim || {}, Site.ui);


Site.autoInits = (function($, ui, u){
    return {

        // Auto fade form field lables
        '.field': function($fields){
            var $field = $fields.find('input, textarea');

            $field.on({
                focus: function(){
                    if (!this.value) {
                        $(this).prev().addClass('is-focused');
                    }
                },
                blur: function(){
                    if (!this.value) {
                        $(this).prev().removeClass('is-focused');
                    } else {
                        $(this).prev().addClass('is-focused');
                    }
                },
                load: function(){
                    if (this.value) {
                        $(this).prev().addClass('is-focused');
                    } else {
                        $(this).prev().removeClass('is-focused');
                    }
                }
            });

            window.addEventListener('load', function(){
                $field.trigger('load');
            });
        },

        // Auto process forms
        '.js-process-form': function($forms){

            $forms.each(function(){
                $(this).validate({
                    ignore: '',
                    rules: {
                        upload: {
                            required: false,
                            extension: "doc|docx|txt|rtf|pdf|jpg|gif|jpeg|png|tiff|bmp"
                        }
                    },
                    onfocusout: function(element) {
                        this.element(element);
                    },
                    onkeyup: false
                });
            });

            function processForm(e){
                var $this = $(this);

                e.preventDefault();

                if ($this.valid()) {
                    $.ajax({
                        url: WP.ajax,
                        type:'POST',
                        data: $.extend({
                            action:    $this.attr('action'),
                            security: WP.nonce
                        }, $this.serializeObject()),
                        dataType: 'json',
                        success: function(data){

                            if(data !== null && typeof data !== 'object')
                                data = JSON.parse(data);

                            if (data.success) {
                                $this.html('<p class="form-sent">' + $this.data('thanks') + '</p>');
                            } else {
                                $this.html('<p class="form-sent">' + data.data + '</p>');
                            }

                        }
                    });
                }
            }

            $forms.on('submit', processForm);
        },

        // Auto popup share windows
        '.js-share': function($share){
            $share.on('click', 'a', function(){

                if ($(this).attr('href').indexOf('http') === 0) {
                    var new_window = window.open($(this).attr('href'), '', 'height=450, width=700');

                    if (window.focus) {
                        new_window.focus();
                    }

                    return false;

                }
            });
        },

        // Auto Scroll to section
        '.js-scroll-to': function($link) {
            $link.on('click', function(e){
                var href = $(this).attr('href');

                if (href.indexOf('#') == -1) return;

                e.preventDefault();

                var target = document.getElementById(href.substr(1, href.length-1));

                if (!target) return false;

                var pos = Site.utils.position(target).top - 100;

                TweenMax.to(window, 0.8, {scrollTo: {y: pos}, ease: ui.enter});
            });
        },

        // Auto Generate Google Maps
        '.js-map': function($map) {

            if (window.initMap) {
                window.initMap();
                return false;
            };

            window.initMap = function(){
                var maps = document.getElementsByClassName('js-map');

                if (maps.length == 0) return false;

                for (var i = 0; i < maps.length; i++) {
                    var lat = maps[i].getAttribute('data-lat'),
                        lng = maps[i].getAttribute('data-lng');


                    var map,
                        mapOptions = {
                            zoom: 14,
                            scrollwheel: false,
                            mapTypeControl: false,
                            streetViewControl: false,
                            zoomControl: false,
                            draggable: false,
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            center: new google.maps.LatLng(lat,lng)
                        };

                    map = new google.maps.Map(maps[i], mapOptions);

                    var marker = new google.maps.Marker({
                        position: mapOptions.center,
                        map: map[i],
                        draggable: false,
                    });

                    marker.setMap(map);
                }
            }

            var src = '//maps.googleapis.com/maps/api/js?v=3&callback=initMap',
                protocol = ('https:' == doc.location.protocol ? 'https:' : 'http:'),
                script = doc.createElement('script');

            script.type = 'text/javascript';
            script.async = true;
            script.src = protocol + src;

            doc.getElementsByTagName("head")[0].appendChild(script);
        },

        // Move element depending on screen width
        '.js-move': function($el) {
            var els = doc.getElementsByClassName('js-move');
            els = [].slice.call(els);

            els.forEach(function(el){
                repos(el);
            })

            function repos(el) {
                var width = el.getAttribute('data-width'),
                    selector = el.getAttribute('data-target'),
                    position = el.getAttribute('data-position');

                var targetEl = doc.querySelector(selector);

                var has_moved = false;

                var placeholder = doc.createElement('div');
                placeholder.setAttribute('class', 'js-move-placeholder');
                placeholder.style.display = 'none';

                el.parentNode.insertBefore(placeholder, el);

                function insert(el, target, position){
                    switch (true) {
                        case position == 'append':
                            target.appendChild(el);
                            break;
                        case position == 'prepend':
                            target.insertBefore(el, target.firstChild);
                            break;
                        case position == 'before':
                            target.parentNode.insertBefore(el, target);
                            break;
                        case position == 'after':
                            target.parentNode.insertBefore(el, target.nextSibling);
                            break;
                        default:
                            break;
                    }
                }


                function checkPos(){
                    if (!has_moved && u.maxWidth(width)) {
                        insert(el, targetEl, position);
                        has_moved = true;
                    }

                    if (has_moved && !u.maxWidth(width)) {
                        insert(el, placeholder, 'after');
                        has_moved = false;
                    }
                }

                window.addEventListener('resize', u.debounce(checkPos, 300));
                checkPos();

            }
        },
    };
})(jQuery, Site.ui, Site.utils);


/* ==========================================================================
 Mini plugins
 ========================================================================== */

jQuery.noConflict();
(function($) {

    // Serialized Form Data
    $.fn.serializeObject = function(){
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

})(jQuery);