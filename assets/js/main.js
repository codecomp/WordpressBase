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
        autoInits: require('./auto'),
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
        utils: require('./utils'),
        ui: ui,
        w: w
    };

})(jQuery);

(function(m, u, ui, w, a, $){

    var menu_state = false;

    // Example Module
    m.example = function(){
        // Do stuff, the m.example module gets auto initialized.

        var el = document.getElementsByClassName('tst')[0];
        console.log( el );
        console.log( u.getSiblings(el) );
    };

    // Document loaded class
    m.docLoad = function(){
        u.addClass(document.body, 'is-loaded');
    };

})(Site.modules = Site.modules || {}, Site.utils, Site.ui, Site.w, Site.anim, jQuery);