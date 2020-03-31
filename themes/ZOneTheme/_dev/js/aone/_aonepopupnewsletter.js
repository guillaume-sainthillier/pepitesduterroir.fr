function setCookie(cname, cvalue, exdays, path) {
    var d = new Date();
    var expires = '';
    if (exdays) {
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        expires = ';expires='+d.toUTCString();
    }
    
    document.cookie = cname + '=' + cvalue + expires + ';path=' + path;
}

function getCookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
}

function ajaxNewsletterSubscribe(subscribeForm) {
    var subscribeURL = $('.js-popup-newsletter-form').data('ajax-submit-url');

    if (subscribeURL && subscribeForm.length) {
        subscribeForm.submit(function(event) {
            event.preventDefault();
            var $form = $(this);

            $.ajax({
                type: 'POST',
                url: subscribeURL,
                data: $form.serialize()+'&submitNewsletter=1&ajax=1',
                dataType: 'json',
                success: function(data) {
                    let msg_html = '<p class="alert alert-success">' + data.msg + '</p>';
                    if (data.nw_error) {
                        msg_html = '<p class="alert alert-danger">' + data.msg + '</p>';
                    }
                    $form.find('.newsletter-message').fadeOut(400, function () {
                        $(this).html(msg_html).fadeIn();
                    });
                },
                error: function(XMLHttpRequest)
                {
                    alert("Response Text:\n" + XMLHttpRequest.responseText);
                }
            });

            return false;
        });
    }
}

function aoneNewsletterLoad()
{
    var jsAOneNewsletter = $('.js-aone-popupnewsletter');
    if (jsAOneNewsletter.length) {
        var save_time = jsAOneNewsletter.data('save-time');

        if (getCookie('aonehidepopupnewsletter' + save_time) === '') {
            $.ajax({
                type: 'POST',
                url: jsAOneNewsletter.data('modal-newsletter-controller'),
                data: {
                    ajax: true,
                },
                success: function(result) {
                    jsAOneNewsletter.replaceWith(result);
                    modalNewsletterConfig();
                },
                error: function(err) {
                    console.log(err);
                }
            });

            var modalNewsletterConfig = function() {
                let displayed = false,
                    popNewsletterModal = $('#aone-popup-newsletter-modal'),
                    cookie_expire = popNewsletterModal.data('hidepopup-time');
                
                $(window).on('scroll', function() {
                    if (!displayed && $(this).scrollTop() > 600) {
                        displayed = true;
                        popNewsletterModal.modal('show');
                    }
                });

                popNewsletterModal.on('hidden.bs.modal', function () {
                    let path = prestashop.urls.base_url.substring(prestashop.urls.shop_domain_url.length);
                    setCookie('aonehidepopupnewsletter' + save_time, '1', parseInt(cookie_expire), path);
                });

                $('.js-newsletter-nothanks').click(function() {
                    popNewsletterModal.modal('hide');
                    return false;
                });

                ajaxNewsletterSubscribe($('.js-popupemailsubscription .js-subscription-form'));
            };
        }
    }
}

$(window).load(function () {
    setTimeout(function() {
        aoneNewsletterLoad();
    }, 1500);
    ajaxNewsletterSubscribe($('.js-emailsubscription .js-subscription-form'));
});
