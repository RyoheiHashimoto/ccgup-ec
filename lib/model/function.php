<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

/**
 * @return PDO
 */
function db_connect() {
	$dsn = 'mysql:charset=utf8;dbname=' . DB_NAME . ';host=' . DB_HOST;

	try {
		$db = new PDO($dsn, DB_USER, DB_PASS);
		$db->exec("SET NAMES 'UTF8'");
		// エラーモードの設定、例外を投げるようにする。
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// prepare機能がない場合エミュレート、今回は使わない(false)。
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
		die('db error: ' . $e->getMessage());
	}

	return $db;
}

/**
 *
 * @param PDO $db
 * @param string $sql
 * @return array
 */
function db_select(PDO $db, $sql, $params = array()) {
	$stmt = $db->prepare($sql);
	$stmt->execute($params);
	if ($stmt->rowCount() === 0) {
		return array();
	}
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
}

/**
 * @param PDO $db
 * @param string $sql
 * @return NULL|mixed
 */
function db_select_one(PDO $db, $sql, $params = array()) {
	$rows = db_select($db, $sql, $params);
	if (empty($rows)) {
		return null;
	}
	return $rows[0];
}

/**
 *
 * @param PDO $db
 * @param string $sql
 * @return bool
 */
function db_update(PDO $db, $sql, $params = array()) {
	$stmt = $db->prepare($sql);

	$result = $stmt->execute($params);
	var_dump($sql);
	var_dump($result);
	return $result;
}

/**
 *
 * @param mixed $value
 * @return boolean
 */
function is_number($value) {
	if (empty($value)) {
		return false;
	}
	$pattern = '/^[0-9]+$/';
	return (bool)preg_match($pattern, $value);
}

function save_upload_file($dir, $varname, &$errors) {

	if (is_uploaded_file($_FILES[$varname]['tmp_name']) === false) {
		$errors = 'ファイルを選択してください';
		return null;
	}

	$user_file_name = $_FILES[$varname]['name'];

	// 画像の拡張子取得
	$extension = pathinfo($user_file_name, PATHINFO_EXTENSION);

	switch ($extension) {
		case 'jpg':
		case 'jpeg':
		case 'png':
			break;
		default:
			$errors = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です。';
			return null;
	}

	$save_file_name = '';
	for ($i = 0; $i < 10; $i++) {
		$save_file_name= md5(uniqid(mt_rand(), true)) . '.' . $extension;
		if (!file_exists($dir . $save_file_name)) {
			break;
		}
	}

	// ファイルを移動し保存
	if (move_uploaded_file($_FILES[$varname]['tmp_name'], $dir. $save_file_name) !== TRUE) {
		$errors = 'ファイルアップロードに失敗しました。';
		return null;
	}
	return $save_file_name;
}

/**
 * @param PDO $db
 */
function check_logined($db) {
	if (empty($_SESSION['user'])) {
		header('Location: ./login.php');
		exit;
	}

	require_once DIR_MODEL . 'user.php';

	$user = user_get($db, $_SESSION['user']['id']);
	if (empty($user)) {
		header('Location: ./logout.php');
		exit;
	}
}

function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function make_token() {
	// $tokenに乱数を含む一意のIDのハッシュ値を代入
	$token = sha1(uniqid(mt_rand(), true));
	// さらに$tokenを$_SESSIONのtokenキーに代入
	$_SESSION['token'] = $token;
}

function is_valid_token() {
	// $_POSTのtokenキーに値が無ければ
	if (empty($_POST['token'])) {
		// falseを返す
		return false;
	}
	// $_SESSIONのtokenキーに値が無ければ
	if (empty($_SESSION['token'])) {
		// falseを返す
		return false;
	}
	// 上記に合致しなければtrueを返す
	return $_SESSION['token'] === $_POST['token'];
}