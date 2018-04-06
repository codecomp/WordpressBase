import utils from './modules';
import throttle from 'throttle-debounce/throttle';
import debounce from 'throttle-debounce/debounce';
import request from 'request';
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
