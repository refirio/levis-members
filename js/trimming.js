$(document).ready(function() {

    /*
     * トリミング
     */
    if ($('#trimming').size() > 0) {
        // 画像サイズを問い合わせ
        $.ajax({
            type: 'get',
            url: $('form input[name="image"]').val(),
            cache: false,
            data: '_type=json',
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'OK') {
                    // トリミング対象
                    $('#trimming').css({
                        width: response.width + 2,
                        height: response.height + 2,
                        backgroundImage: 'url(' + $('form input[name="image"]').val() + '&' + new Date().getTime() + ')',
                        backgroundRepeat: 'no-repeat'
                    });

                    // 選択範囲
                    $('#scope').css({
                        width: response.width,
                        height: response.height
                    });
                    $('#scope').draggable({
                        containment: 'parent',
                        drag: function(e, ui) {
                            $('form input[name="trimming[left]"]').val(Math.round(ui.position.left));
                            $('form input[name="trimming[top]"]').val(Math.round(ui.position.top));
                        },
                        stop: function(e, ui) {
                            $('form input[name="trimming[left]"]').val(Math.round(ui.position.left));
                            $('form input[name="trimming[top]"]').val(Math.round(ui.position.top));
                        }
                    }).resizable({
                        containment: 'parent',
                        handles: 'all',
                        minWidth: response.width < 10 ? response.width : 10,
                        minHeight: response.height < 10 ? response.height : 10,
                        maxWidth: response.width,
                        maxHeight: response.height,
                        resize: function(e, ui) {
                            $('form input[name="trimming[left]"]').val(Math.round(ui.position.left));
                            $('form input[name="trimming[top]"]').val(Math.round(ui.position.top));
                            $('form input[name="trimming[width]"]').val(Math.round(ui.size.width));
                            $('form input[name="trimming[height]"]').val(Math.round(ui.size.height));
                        },
                        stop: function(e, ui) {
                            $('form input[name="trimming[left]"]').val(Math.round(ui.position.left));
                            $('form input[name="trimming[top]"]').val(Math.round(ui.position.top));
                            $('form input[name="trimming[width]"]').val(Math.round(ui.size.width));
                            $('form input[name="trimming[height]"]').val(Math.round(ui.size.height));
                        }
                    });

                    // 初期化
                    var trimming = $('#trimming');
                    var trimming_position = trimming.position();

                    var scope = $('#scope');
                    var scope_position = scope.position();

                    $('form input[name="trimming[left]"]').val(Math.round(scope_position.left - trimming_position.left));
                    $('form input[name="trimming[top]"]').val(Math.round(scope_position.top - trimming_position.top));
                    $('form input[name="trimming[width]"]').val(Math.round(scope.width()));
                    $('form input[name="trimming[height]"]').val(Math.round(scope.height()));
                } else {
                    window.alert('予期しないエラーが発生しました。');
                }
            },
            error: function(request, status, errorThrown) {
                console.log(request);
                console.log(status);
                console.log(errorThrown);
            }
        });

        $('form input[type="text"]').keyup(function() {
            // 選択範囲を更新
            $('#scope').css({
                left: $('form input[name="trimming[left]"]').val() + 'px',
                top: $('form input[name="trimming[top]"]').val() + 'px',
                width: $('form input[name="trimming[width]"]').val() + 'px',
                height: $('form input[name="trimming[height]"]').val() + 'px'
            });
        });
    }

});
