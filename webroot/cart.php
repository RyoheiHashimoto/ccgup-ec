<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'cart.php';

{
	session_start();

	$response = array();
	$db = db_connect();

	check_logined($db);

	__update($db, $response);
	$response['cart_items'] = cart_list($db, $_SESSION['user']['id']);

	if (empty($response['cart_items'])) {
		$response['error_msg'] = 'カートに商品がありません。';
	} else {
		$response['total_price'] = cart_total_price($db, $_SESSION['user']['id']);
	}

	make_token();
	
}
require_once DIR_VIEW . 'cart.php';

/**
 * @param PDO $db
 * @param array $response
 */
function __update($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	if (is_valid_token() === TRUE) {

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
	}

	$response['error_msg'] = 'リクエストが不適切です。';
	return;
}
