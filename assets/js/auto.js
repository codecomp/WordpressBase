var $ = jQuery,
    auto = {

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
        }

    };

module.exports = auto;