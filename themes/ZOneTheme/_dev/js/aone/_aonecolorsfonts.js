function ajaxLoadPreviewContent(preview_controller) {
    $.ajax({
        type: 'POST',
        url: preview_controller,
        data: {
            ajax: true,
        },
        success: function(data) {
            $('.js-previewContainer').append(data);
            colorsLivePreviewConfig();
        },
        error: function(err) {
            console.log(err);
        }
    });

    var colorsLivePreviewConfig = function() {
        var live_preview = $('#js-colorsLivePreview'),
            color_picker = live_preview.find('.js-colorPicker'),
            reset_button = live_preview.find('.js-previewReset'),
            preview_boxed = live_preview.find('.js-previewBoxed'),
            preview_wide = live_preview.find('.js-previewWide'),
            special_style = live_preview.find('.js-specialStyle');

        color_picker.each(function() {
            $(this).colpick({
                layout: 'hex',
                color: $(this).data('color'),
                onSubmit: function(hsb,hex,rgb,el){
                    $(el).css('background-color', '#' + hex);
                    
                    var styles = $(el).parent('.js-color').children('.style');
                    $.each(styles, function() {
                        var selector = $(this).children('.selector');
                        var property = $(this).children('.property');
                        var preview = $(this).children('.preview');
                        preview.html('<style>' + selector.text() + '{' + property.text() + '#' + hex + '}</style>');
                    });
                }
            });
        });

        reset_button.click(function(e) {
            e.preventDefault();
            live_preview.find('.js-color .preview').html('');
            color_picker.each(function() {
                $(this).css('background-color', $(this).data('color'));
            });
            return false;
        });
        
        preview_boxed.click(function(e) {
            e.preventDefault();
            $('body').addClass('boxed-layout');
            preview_wide.removeClass('active');
            $(this).addClass('active');
            $('.js-boxedWide .style .preview').html('<style>' + $('.js-boxedBackgroundCSS').text() + '</style>');

            return false;
        });
        preview_wide.click(function(e) {
            e.preventDefault();
            $('body').removeClass('boxed-layout');
            preview_boxed.removeClass('active');
            $(this).addClass('active');
            $('.js-boxedWide .style .preview').html('');

            return false;
        });

        if ($('body').hasClass('remove-border-radius')) {
            special_style.find('input[name="disable_border_radius"]').attr('checked', 'checked');
        }
        if ($('body').hasClass('remove-box-shadow')) {
            special_style.find('input[name="disable_box_shadow"]').attr('checked', 'checked');
        }
        if ($('#wrapper').hasClass('background-for-title')) {
            special_style.find('input[name="background_block_title"]').attr('checked', 'checked');
        }
        special_style.find('input[name="disable_border_radius"]').change(function() {
            $('body').toggleClass('remove-border-radius');
        });
        special_style.find('input[name="disable_box_shadow"]').change(function() {
            $('body').toggleClass('remove-box-shadow');
        });
        special_style.find('input[name="background_block_title"]').change(function() {
            $('#wrapper').toggleClass('background-for-title background-for-tab-title background-for-column-title');
        });
    };
}
function livePreviewColorPicker() {
    if ($('.js-previewToggle').length) {
        $('.js-previewToggle').click(function() {
            $(this).parent().toggleClass('open');
        });

        ajaxLoadPreviewContent($('.js-previewToggle').data('preview-controller'));
    }
}

$(window).load(function() {
    setTimeout(function() {
        livePreviewColorPicker();
    }, 2400);
});
