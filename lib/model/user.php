<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

# ユーザーを参照

// ログイン中のユーザーを参照
function get_login_user($db) {
	return get_user($db, $_SESSION['user']['user_id']);
}

// 指定のユーザーを参照
function get_user($db, $user_id) {
	$sql =
	'SELECT
		user_id,
		login_id,
		password,
		is_admin
 	FROM
	 	users
	WHERE
		user_id = ?';
	$params = array($user_id);
	return get_row($db, $sql, $params);
}

// ログインIDとPWの合致するユーザーを参照
function get_registered_user($db, $login_id, $password) {
	$sql =
		'SELECT
			user_id,
			login_id,
			password,
			is_admin
		FROM
			users
		WHERE
			login_id = :login_id
			AND password = sha1(:password);';
	$params = array(
		':login_id' => $login_id,
		':password' => $password
	);
	return get_row($db, $sql, $params);
}