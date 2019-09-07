<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
// config読み込み
require_once '../lib/config/const.php';

// model読み込み
require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'order.php';
require_once DIR_MODEL . 'user.php';

// 処理開始
{
	// セッション開始
	session_start();
	// 注文履歴リストを配列宣言
	$order_histories = array();
	// DB接続、ハンドルを変数に代入
	$db = connect_to_db();
	// ログイン済かチェック
	check_logged_in($db);
	// 注文履歴リストをDBより取得、変数に代入
	$order_histories = get_order_histories($db, $_SESSION['user']['user_id']);
	// 注文履歴が存在するか確認
	$msg = check_existing($order_histories, '注文履歴');
	// view読み込み
	include_once DIR_VIEW . 'history.php';
}