<?php

/*********************************************************************

 Functions for Validator

*********************************************************************/

/*
 * 必須
 */
function validator_required($data)
{
	if ($data === '') {
		return false;
	} else {
		return true;
	}
}

/*
 * 最小長
 */
function validator_min_length($data, $min)
{
	if (mb_strlen($data, MAIN_INTERNAL_ENCODING) < $min) {
		return false;
	} else {
		return true;
	}
}

/*
 * 最大長
 */
function validator_max_length($data, $max)
{
	if (mb_strlen($data, MAIN_INTERNAL_ENCODING) > $max) {
		return false;
	} else {
		return true;
	}
}

/*
 * 指定された範囲の長さ
 */
function validator_between($data, $min, $max)
{
	if (mb_strlen($data, MAIN_INTERNAL_ENCODING) < $min || mb_strlen($data, MAIN_INTERNAL_ENCODING) > $max) {
		return false;
	} else {
		return true;
	}
}

/*
 * 英字
 */
function validator_alpha($data)
{
	if (!preg_match('/^[_a-zA-Z]+$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 数字
 */
function validator_numeric($data)
{
	if (!preg_match('/^\d+$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 数値（マイナスや少数も許可）
 */
function validator_decimal($data)
{
	if (!is_numeric($data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 英数字
 */
function validator_alpha_numeric($data)
{
	if (!preg_match('/^\w+$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 英数字・アンダーバー・ダッシュ
 */
function validator_alpha_dash($data)
{
	if (!preg_match('/^[\w\-]+$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 等しい
 */
function validator_equals($data, $value)
{
	if ($data !== $value) {
		return false;
	} else {
		return true;
	}
}

/*
 * 以下
 */
function validator_max($data, $max)
{
	if ($data > $max) {
		return false;
	} else {
		return true;
	}
}

/*
 * 以上
 */
function validator_min($data, $min)
{
	if ($data < $min) {
		return false;
	} else {
		return true;
	}
}

/*
 * 指定された範囲の数値
 */
function validator_range($data, $min, $max)
{
	if ($data < $min || $data > $max) {
		return false;
	} else {
		return true;
	}
}

/*
 * 未入力もしくはホワイトスペース
 */
function validator_blank($data)
{
	if (!preg_match('/^\s*$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * ブール値（true, false, 1, 0）
 */
function validator_boolean($data)
{
	if (!is_bool($data) && !preg_match('/^(0|1)$/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * カスタム正規表現
 */
function validator_regexp($data, $regexp)
{
	if (!preg_match('/' . $regexp . '/', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * 日付
 */
function validator_date($data)
{
	if (!preg_match('/^(\d\d\d\d)\-(\d\d)\-(\d\d)$/', $data, $matches)) {
		return false;
	} elseif (!checkdate($matches[2], $matches[3], $matches[1])) {
		return false;
	} else {
		return true;
	}
}

/*
 * 時間
 */
function validator_time($data)
{
	if (!preg_match('/^[0-2][0-9]\:[0-5][0-9]\:[0-5][0-9]$/', $data)) {
		return false;
	} else {
		return true;
	}

	return true;
}

/*
 * 日時
 */
function validator_datetime($data)
{
	list($date, $time) = explode(' ', $data);

	if (!validator_date($date) || !validator_time($time)) {
		return false;
	} else {
		return true;
	}
}

/*
 * メールアドレス
 */
function validator_email($data)
{
	if (!preg_match('/^[^@\s]+@[^@\s]+$/', $data) || !validator_max_length($data, 256)) {
		return false;
	} else {
		return true;
	}
}

/*
 * URL
 */
function validator_url($data)
{
	if (!preg_match('/^https?\:\/\//', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * ひらがな
 */
function validator_hiragana($data)
{
	if (!preg_match('/^[ぁ-ん]+$/u', $data)) {
		return false;
	} else {
		return true;
	}
}

/*
 * カタカナ
 */
function validator_katakana($data)
{
	if (!preg_match('/^[ァ-ヶー]+$/u', $data)) {
		return false;
	} else {
		return true;
	}
}
