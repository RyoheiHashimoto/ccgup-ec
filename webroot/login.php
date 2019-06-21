<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
require_once '../lib/config/const.php';

require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'user.php';

{
	session_start();
	$db = db_connect();

	$response = array();

	__check_logined($db);
	__login($db, $response);

	make_token();
}
require_once DIR_VIEW  . 'login.php';

/**
 * @param PDO $db
 */
function __check_logined($db) {
	if (empty($_SESSION['user'])) {
		return;
	}

	$user = user_get($db, $_SESSION['user']['id']);
	if (empty($user)) {
		header('Location: ./logout.php');
		exit;
	}

	if (empty($_SESSION['user']['is_admin'])) {
		header('Location: ./top.php');
	} else {
		header('Location: ./admin.php');
	}
	exit;
}

/**
 * @param PDO $db
 * @param array $response
 */
function __login($db, &$response) {
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}

	if (is_valid_token() === TRUE) {

		$user = user_get_login($db, $_POST['login_id'], $_POST['password']);
		if (empty($user)) {
			$response['error_msg'] = 'IDまたはパスワードが違います。';
			return;
		}
	} else {
		$response['error_msg'] = 'リクエストが不適切です。';
		return;
	}

	$_SESSION['user'] = $user;

	if (empty($user['is_admin'])) {
		header('Location: ./top.php');
	} else {
		header('Location: ./admin.php');
	}
	exit;
}
