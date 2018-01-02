var $ = jQuery,
    auto = {

        // Auto fade form field lables
        '.form__field': function($fields){
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

            var $select = $fields.find('select');

            $select.on({
                load: function(){
                    if (this.value) {
                        $(this).prev().addClass('is-focused');
                    } else {
                        $(this).prev().removeClass('is-focused');
                    }
                },
                change: function(){
                    if (this.value) {
                        $(this).prev().addClass('is-focused');
                    } else {
                        $(this).prev().removeClass('is-focused');
                    }
                }
            });

            window.addEventListener('load', function(){
                $select.trigger('load');
            });
        },

        // Auto process forms
        '.js-process-form': function($forms){

            $forms.each(function () {
                $(this).validate({
                    ignore: '',
                    rules: {
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).parent('div').addClass('form__field--error').removeClass('form__field--valid');
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).parent('div').addClass('form__field--valid').removeClass('form__field--error');
                    },
                    errorPlacement: function (error, element) {
                    },
                    onfocusout: function (element) {
                        this.element(element);
                    },
                    onkeyup: false
                });
            });

            function processForm(e){
                var $this = $(this);

                e.preventDefault();

                formData = new FormData($this[0]);
                formData.append('action', $this.attr('action'));
                formData.append('security', WP.nonce);

                if( $this.hasClass('loading') )
                    return false;

                $this.addClass('loading');

                if ($this.valid()) {
                    $.ajax({
                        url: WP.ajax,
                        type:'POST',
                        data: formData,
                        dataType: 'json',
                        contentType:false,
                        processData: false,
                        beforeSend: function(){
                            start = new Date().getTime();
                        },
                        success: function(data){

                            setTimeout(function(){

                                if(data !== null && typeof data !== 'object')
                                    data = JSON.parse(data);

                                if (data.success) {
                                    $this.html('<p class="form-sent">' + $this.data('thanks') + '</p>');
                                } else {
                                    $this.html('<p class="form-sent">' + data.data + '</p>');
                                }

                                $this.removeClass('loading');

                            }, 1000 - (new Date().getTime() - start));

                        }
                    });
                } else {
                    $this.removeClass('loading');
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
        }

    };

module.exports = auto;