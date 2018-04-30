import * as utils from './modules/utils';
import ValidForm from '@pageclip/valid-form';
import throttle from 'throttle-debounce/throttle';
import debounce from 'throttle-debounce/debounce';
import request from 'request';
import serialize from 'form-serialize';
import bowser from 'bowser';

const moduleRegistration = function(m, u, ui, win, doc){

    // Document loaded class
    m.docLoad = () => {
        u.addClass(document.body, 'is-loaded');
        u.addClass(document.body, bowser.name.replace(/\s+/g, '-').toLowerCase());
        u.addClass(document.body, bowser.name.replace(/\s+/g, '-').toLowerCase() + '-' + bowser.version.replace(/\s+/g, '-').toLowerCase());
    };

    // Open share links in popup window
    m.sharePopup = () => {
        doc.addEventListener('click', (e) => {
            const url = e.target.getAttribute('href');

            if (url && url.indexOf('http') === 0) {
                const newWindow = window.open(url, '', 'height=450, width=700');

                if (window.focus) {
                    newWindow.focus();
                }

                e.preventDefault();
            }
        });
    };

    // Set form label active classes to assist styling
    m.formLabels = () => {
        const fields = doc.querySelectorAll('input, textarea'),
            selects = doc.querySelectorAll('select');

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

        if(fields.length !== 0){
            fields.forEach( (el) => {
                el.addEventListener('focus', checkEvent);
                el.addEventListener('blur', checkEvent);
                check(el);
            });
        }

        if( selects.length !== 0 ){
            selects.forEach( (el) => {
                el.addEventListener('change', checkEvent);
                check(el);
            });
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

        const src = '//maps.googleapis.com/maps/api/js?v=3&callback=initMap',
            protocol = ('https:' === doc.location.protocol ? 'https:' : 'http:'),
            script = doc.createElement('script');

        script.type = 'text/javascript';
        script.async = true;
        script.src = protocol + src;

        doc.getElementsByTagName("head")[0].appendChild(script);
    };

    // Process forms into WP Admin Requests
    m.processForms = () => {
        for(const form of doc.getElementsByClassName('js-process-form')) {
            ValidForm(form, {errorPlacement: 'after'});

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                // Stop repeated submissions
                if( u.hasClass(form, 'is-loading') ) {
                    return false;
                }

                u.addClass(form, 'is-loading');

                // Serialise the form data and append WP variables
                const formData = serialize(form, { hash: true });
                formData.action = form.getAttribute('action');
                formData.security = WP.nonce;

                // Send the request
                const start = new Date().getTime();
                request.post({url:WP.ajax, form: formData}, (err, response, body) => {
                    setTimeout(() => {
                        let message;

                        if(err){
                            console.warn(err);
                            message = WP.translate.error;
                        } else if(u.isJsonString(body)){
                            const info = JSON.parse(body);

                            if(info.data.message){
                                message = info.data.message
                            } else {
                                message = WP.translate.thanks;
                            }
                        } else {
                            message = WP.translate.thanks;
                        }

                        console.log( message );

                        const thanks = doc.createElement('div');
                        u.addClass(thanks, 'response');
                        thanks.innerHTML = `<p class="response__thanks">${message}</p>`;

                        form.parentNode.replaceChild(thanks, form);
                    }, 1000 - (new Date().getTime() - start));
                });
            });
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
