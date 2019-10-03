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
require_once DIR_MODEL . 'user.php';

{
	// セッション開始
	session_start();
	// dbへ接続
	$db = connect_to_db();
	// ログイン中か確認
	check_logged_in($db);
	// カート内商品の更新処理
	$messages[] = __update($db);
	// カート内商品を取得
	$cart_items = get_cart_list($db, $_SESSION['user']['user_id']);
	// カートに商品があるかチェック
	$messages[] = check_existing($cart_items, 'カート内商品');
	// 合計金額を取得
	$total_price = sum_cart($cart_items);
	// トークン発行(CSRF対策)
	make_token();
	// view読み込み
	include_once DIR_VIEW . 'cart.php';
}

// カート更新のための関数
function __update($db) {
	// POSTメソッドでなければreturn(更新しない)
	if (is_post() === FALSE) {
		return;
	}
	// トークンチェックの結果tokenキーの値が空(FALSE)ならばreturn
	if (is_valid_token() === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// actionキーの値が空ならばreturn
	if (get_post_data('action') === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// idキーの値が空ならばreturn
	if (get_post_data('cart_id') === FALSE) {
		return ['error' => '商品が指定されていません。'];
	}
	// 更新と削除の場合で分岐
	switch ($_POST['action']) {
		case 'update_cart' :
			if (get_post_data('cart_amount') === FALSE) {
				return ['error' => '商品の数量が指定されていません。'];
			}
			if (update_cart_amount($db, $_POST['cart_id'], $_POST['cart_amount']) === FALSE) {
				return ['error' => '購入数を変更できませんでした。'];
			} 
			return ['success' => '購入数を変更しました。'];
		case 'delete_cart' :
			if (delete_cart($db, $_POST['cart_id'], $_SESSION['user']['user_id']) === FALSE) {
				return ['error' => '商品を削除できませんでした。'];
			}
			return ['success' => '商品を削除しました。'];
	}
	// 上記の条件に当てはまらなかった場合
	return ['error' => 'リクエストが不適切です。'];
}