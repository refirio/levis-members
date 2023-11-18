$(document).ready(function() {

    /*
     * 名簿の検索
     */
    if ($('form.search').length > 0) {
        var form = $('form.search');

        form.find('select[name=id]').attr('disabled', 'disabled');

        form.find('select[name=class_id]').on('change', function() {
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                cache: false,
                data: '_type=json&class_id=' + $(this).val(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'OK') {
                        var option = '<option value="">選択してください</option>';
                        $.each(response.members, function(i, data) {
                            option += '<option value="' + data.id + '">' + data.name + '</option>';
                        });
                        form.find('select[name=id]').html(option);
                        form.find('select[name=id]').removeAttr('disabled');
                    } else {
                        window.alert('予期しないエラーが発生しました。');
                    }
                },
                error: function(request, status, errorThrown) {
                    console.log(request);
                    console.log(status);
                    console.log(errorThrown);

                    window.alert('通信エラーが発生しました。');
                    window.location.reload();
                }
            });
        });
        form.on('submit', function() {
            if (form.find('select[name=id]').val() == '') {
                window.alert('名簿が選択されていません。');

                return false;
            }
            return true;
        });
    }

});
