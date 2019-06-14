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
	$sql = <<<EOD
SELECT item_id, amount FROM carts
 WHERE user_id = {$user_id} AND item_id = {$item_id}
EOD;

	$cart = db_select($db, $sql);
	return empty($cart) === false;
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int | NULL
 */
function cart_total_price($db, $user_id) {
	$sql = <<<EOD
SELECT sum(price * amount) as total_price
 FROM carts JOIN items
 ON carts.item_id = items.id
 WHERE items.status = 1 AND user_id = {$user_id}
EOD;
	$row = db_select_one($db, $sql);
	if (empty($row)) {
		return null;
	}
	return $row['total_price'];
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return array
 */
function cart_list($db, $user_id) {
	$sql = <<<EOD
 SELECT carts.id, item_id, name, price, img, amount, (price * amount) as amount_price
 FROM carts JOIN items
 ON carts.item_id = items.id
 WHERE items.status = 1 AND user_id = {$user_id}
EOD;
	return db_select($db, $sql);
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
		$sql = <<<EOD
UPDATE carts
 SET amount = amount + 1 , update_date = NOW()
 WHERE user_id = {$user_id} AND item_id = {$item_id}
EOD;
	} else {
		$sql = <<<EOD
INSERT INTO carts (user_id, item_id, amount, create_date, update_date)
VALUES ({$user_id}, {$item_id}, 1, NOW(), NOW())
EOD;
	}
	return db_update($db, $sql);
}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @param int $amount
 * @return int
 */
function cart_update($db, $id, $user_id, $amount) {
	$sql = <<<EOD
UPDATE carts
 SET amount = {$amount}, update_date = NOW()
 WHERE id = {$id} AND user_id = {$user_id}
EOD;
	return db_update($db, $sql);
}

/**
 * @param PDO $db
 * @param int $id
 * @param int $user_id
 * @return int
 */
function cart_delete($db, $id, $user_id) {
	$sql = <<<EOD
DELETE FROM carts
 WHERE id = {$id} AND user_id = {$user_id}
EOD;
	return db_update($db, $sql);
}

/**
 * @param PDO $db
 * @param int $user_id
 * @return int
 */
function cart_clear($db, $user_id) {
	$sql = 'DELETE FROM carts WHERE user_id = ' . $user_id;
	return db_update($db, $sql);
}
