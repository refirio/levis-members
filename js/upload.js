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
            success: function() {
                $('#upload > p').html('アップロードしました。');

                var file = $('#upload form input[name="target"]').val();

                window.parent.$('img#' + file).attr('src', window.parent.$('img#' + file).attr('src') + '&' + new Date().getTime());
                window.parent.$('#' + file + '_menu').show();
                window.parent.$.fn.subwindow.close();
            },
            error: function(message) {
                $('#upload > p').html('アップロード失敗：' + message);
            },
        });
    }

});
