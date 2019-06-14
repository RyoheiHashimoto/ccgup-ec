<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @param PDO $db
 * @param int $login_id
 * @param string $password
 * @return NULL|array
 */
function user_get_login($db, $login_id, $password) {
	$sql =
	'SELECT id, login_id, password, is_admin, create_date, update_date
 	FROM users
	WHERE login_id = ? AND password = ?;';
	$params = array($login_id, $password); 
	return db_select_one($db, $sql, $login_id, $password);
}

/**
 * @param PDO $db
 * @param int $id
 * @return NULL|array
 */
function user_get($db, $id) {
	$sql =
	'SELECT id, login_id, password, is_admin, create_date, update_date
 	FROM users
	WHERE id = ?;';
	$params = array($id);
	return db_select_one($db, $sql, $params);
}
