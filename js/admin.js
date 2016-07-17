$(document).ready(function() {

    /*
     * アップロードファイルの処理
     */
    if ($('.upload').size() > 0) {
        //アップロードファイルを削除
        var file_delete = function(key) {
            return function(e) {
                if (window.confirm('本当に削除してもよろしいですか？')) {
                    $.ajax({
                        type: 'get',
                        url: $(this).attr('href'),
                        cache: false,
                        data: 'type=json',
                        dataType: 'json',
                        success: function(response) {
                            //トークンを更新
                            $('form input.token').val(response.values.token);
                            $('a.token').each(function() {
                                $(this).attr('href', $(this).attr('href').replace(/token=\w+/, 'token=' + response.values.token));
                            });

                            if (response.status == 'OK') {
                                //正常終了
                                $('#' + key).attr('src', window.parent.$('#' + key).attr('src') + '&amp;' + new Date().getTime());
                                $('#' + key + '_menu').hide();
                            } else {
                                //予期しないエラー
                                window.alert('予期しないエラーが発生しました。');
                            }
                        },
                        error: function(request, status, errorThrown) {
                            console.log(request);
                            console.log(status);
                            console.log(errorThrown);
                        }
                    });
                }

                return false;
            };
        };

        //初期化
        if ($('#image_01').size() > 0) {
            $('#image_01_menu').hide();
            $('#image_01_delete').click(file_delete('image_01'));
        }
        if ($('#image_02').size() > 0) {
            $('#image_02_menu').hide();
            $('#image_02_delete').click(file_delete('image_02'));
        }
        if ($('#document').size() > 0) {
            $('#document_menu').hide();
            $('#document_delete').click(file_delete('document'));
        }

        $.ajax({
            type: 'get',
            url: $('form.validate').attr('action'),
            cache: false,
            data: 'type=json',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'OK') {
                    $.each(response.files, function(key, value) {
                        if (value != null) {
                            $('#' + key + '_menu').show();
                        }
                    });
                }
            },
            error: function(request, status, errorThrown) {
                console.log(request);
                console.log(status);
                console.log(errorThrown);
            }
        });
    }

    /*
     * サブウインドウ
     */
    $('a.file_upload').subwindow({
        width: 500,
        height: 400,
        loading: 'Now Loading...',
        close: '×',
        fade: 500
    });
    $('a.file_process').subwindow({
        width: 900,
        height: 600,
        loading: 'Now Loading...',
        close: '×',
        fade: 500
    });

    /*
     * 並び替え
     */
    $('#sortable table tbody').sortable({
        handle: 'span.handle',
        update: function(event, ui) {
            //並び替え後の順番を取得
            var sort = [];
            $.each($('#sortable table tbody').sortable('toArray'), function(i) {
                this.match(/^sort_(\d+)$/);

                sort.push('sort[' + RegExp.$1 + ']=' + (i + 1));
            });

            //登録情報を更新
            $.ajax({
                type: $('#sortable').attr('method'),
                url: $('#sortable').attr('action'),
                cache: false,
                data: 'type=json&token=' + $('#sortable').find('input[name=token]').val() + '&' + sort.join('&'),
                dataType: 'json',
                success: function(response) {
                    //トークンを更新
                    $('form input.token').val(response.values.token);
                    $('a.token').each(function() {
                        $(this).attr('href', $(this).attr('href').replace(/token=\w+/, 'token=' + response.values.token));
                    });

                    if (response.status != 'OK') {
                        //正常終了
                        window.alert(response.message);
                        window.location.reload();
                    }
                },
                error: function(request, status, errorThrown) {
                    console.log(request);
                    console.log(status);
                    console.log(errorThrown);

                    window.location.reload();
                }
            });
        }
    });

    /*
     * 一括削除
     */
    $('form input.bulk').on('change', function() {
        //削除対象を保持
        var data = {
            'type': 'json',
            'id': $(this).val(),
            'checked': $(this).prop('checked') ? 1 : 0,
            'token': $('form.delete input[name="token"]').val()
        };
        $.post($('form.delete').attr('action'), data, function(response) {
            if (response.status != 'OK') {
                window.alert('予期しないエラーが発生しました。');
            }
        }, 'json');

        return false;
    });
    $('form input.bulks').on('change', function() {
        //一括選択
        if ($(this).prop('checked')) {
            $('form input.bulks').prop('checked', true);
            $('form input.bulk').prop('checked', true);
        } else {
            $('form input.bulks').prop('checked', false);
            $('form input.bulk').prop('checked', false);
        }

        var list = {};
        $('form input.bulk').each(function() {
            list[$(this).val()] = $(this).prop('checked') ? 1 : 0;
        });

        //削除対象を保持
        var data = {
            'type': 'json',
            'list': list,
            'token': $('form.delete input[name="token"]').val()
        };
        $.post($('form.delete').attr('action'), data, function(response) {
            if (response.status != 'OK') {
                window.alert('予期しないエラーが発生しました。');
            }
        }, 'json');

        return false;
    });
    if ($('form input.bulk').size() > 0) {
        //すべて選択済みなら一括選択にチェック
        var flag = true;
        $('form input.bulk').each(function() {
            if ($(this).prop('checked') == false) {
                flag = false;
            }
        });
        if (flag == true) {
            $('form input.bulks').prop('checked', true);
        }
    }

});
