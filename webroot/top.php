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
	// セッション開始・再開
	session_start();
	// DB接続、DB情報を変数に代入
	$db = db_connect();
	// 配列宣言
	$response = array();
	// カート投入処理
	__regist($db, $response);
	// GETで送信されてきたvalueを変数に代入
	$sort = get_get_data('sort');
	// DBより商品一覧テーブルを取得し配列に代入
	// 
	$response['items'] = item_list($db, true, $sort);

	make_token();

	include_once DIR_VIEW  . 'top.php';
}

/**
 * @param PDO $db
 * @param array $response
 */
function __regist($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	check_logined($db);

	if (is_valid_token() === FALSE) {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}
	if (empty($_POST['id']) === TRUE) {
		$response['error_msg'] = '商品の指定が不適切です。';
		return;
	}
	if (cart_regist($db, $_SESSION['user']['id'], $_POST['id'])) {
		$response['result_msg'] = 'カートに登録しました。';
		return;
	}

	$response['error_msg'] = 'カート登録に失敗しました。';
	return;
}
