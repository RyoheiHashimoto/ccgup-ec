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
require_once DIR_MODEL . 'cart.php';
require_once DIR_MODEL . 'item.php';
require_once DIR_MODEL . 'user.php';

// 処理開始
{
	// セッション開始・再開
	session_start();
	// DB接続、DB情報を変数に代入
	$db = connect_to_db();
	// カート投入処理
	$messages[] = __register($db);
	// GETで送信されてきたorderのvalueを変数に代入
	$order = get_get_data('order');
	// DBより商品一覧テーブルを取得し配列に代入
	$items = get_items($db, true, $order);
	// トークン発行(CSRF対策)
	make_token();
	// view読み込み
	include_once DIR_VIEW  . 'top.php';
}

// カート投入処理
function __register($db) {
	// POSTメソッドでなければreturn(更新しない)
	if (is_post() === FALSE) {
		return;
	}
	// ログイン済であるかチェック
	check_logged_in($db);
	// トークンチェック(CSRF対策)
	if (is_valid_token() === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// 商品idがPOSTされているかチェック
	if (get_post_data('item_id') === FALSE) {
		return ['error' => '商品の指定が不適切です。'];
	}
	// カートに商品を登録
	if (register_cart($db, $_SESSION['user']['user_id'], $_POST['item_id'])) {
		return ['success' => 'カートに登録しました。'];
	}
	return ['error' => 'カート登録に失敗しました。'];
}