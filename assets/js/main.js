import * as utils from './modules/utils';
import 'core-js/fn/symbol/iterator';
import 'whatwg-fetch';
import bowser from 'bowser';
import Cookies from 'js-cookie';
import ValidForm from '@pageclip/valid-form';
//import throttle from 'throttle-debounce/throttle';
//import debounce from 'throttle-debounce/debounce';

const moduleRegistration = function(m, u, ui, win, doc){

    // Document loaded class
    m.docLoad = () => {
        u.addClass(document.body, 'is-loaded');
        u.addClass(document.body, bowser.name.replace(/\s+/g, '-').toLowerCase());
        u.addClass(document.body, bowser.name.replace(/\s+/g, '-').toLowerCase() + '-' + bowser.version.replace(/\s+/g, '-').toLowerCase()); // eslint-disable-line max-len
    };

    // Open share links in popup window
    m.sharePopup = () => {
        doc.addEventListener('click', (e) => {
            if( u.hasClass(e.target, 'js-share') ) {
                const url = e.target.getAttribute('href');

                if (url && url.indexOf('http') === 0) {
                    const newWindow = window.open(url, '', 'height=450, width=700');

                    if (window.focus) {
                        newWindow.focus();
                    }

                    e.preventDefault();
                }
            }
        });
    };

    // Set form label active classes to assist styling
    m.formLabels = () => {
        function check (el, force = false){
            const label = u.getSiblings(el, (subject) => { return subject.nodeName.toLocaleLowerCase() === 'label'; });

            if(el.value || force){
                u.addClass(label, 'is-focused');
            } else {
                u.removeClass(label, 'is-focused');
            }
        }

        function checkEvent(e){
            check(e.target, e.type === 'focus');
        }

        for(const field of doc.querySelectorAll('input, textarea')) {
            field.addEventListener('focus', checkEvent);
            field.addEventListener('blur', checkEvent);
            check(field);
        }

        for(const select of doc.querySelectorAll('select')) {
            select.addEventListener('change', checkEvent);
            check(select);
        }
    };

    // initialise google maps
    m.googleMap = () => {
        const maps = document.getElementsByClassName('js-map');

        if (maps.length === 0){
            return false;
        }

        if (win.initMap) {
            win.initMap();
            return false;
        }

        win.initMap = function(){
            for (const map of maps) {
                const lat = map.getAttribute('data-lat'),
                    lng = map.getAttribute('data-lng'),
                    mapOptions = {
                        zoom: 14,
                        scrollwheel: false,
                        mapTypeControl: false,
                        streetViewControl: false,
                        zoomControl: false,
                        draggable: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        center: new google.maps.LatLng(lat,lng)
                    },
                    googleMap = new google.maps.Map(map, mapOptions),
                    marker = new google.maps.Marker({
                        position: mapOptions.center,
                        map: googleMap,
                        draggable: false,
                    });

                marker.setMap(googleMap);
            }
        };

        const src = `//maps.googleapis.com/maps/api/js?v=3&callback=initMap&key=${WP.gmap_key}`,
            protocol = ('https:' === doc.location.protocol ? 'https:' : 'http:'),
            script = doc.createElement('script');

        script.type = 'text/javascript';
        script.async = true;
        script.src = protocol + src;

        doc.getElementsByTagName("head")[0].appendChild(script);
    };

    // Process forms into WP Admin Requests
    m.processForms = () => {

        //Process response from AJAX requests
        function handleResponse(form, message){
            const thanks = doc.createElement('div');
            u.addClass(thanks, 'response');
            thanks.innerHTML = `<p class="response__thanks">${message}</p>`;

            form.parentNode.replaceChild(thanks, form);
        }

        // Handle form submission
        function handleSubmit(e) {
            e.preventDefault();
            const form = e.target;

            // Stop repeated submissions
            if( u.hasClass(form, 'is-loading') ) {
                return false;
            }

            u.addClass(form, 'is-loading');

            // Format request data
            const data = new FormData(form);
            data.append('action', form.getAttribute('action'));
            data.append('security', WP.nonce);

            // Send the request
            let responseStatus,
                message;

            fetch(WP.ajax, {
                method: 'POST',
                body: data
            })
                .then(response => {
                    responseStatus = response.status;
                    return response.json()
                })
                .then(response => {
                    switch (responseStatus) {
                        case 200:
                        case 201:
                        case 202:
                            if(response.data.message){
                                message = response.data.message
                            } else {
                                message = WP.translate.thanks;
                            }
                            break;
                        case 418:
                            message = 'What a regrettably large head you have. I would very much like to hat it. I used to hat The White Queen, you know. Her head was so small.'; // eslint-disable-line max-len
                            break;
                        default:
                            message = WP.translate.error;
                            break
                    }

                    handleResponse(form, message);
                })
                .catch(() => {
                    message = WP.translate.thanks;
                    handleResponse(form, message);
                });
        }

        // Setup form processing
        for(const form of doc.getElementsByClassName('js-process-form')) {
            ValidForm(form, {errorPlacement: 'after'});

            form.addEventListener('submit', handleSubmit);
        }
    };

    // Handle notice visibility
    m.Notices = function(){

        function showNotice(notice){
            u.addClass(notice, 'is-visible');
        }

        function hideNotice(notice){
            u.removeClass(notice, 'is-visible');
            Cookies.set(`notice-${notice.getAttribute('data-type')}`, true, { expires: 30 });
        }

        for(const notice of doc.getElementsByClassName('notice--closeable')) {
            if( Cookies.get(`notice-${notice.getAttribute('data-type')}`) === undefined ){
                showNotice(notice);
            }

            notice.querySelector('.notice__close').addEventListener('click', () => { hideNotice(notice); });
        }
    };

};

class Site {

    constructor() {
        // DOM caching
        this.win = window;
        this.doc = document;

        this.doc.documentElement.setAttribute('data-ua',  navigator.userAgent);

        // Globals
        this.ui = {
            fast: 0.2,
            slow: 0.4,
            step: 0.03
        };
        this.modules = {};

        this.init();
    }

    init(){
        moduleRegistration(this.modules, utils, this.ui, this.win, this.doc);

        for (const prop in this.modules) {
            if ( this.modules.hasOwnProperty(prop) ) {
                this.modules[prop]();
            }
        }
    }

}

utils.documentReady(()=>{ new Site(); });
