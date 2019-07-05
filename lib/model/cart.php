<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param int $user_id
 * @param int $item_id
 * @return boolean
 */
function cart_is_exists_item($db, $user_id, $item_id) {
	$sql = 'SELECT item_id, amount FROM carts
	WHERE user_id = ? AND item_id = ?;';
	$params = array($user_id, $item_id);
	$cart = db_select($db, $sql, $params);
	return empty($cart) === false;
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int | NULL
 */
// function cart_total_price($db, $user_id) {
// 	$sql =
// 	'SELECT sum(price * amount) as total_price
//  	FROM carts JOIN items
//  	ON carts.item_id = items.id
// 	WHERE items.status = 1 AND user_id = ?;';
// 	$params = array($user_id); 
// 	$row = db_select_one($db, $sql, $params);
// 	if (empty($row)) {
// 		return null;
// 	}
// 	return $row['total_price'];
// }

function cart_sum($cart_items) {
	$sum_price = 0;
	foreach ($cart_items as $cart_item) {
		// $sum_price = $sum_price + $value['price'] * $value['amount'];
		$sum_price += $cart_item['price'] * $cart_item['amount'];
	}
	return $sum_price;
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return array
 */
function cart_list($db, $user_id) {
	$sql =
	'SELECT carts.id, item_id, name, price, img, amount, (price * amount) as amount_price
 	FROM carts JOIN items
 	ON carts.item_id = items.id
	WHERE items.status = 1 AND user_id = ?;';
	$params = array($user_id); 
	return db_select($db, $sql, $params);
}

/**
 * @param PDO $db
 * @param int $user_id
 * @param int $item_id
 * @return int
 */
function cart_regist($db, $user_id, $item_id) {
	$sql = '';

	if (cart_is_exists_item($db, $user_id, $item_id)) {
		$sql =
		'UPDATE carts
 		SET amount = amount + 1 , update_date = NOW()
 		WHERE user_id = ? AND item_id = ?;';
	} else {
		$sql =
		'INSERT INTO carts (user_id, item_id, amount, create_date, update_date)
		VALUES (?, ?, 1, NOW(), NOW());';
	}
	$params = array($user_id, $item_id);
	return db_update($db, $sql, $params);
}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @param int $amount
 * @return int
 */
function cart_update($db, $id, $user_id, $amount) {
	$sql =
	'UPDATE carts
 	SET amount = ?, update_date = NOW()
 	WHERE id = ? AND user_id = ?;';
	$params = array($amount, $id, $user_id);
	return db_update($db, $sql, $params);
}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @return int
 */
function cart_delete($db, $id, $user_id) {
	$sql =
	'DELETE FROM carts
	WHERE id = ? AND user_id = ?;';
	$params = array($id, $user_id); 
	return db_update($db, $sql, $params);
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int
 */
function cart_clear($db, $user_id) {
	$sql =
	'DELETE FROM carts
	WHERE user_id = ?;';
	$params = array($user_id);
	return db_update($db, $sql, $params);
}
