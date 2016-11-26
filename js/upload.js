$(document).ready(function() {

    /*
     * ファイルアップロード
     */
    if ($('#upload').size() > 0) {
        $(document).on('drop', function(e) {
            return false;
        }).on('dragover', function(e) {
            return false;
        });

        $('#upload').upload({
            form: $('#upload form').get(0),
            url: $('#upload form').attr('action'),
            name: 'files[]',
            dragover: function() {
                $('#upload').addClass('dragover');
            },
            dragleave: function() {
                $('#upload').removeClass('dragover');
            },
            initialize: function() {
                $('#upload').removeClass('dragover');
                $('#upload > p').html('アップロードを開始します。');
            },
            progress: function(total, loaded, percent) {
                $('#upload > p').html('アップロード中：進捗' + percent + '%' + (total ? '（' + Math.round(total / 1024) + 'KB 中 ' + Math.round(loaded / 1024) + 'KB）' : ''));
            },
            success: function(response) {
                // トークンを更新
                $('form input.token').val(response.values.token);
                $('a.token').each(function() {
                    $(this).attr('href', $(this).attr('href').replace(/_token=\w+/, '_token=' + response.values.token));
                });

                // 正常終了
                $('#upload > p').html('アップロードしました。');

                var file = $('#upload form input[name="key"]').val();

                window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
                window.parent.$('#' + file + '_menu').show();
                window.parent.$.fn.subwindow.close();
            },
            error: function(response) {
                // トークンを更新
                $('form input.token').val(response.values.token);
                $('a.token').each(function() {
                    $(this).attr('href', $(this).attr('href').replace(/_token=\w+/, '_token=' + response.values.token));
                });

                // エラーを表示
                $('#upload > p').html('アップロード失敗：' + response.message);
            },
        });
    }

});
