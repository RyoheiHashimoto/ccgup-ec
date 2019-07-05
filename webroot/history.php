<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'cart.php';
require_once DIR_MODEL . 'order.php';

{
	session_start();

	$order_histories_list = array();
	$db = db_connect();

	check_logined($db);

	// __update($db, $response);
	
	$order_histories_list = order_histories_select($db, $_SESSION['user']['id']);
    var_dump($order_histories_list);

	if (empty($order_histories_list)) {
		$response['error_msg'] = '購入履歴がありません。';
	}

	make_token();

	include_once DIR_VIEW . 'history.php';
}

/**
 * @param PDO $db
 * @param array $response
 */
function __update($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}
	if (is_valid_token() === FALSE) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}
	if (empty($_POST['action'])) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}
	if (empty($_POST['id'])) {
		$response['error_msg'] = '商品が指定されていません。';
		return;
	}
	switch ($_POST['action']) {
		case 'update' :
			if (cart_update($db, $_POST['id'], $_SESSION['user']['id'], $_POST['amount'])) {
				$response['result_msg'] = '購入数を変更しました。';
			} else {
				$response['error_msg'] = '購入数を変更に失敗しました。';
			}
			return;
		case 'delete' :
			if (cart_delete($db, $_POST['id'], $_SESSION['user']['id'])) {
				$response['result_msg'] = 'カートから削除しました。';
			} else {
				$response['error_msg'] = '削除に失敗しました。';
			}
			return;
	}
	$response['error_msg'] = 'リクエストが不適切です。';
	return;
}
