$(document).ready(function() {

	/*
	 * アップロードファイルの処理
	 */
	if ($('.upload').size() > 0) {
		//アップロードファイルを削除
		var image_delete = function(i) {
			i = ('0' + i).slice(-2);

			return function(e) {
				if (window.confirm('本当に削除してもよろしいですか？')) {
					$.ajax({
						type: 'get',
						url: $(this).attr('href'),
						cache: false,
						data: 'type=json',
						dataType: 'json',
						success: function(response) {
							if (response.status == 'OK') {
								$('img#image_' + i).attr('src', window.parent.$('img#image_' + i).attr('src') + '?' + new Date().getTime());
								$('#image_' + i + '_menu').hide();
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
				}

				return false;
			};
		};

		//初期化
		for (var i = 1; i <= $('.upload').size(); i++) {
			i = ('0' + i).slice(-2);

			$('#image_' + i + '_menu').hide();
			$('a#image_' + i + '_delete').click(image_delete(i));
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
	$('a.image_upload').subwindow({
		width: 500,
		height: 400,
		loading: 'Now Loading...',
		close: '×',
		fade: 500
	});
	$('a.image_process').subwindow({
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
					if (response.status != 'OK') {
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
