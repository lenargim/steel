function openSuccessPopup() {
    $.magnificPopup.close();
    $.magnificPopup.open({
        type: 'inline',
        preloader: true,
        mainClass: 'mfp-fade',
        showCloseBtn: true,
        items: {
            src: $(".js-popup-message-success")
        },
        callbacks: {
            beforeOpen: function () {
                if ($(window).width() < 700) {
                    this.st.focus = false;
                } else {
                    this.st.focus = '#name';
                }
            }
        }
    });
}

window._showPopupAdditional = function ($elem) {
    $.magnificPopup.open({
        type: 'inline',
        preloader: true,
        mainClass: 'mfp-fade',
        showCloseBtn: true,
        items: {
            src: $elem
        },
        callbacks: {
            beforeOpen: function () {
                if ($(window).width() < 700) {
                    this.st.focus = false;
                } else {
                    this.st.focus = '#name';
                }
            }
        }
    });
};


window.closePopup = function () {
    $.magnificPopup.close();
};


$(function () {


    setTimeout(function () {
        //Просто попап для форм и любого контента
        $('.js-popup-open').magnificPopup({
            type: 'ajax',
            preloader: true,
            mainClass: 'mfp-fade',
            focus: '#name',
            showCloseBtn: true,
            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            callbacks: {
                beforeOpen: function () {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '#name';
                    }

                    window.send_counter_event($(this).data('reachgoal'));
                },
                ajaxContentAdded: function () {
                    setTimeout(function () {
                        $('.tel-input').inputmask('+7(999)999-99-99');
                    }, 1000);
                }
            }
        });

    }, 0);


    $(document).on("submit", 'form[name="callback_mail"], form[name="message_mail"], form[name="feedback_mail"], form[name="order"]', function (e) {
        e.stopPropagation();
        var form_obj = $(this);
        var path = form_obj.data("ajax_valid_path");
        var form_name = $(this).attr('name');
        form_obj.addClass('js-submited');
        form_obj.find(".js_wrap_input").removeClass("error").find(".input__error").html("");
        window.send_counter_event('submit_' + form_name);
        var formData = form_obj.serialize();
        var request = {
            url: path,
            type: 'POST',
            data: formData
        };
        $.ajax(request).then(function (response) {
            var isError = false;
            var inputs = form_obj.find('input,textarea');
            if (response.hasOwnProperty('error')) {
                for (var name in response.error) {
                    inputs.each(function (key, obj) {
                        if ($(obj).attr("name") && $(obj).attr("name").indexOf(name) != -1) {
                            $(obj).closest(".js_wrap_input").addClass("error");
                            isError = true;
                        }
                    });
                }
            }
            if (!isError && response.hasOwnProperty('ok')) {
                // form_obj.find(".js_wrap_input input:not(:hidden)").val("");
                window.send_counter_event('success_send_' + form_name);
                openSuccessPopup();
            }
            return false;

        });
        return false;
    });
    $(document).on('change', '.js_wrap_input input', function () {
        $(this).closest(".js_wrap_input").removeClass("error");
    });

    $(document).on('keydown', 'input[digitonly="true"]',
        function (event) {
            var key = event.keyCode || event.which;
            key = String.fromCharCode(key);
            var regex = /[0-9]|\./;
            if (!(!event.shiftKey //Disallow: any Shift+digit combination
                && !(event.keyCode < 48 || event.keyCode > 57) //Disallow: everything but digits
                || regex.test(key) //Allow: numeric pad digits
                || key > 0
                || event.keyCode == 46 // Allow: delete
                || event.keyCode == 8  // Allow: backspace
                || event.keyCode == 9  // Allow: tab
                || event.keyCode == 27 // Allow: escape
                || (event.keyCode == 65 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+A
                || (event.keyCode == 67 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+C
                //Uncommenting the next line allows Ctrl+V usage, but requires additional code from you to disallow pasting non-numeric symbols
                //|| (event.keyCode == 86 && (event.ctrlKey === true || event.metaKey === true)) // Allow: Ctrl+Vpasting
                || (event.keyCode >= 35 && event.keyCode <= 39) // Allow: Home, End
            )) {
                event.preventDefault();
            }
    });

    $(".js-btn-more").click(function () {
        var wrapSelector = $(this).data('wrapper') || '.js-hidden-wrapper';
        var hiddenSelector = $(this).data('hidden') || '.js-hidden-text-block';
        $(this).closest(wrapSelector).find(hiddenSelector).slideToggle();
        var openText = $(this).data('open');
        var closeText = $(this).data('close');
        $(this).text(function(i, text){
            return text === closeText ? openText : closeText;
        });
    });

    $(document).on('click', 'button.js-submited[type="submit"]', function () {
        $(this)
            .closest('form')
            .addClass('js-submited');
    });


    $(document).on('click', '.js-modal__close', function(){
        $.magnificPopup.close();
    });
});
