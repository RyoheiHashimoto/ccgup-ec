<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param string $name
 * @param string $img
 * @param int $price
 * @param int $stock
 * @param int $status
 * @return number
 */
function item_regist($db, $name, $img, $price, $stock, $status) {
	$sql =
	'INSERT INTO items (name, img, price, stock, status, create_date, update_date)
	VALUES (?, ?, ?, ?, ?, NOW(), NOW());';
	$params = array($name, $img, $price, $stock, $status); 
	return db_update($db, $sql, $params);
}

/**
 * @param PDO $db
 * @param int $id
 * @return number
 */
function item_delete($db, $id) {
	$row = item_get($db, $id);

	if (!empty($row)) {
		@unlink(DIR_IMG_FULL . $row['img']);
	}
	$sql = 'DELETE FROM items WHERE id = ' . $id;
	return db_update($db, $sql);
}

/**
 * @param PDO $db
 * @return array
 */
function item_list($db, $is_active_only = true) {
	$sql = <<<EOD
 SELECT id, name, price, img, stock, status, create_date, update_date
 FROM items
EOD;

	if ($is_active_only) {
		$sql .= " WHERE status = 1";
	}

	return db_select($db, $sql);
}

/**
 * @param PDO $db
 * @param int $id
 * @return NULL|mixed
 */
function item_get($db, $id) {
	$sql = <<<EOD
 SELECT id, name, price, img, stock, status, create_date, update_date
 FROM items
 WHERE id = {$id}
EOD;

	return db_select_one($db, $sql);
}

/**
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_stock($db, $id, $stock) {
	$sql = <<<EOD
 UPDATE items
 SET stock = {$stock}, update_date = NOW()
 WHERE id = {$id}
EOD;
	return db_update($db, $sql);
}

/**
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_saled($db, $id, $amount) {
	$sql = <<<EOD
 UPDATE items
 SET stock = stock - {$amount}, update_date = NOW()
 WHERE id = {$id}
EOD;
	return db_update($db, $sql);
}

/**
 *
 * @param PDO $db
 * @param array $cart_items
 * @return boolean
 */
function item_update_status($db, $id, $status) {
	$sql = <<<EOD
 UPDATE items
 SET status = {$status}, update_date = NOW()
 WHERE id = {$id}
EOD;
	return db_update($db, $sql);
}

/**
 * @param string $status
 * @return boolean
 */
function item_valid_status($status) {
	return "0" === (string)$status || "1" === (string)$status;
}
