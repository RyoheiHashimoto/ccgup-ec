<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

// カートに既に登録されているか確認
function is_registered_with_carts($db, $user_id, $item_id) {
	$sql =
	'SELECT
		item_id,
		cart_amount
	FROM
		carts
	WHERE
		user_id = ?
		AND item_id = ?;';
	$params = array($user_id, $item_id);
	$cart_items = get_rows($db, $sql, $params);
	return empty($cart_items) === FALSE;
}

// ユーザ毎のカート内商品を参照
function get_cart_list($db, $user_id) {
	$sql =
	'SELECT
		carts.
		item_id,
		item_name,
		item_price,
		item_img,
		item_stock,
		cart_id,
		cart_amount
 	FROM
	 	carts
	JOIN
		items
 	ON
	 	carts.item_id = items.item_id
	WHERE
		items.item_status = 1
		AND	user_id = ?;';
	$params = array($user_id); 
	return get_rows($db, $sql, $params);
}

// カートの1レコード
// add_cart
// 今後の実装によってはプラス1ずつとは限らない
// update_cart_amountをそのまま利用したほうが早い
// 一行取得→0ならば新規追加　1ならば更新
function register_cart($db, $user_id, $item_id) {
	$sql = '';

	if (is_registered_with_carts($db, $user_id, $item_id)) {
		$sql =
		'UPDATE
			carts
 		SET
		 	cart_amount = cart_amount + 1 ,
			cart_update_datetime = NOW()
 		WHERE
		 	user_id = ?
		AND
			item_id = ?;';
	} else {
		$sql =
		'INSERT
		INTO carts(
			user_id,
			item_id,
			cart_amount,
			cart_create_datetime,
			cart_update_datetime
		)
		VALUES
			(?, ?, 1, NOW(), NOW());';
	}
	$params = array($user_id, $item_id);
	return update_db($db, $sql, $params);
}

// カート内商品数量を更新
function update_cart_amount($db, $cart_id, $cart_amount) {
	$sql =
	'UPDATE
		carts
 	SET
	 	cart_amount = ?,
		cart_update_datetime = NOW()
 	WHERE
	 	cart_id = ?;';
	$params = array($cart_amount, $cart_id);
	return update_db($db, $sql, $params);
}

// カート内商品の削除
function delete_cart($db, $cart_id) {
	$sql =
	'DELETE
	FROM
		carts
	WHERE
		cart_id = ?;';
	$params = array($cart_id); 
	return update_db($db, $sql, $params);
}

// ユーザー毎のカート内商品の削除
function clear_user_carts($db, $user_id) {
	$sql =
	'DELETE
	FROM
		carts
	WHERE
		user_id = ?;';
	$params = array($user_id);
	return update_db($db, $sql, $params);
}

// カート内商品の合計金額を算出
function sum_cart($cart_items) {
	$sum_price = 0;
	foreach ($cart_items as $cart_item) {
		$sum_price += $cart_item['item_price'] * $cart_item['cart_amount'];
	}
	return $sum_price;
}