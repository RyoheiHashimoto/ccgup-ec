<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
// setting読み込み
 require_once '../lib/config/const.php';
// model読み込み
require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'cart.php';
require_once DIR_MODEL . 'item.php';
require_once DIR_MODEL . 'order.php';
require_once DIR_MODEL . 'user.php';

{
	// セッション開始、または再開
	session_start();
	// DBへ接続、ハンドルを代入
	$db = connect_to_db();
	// ログインチェック
	check_logged_in($db);
	// カート内商品を取得
	$cart_items = get_cart_list($db, $_SESSION['user']['user_id']);
	// 商品の有無を確認
	$messages[] = check_existing($cart_items, 'カート内の商品');
	// 合計金額を算出
	$total_price = sum_cart($cart_items);
	// 購入処理
	$messages[] = __finish($db, $cart_items);
	// トークン発行
	make_token();
	// view読み込み
	include_once DIR_VIEW . 'finish.php';
}

function __finish($db, $cart_items) {
	// POSTメソッドであることとをチェック
	if (is_post() === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// トークンをチェック
	if (is_valid_token() === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// 購入処理へ
	return __purchase($db, $cart_items);
}

function __purchase($db, $cart_items) {
	// トランザクション開始
	$db->beginTransaction();

	try {
		// 在庫を減らす処理
		reduce_item_stock($db, $cart_items);
		// 購入履歴を追加(購入履歴テーブルに追加)
		register_order_history($db, $_SESSION['user']['user_id']);
		// history_idを取得
		$order_history_id = $db->lastInsertId();
		// 購入明細を追加
		register_order_details($db, $order_history_id, $cart_items);
		// ユーザーのカート内商品を削除する処理
		clear_user_carts($db, $_SESSION['user']['user_id']);
		// コミット(処理確定)
		$db->commit();
		// 完了メッセージを代入
		return ['success' => 'ご購入、ありがとうございました。'];
		
	} catch (PDOException $e) {
		// ロールバック(処理取消)
		$db->rollback();
		return ['error' => '購入処理が失敗しました: ' . $e->getMessage()];
	}
}