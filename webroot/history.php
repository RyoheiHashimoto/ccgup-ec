<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'order.php';

{
	// セッション開始
	session_start();
	// 注文履歴リストを配列宣言
	$order_histories_list = array();
	// DB接続
	$db = db_connect();
	// ログインチェック
	check_logined($db);
	// 注文履歴リストをDBより取得、変数に代入
	$order_histories_list = order_histories_select($db, $_SESSION['user']['id']);
	// リストより注文ごとの合計金額を算出
	if (empty($order_histories_list)) {
		$response['error_msg'] = '注文履歴がありません。';
	}

	include_once DIR_VIEW . 'history.php';
}
