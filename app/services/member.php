<?php

/*********************************************************************

 Functions for Member

*********************************************************************/

function member_export()
{
	//名簿を取得
	$members = select_members(array(
		'where'    => 'members.public = 1',
		'order_by' => 'members.id'
	), array(
		'associate' => true
	));

	//CSV形式に整形
	$data  = mb_convert_encoding('"ID","登録日時","更新日時","削除","クラスID","名前","名前（フリガナ）","成績","生年月日","メールアドレス","電話番号","メモ","画像1","画像2","公開","クラス名"', 'SJIS-WIN', 'UTF-8');
	$data .= "\n";

	foreach ($members as $member) {
		$flag = false;
		foreach ($member as $key => $value) {
			if ($flag) {
				$data .= ',';
			}

			if ($key == 'grade') {
				$value = $GLOBALS['options']['member']['grades'][$value];
			} elseif ($key == 'public') {
				$value = $GLOBALS['options']['member']['publics'][$value];
			}

			$data .= '"' . ($value != '' ? str_replace('"', '""', mb_convert_encoding($value, 'SJIS-WIN', 'UTF-8')) : '') . '"';

			$flag = true;
		}
		$data .= "\n";
	}

	return $data;
}
