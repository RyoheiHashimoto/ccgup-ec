<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'order.php';
require_once DIR_MODEL . 'user.php';

{
	// セッション開始
	session_start();
	// 購入明細リストを配列宣言
	$order_details_list = array();
	// DB接続
	$db = connect_to_db();
	// ログインチェック
    check_logged_in($db);
    // GETで送信されてきたidを代入
    $order_history_id = get_get_data('order_history_id');
    // 注文履歴データをDBより取得、変数に代入
    $order_history = get_order_history($db, $order_history_id);
	// 購入明細リストをDBより取得、変数に代入
	$order_details = get_order_details($db, $order_history_id);
	// 購入明細が存在するか確認
	$msg = check_existing($order_details, '購入明細');
	// view読み込み
	include_once DIR_VIEW . 'detail.php';
}
