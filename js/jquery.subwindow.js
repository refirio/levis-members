(function($) {
    $.fn.subwindow = function(option) {
        var settings = $.extend({
            option: null,
            width: 400,
            height: 300,
            loading: 'loading',
            close: 'close',
            fade: 0
        }, option);

        $.fn.subwindow.settings = settings;

        if ($('#subwindow').length == 0) {
            $('body').append('<div id="subwindow" style="display:none;"><div id="subwindow_overlay"></div><div id="subwindow_foundation"></div></div>');

            $('#subwindow_overlay').on('click', function() {
                $.fn.subwindow.close();
            });
            $(document).on('click', '#subwindow_close', function() {
                $.fn.subwindow.close();
            });
        }

        $(this).on('click', function() {
            $.fn.subwindow.open(this.getAttribute('href'), this.getAttribute('title'), settings.option, settings.width, settings.height, settings.loading, settings.close, settings.fade);

            return false;
        });

        return this;
    };

    $.fn.subwindow.open = function(url, title, option, width, height, loading, close, fade) {
        if (option == 'null') {
            option = $.fn.subwindow.settings.option;
        }
        if (width == undefined) {
            width = $.fn.subwindow.settings.width;
        }
        if (height == undefined) {
            height = $.fn.subwindow.settings.height;
        }
        if (title == undefined) {
            title = $.fn.subwindow.settings.title;
        }
        if (loading == undefined) {
            loading = $.fn.subwindow.settings.loading;
        }
        if (close == undefined) {
            close = $.fn.subwindow.settings.close;
        }
        if (fade == undefined) {
            fade = $.fn.subwindow.settings.fade;
        }

        $('#subwindow_foundation').css('margin', 'auto').html('<div id="subwindow_loading">' + loading + '</div>');
        $('#subwindow').fadeIn(fade);

        title = title ? '<div id="subwindow_title">' + title + '</div>' : '';
        close = close ? '<div id="subwindow_close">' + close + '</div>' : '';

        var content = '';

        if (url.match(/\.(gif|jpeg|jpg|jpe|png)$/)) {
            var image = $(new Image());
            image.on('load', function() {
                content  = '<div id="subwindow_content" style="display:none;overflow:hidden;width:' + this.width + 'px;height:' + this.height + 'px;">';
                content += '<img src="' + url + '" width="' + this.width + '" height="' + this.height + '" alt="' + title + '" />';
                content += '</div>';

                $.fn.subwindow.show(title + close + content, this.width, this.height, fade);
            });
            image.attr('src', url);
        } else if (option) {
            $.ajax({
                type: 'get',
                url: url,
                async: false,
                cache: false,
                dataType: 'html',
                success: function(response)
                {
                    $.each($(response).filter(option.filter), function() {
                        var html = $(this).html();

                        if (option.replace) {
                            $.each(option.replace, function() {
                                html = html.split(this.key).join(this.value);
                            });
                        }

                        content = '<div id="subwindow_content" style="display:none;width:' + width + 'px;height:' + height + 'px;">' + html + '</div>';

                        $.fn.subwindow.show(title + close + content, width, height, fade);
                    });
                }
            });
        } else {
            if (url.indexOf('?') >= 0) {
                url += '&';
            } else {
                url += '?';
            }
            url += '__subwindow=' + Math.random();

            content = '<iframe src="' + url + '" frameborder="0" width="' + width + '" height="' + height + '" name="subwindow_content" id="subwindow_content" style="display:none;"></iframe>';

            $.fn.subwindow.show(title + close + content, width, height, fade);
        }
    };

    $.fn.subwindow.close = function() {
        $('#subwindow').fadeOut($.fn.subwindow.settings.fade);
    };

    $.fn.subwindow.show = function(html, width, height, fade) {
        $('#subwindow_foundation').html(
            html
        ).css({
            'marginTop': '-' + height / 2 + 'px',
            'marginLeft': '-' + width / 2 + 'px'
        });

        $('#subwindow_content').fadeIn(fade);

        $.fn.subwindow.callback();
    };

    $.fn.subwindow.callback = function() {
    };

    $.subwindow = {
        init: function(option) {
            $.fn.subwindow(option);
        },
        open: function(url, title, option, width, height, loading, close, fade) {
            $.fn.subwindow.open(url, title, option, width, height, loading, close, fade);
        },
        close: function(option) {
            $.fn.subwindow.close();
        }
    };
})(jQuery);
