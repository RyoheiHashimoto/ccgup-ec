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
	// 購入明細リストを配列宣言
	$order_details_list = array();
	// DB接続
	$db = db_connect();
	// ログインチェック
    check_logined($db);
    // GETで送信されてきたidを代入
    $order_history_id = get_get_data('order_history_id');
    // 注文履歴データをDBより取得、変数に代入
    $order_history_data = select_order_history($db, $order_history_id);
	// 購入明細リストをDBより取得、変数に代入
	$order_details_list = order_details_select($db, $order_history_id);
	// リストより注文ごとの合計金額を算出
	if (empty($order_details_list)) {
		$response['error_msg'] = '購入明細がありません。';
	}

	include_once DIR_VIEW . 'detail.php';
}
