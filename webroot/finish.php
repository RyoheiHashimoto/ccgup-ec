<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'cart.php';
require_once DIR_MODEL . 'item.php';
require_once DIR_MODEL . 'order.php';

{
	session_start();

	$response = array();
	$db = db_connect();

	check_logined($db);

	__finish($db, $response);

	make_token();

	include_once DIR_VIEW . 'finish.php';
}


/**
 * @param PDO $db
 * @param array $response
 */
function __finish($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}

	if (is_valid_token() === FALSE) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}
	// cart_list関数でカート内の商品を参照し、$response['cart_items']に代入
	$response['cart_items'] = cart_list($db, $_SESSION['user']['id']);
	// カートの中身が空かチェック
	if (empty($response['cart_items'])) {
		$response['error_msg'] = 'カートに商品がありません。';
		return;
	}
	// $response['total_price'] = cart_total_price($db, $_SESSION['user']['id']);
	$response['total_price'] = cart_sum($db, $response['cart_items']);

    $db->beginTransaction();
    try {
        // 在庫を減らす処理
        item_sold($db, $response['cart_items']);
        // 購入履歴を追加(購入履歴テーブルに追加)
        order_history_regist($db, $_SESSION['user']['id']);
        // history_idを取得
        $order_history_id = $db->lastInsertId();
        // 購入明細を追加
        order_details_regist($db, $order_history_id, $response['cart_items']);
        // カートから商品を削除する処理
        cart_clear($db, $_SESSION['user']['id']);
        // コミット(処理確定)
        $db->commit();
        // 完了メッセージを代入
        $response['result_msg'] = 'ご購入、ありがとうございました。';
    } catch (PDOException $e) {
        // ロールバック(処理取消)
        $db->rollback();
        throw $e;
	}
}