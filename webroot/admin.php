<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'item.php';
require_once DIR_MODEL . 'user.php';

print DIR_IMG_FULL;

{
	session_start();

	$response = array();
	$db = db_connect();

	check_logined($db);

	__update($db, $response);

	$response['items'] = item_list($db, false);

	require_once DIR_VIEW . 'admin.php';
}

	/**
 *
 * @param PDO $db
 * @param array $response
 */
function __update($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== "POST") {
		return;
	}

	switch ($_POST['action']) {
		case 'regist' :
			__regist($db, $response);
			return;
		case 'delete' :
			__delete($db, $response);
			return;
		case 'update_stock' :
			__update_stock($db, $response);
			return;
		case 'update_status' :
			__update_status($db, $response);
			return;
	}

	$response['error_msg'] = 'リクエストが不適切です。';
	return;
}

/**
 *
 * @param PDO $db
 * @param array $response
 */
function __regist($db, &$response) {
	$response['error_msg'] = array();
	if (!isset($_POST['name'])) {
		$response['error_msg'][] = '商品名を指定してください。';
	} else if (mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 100) {
		$response['error_msg'][] = '商品名は３文字以上、100文字以内で入力してください。';
	}
	if (!isset($_FILES['img']['tmp_name'])) {
		$response['error_msg'][] = '商品画像を指定してください。';
	}
	if (!isset($_POST['price']) || !is_number($_POST['price'])) {
		$response['error_msg'][] = '価格を数値で入力してください。';
	}
	if (!isset($_POST['stock']) || !is_number($_POST['stock'])) {
		$response['error_msg'][] = '在庫数を数値で入力してください。';
	}
	if (!isset($_POST['status']) || !item_valid_status($_POST['status'])) {
		$response['error_msg'][] = 'ステータスの指定が不適切です。';
	}
	if (!empty($response['error_msg'])) {
		return;
	}
	$error_msg = '';
	$save_name = save_upload_file(DIR_IMG_FULL, 'img', $error_msg);
	if (empty($save_name)) {
		$response['error_msg'][] = $error_msg;
		return;
	}

	if (item_regist($db, $_POST['name'], $save_name, $_POST['price'], $_POST['stock'],
			$_POST['status'])) {
		$response['result_msg'] = '商品を登録しました。';
		return;
	}
	$response['error_msg'] = '商品の登録に失敗しました。';
}

/**
 *
 * @param PDO $db
 * @param array $response
 */
function __delete($db, &$response) {
	if (empty($_POST['id'])) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}

	if (item_delete($db, $_POST['id'])) {
		$response['result_msg'] = '商品を削除しました。';
		return;
	}
	$response['error_msg'] = '商品の削除に失敗しました。';
}

/**
 *
 * @param PDO $db
 * @param array $response
 */
function __update_stock($db, &$response) {
	if (empty($_POST['id'])) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}

	if (!isset($_POST['stock']) || !is_number($_POST['stock'])) {
		$response['error_msg'][] = '在庫数を数値で入力してください。';
		return;
	}

	if (item_update_stock($db, $_POST['id'], $_POST['stock'])) {
		$response['result_msg'] = '在庫を更新しました。';
		return;
	}
	$response['error_msg'] = '在庫の削除に失敗しました。';
}

/**
 *
 * @param PDO $db
 * @param array $response
 */
function __update_status($db, &$response) {
	if (empty($_POST['id'])) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}

	if (!isset($_POST['status']) || !item_valid_status($_POST['status'])) {
		$response['error_msg'][] = 'ステータスの指定が不適切です。';
		return;
	}

	if (item_update_status($db, $_POST['id'], $_POST['status'])) {
		$response['result_msg'] = 'ステータスを更新しました。';
		return;
	}
	$response['error_msg'] = 'ステータスの削除に失敗しました。';
}
