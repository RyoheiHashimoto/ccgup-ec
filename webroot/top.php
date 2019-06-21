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

{
	session_start();

	$db = db_connect();
	$response = array();

	__regist($db, $response);
	$response['items'] = item_list($db);

	make_token();
	
}
require_once DIR_VIEW  . 'top.php';

/**
 * @param PDO $db
 * @param array $response
 */
function __regist($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	check_logined($db);

	if (is_valid_token() === TRUE) {
		
		if (empty($_POST['id']) === TRUE) {
			$response['error_msg'] = '商品の指定が不適切です。';
			return;
		}

		if (cart_regist($db, $_SESSION['user']['id'], $_POST['id'])) {
			$response['result_msg'] = 'カートに登録しました。';
			return;
		}
	}
	$response['error_msg'] = 'カート登録に失敗しました。';
	return;
}
