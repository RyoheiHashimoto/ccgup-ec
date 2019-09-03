<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

function connect_to_db() {
	$dsn = 'mysql:charset=utf8;dbname=' . DB_NAME . ';host=' . DB_HOST;
	// $dbhが適切かと思われる、要修正
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

// DBの指定レコードを更新
function update_db(PDO $db, $sql, $params = array()) {
	$stmt = $db->prepare($sql);
	$result = $stmt->execute($params);
	return $result;
}

// DBから指定のレコードをすべて取得
function get_rows(PDO $db, $sql, $params = array()) {
	$stmt = $db->prepare($sql);
	$stmt->execute($params);
	if ($stmt->rowCount() === 0) {
		return array();
	}
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $rows;
}

// DBから指定のレコード一行のみを取得
function get_row(PDO $db, $sql, $params = array()) {
	$rows = get_rows($db, $sql, $params);
	if (empty($rows)) {
		return null;
	}
	return $rows[0];
}

// ファイル拡張子を取得
function get_file_extension($var_name) {
	$file_name = $_FILES[$var_name]['name'];
	return pathinfo($file_name, PATHINFO_EXTENSION);
}

// 指定の拡張子かチェック
function is_valid_file_extension($file_extension) {
	switch ($file_extension) {
		case 'jpg':
		case 'jpeg':
		case 'png':
			break;
		default:
			return FALSE;
	}
}

// ★一意のファイル名をつける
function set_unique_file_name($dir, $file_extension) {
	$unique_file_name = '';
	for ($i = 0; $i < 10; $i++) {
		$unique_file_name = md5(uniqid(mt_rand(), true)) . '.' . $file_extension;
		if (!file_exists($dir . $unique_file_name)) {
			break;
		}
	}
	return $unique_file_name;
}

// 一意のファイル名をつけてファイルを保存
function save_uploaded_file($tmp_file, $dir, $unique_file_name) {
	// アップロードされた画像ファイルを画像ディレクトリに保存
	return move_uploaded_file($tmp_file, $dir. $unique_file_name);
}

// ユーザーがログイン中かチェック
function check_logged_in($db) {
	// ユーザーのセッションが存在しなければログインページへ
	if (is_logged_in() === FALSE) {
		redirect_to(LOGIN_URL);
	};
	// 登録されていないユーザーでログイン中であればログアウト処理
	if (is_registered_user($db) === FALSE) {
		redirect_to(LOGOUT_URL);
	}
}

// ログイン済ならTRUE
function is_logged_in() {
	return isset($_SESSION['user']);
}

// 登録済のユーザーならTRUE
function is_registered_user($db) {
	$user = get_user($db, $_SESSION['user']['user_id']);
	return isset($user);
}

// 

// ユーザーの権限ごとに適切なページにリダイレクト
function redirect_to_appropriate_page() {
	if (is_admin($_SESSION['user'])) {
		redirect_to(ADMIN_URL);
	} else {
		redirect_to(TOP_URL);
	}
}

// 管理者権限のあるユーザーならTRUE
function is_admin($registered_user) {
	return !empty($registered_user['is_admin']);
}

// 指定したURLにリダイレクト
function redirect_to($url) {
	header('Location:' . $url);
	exit;
}

// POSTメソッドかどうか
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === "POST";
}

// GETメソッドで変数がセットされていることをチェック
function get_get_data($key) {
	if (isset($_GET[$key])) {
		// TRUEならば変数を返す
		return $_GET[$key];
	}
	// FALSEなら空文字を返す
	return '';
}

// トークン発行関数
function make_token() {
	// $tokenに乱数を含む一意のIDのハッシュ値を代入
	$token = sha1(uniqid(mt_rand(), true));
	// さらに$tokenを$_SESSIONのtokenキーに代入
	$_SESSION['token'] = $token;
}

// トークンが正しいか判断
function is_valid_token() {
	// $_POSTのtokenキーに値が無ければ
	if (empty($_POST['token'])) {
		// FALSEを返す
		return FALSE;
	}
	// $_SESSIONのtokenキーに値が無ければ
	if (empty($_SESSION['token'])) {
		// FALSEを返す
		return FALSE;
	}
	// 上記に合致しなければ両者を比較
	return $_SESSION['token'] === $_POST['token'];
}

// 商品名の長さが制限値の範囲内か判定
function is_valid_str_length($str, $min_length, $max_length) {
	if (mb_strlen($str) < $min_length || mb_strlen($str) > $max_length) {
		return FALSE;
	}
}

// 数字かどうかチェック
function is_number($value) {
	if (empty($value)) {
		return false;
	}
	$pattern = '/^[0-9]+$/';
	return (bool)preg_match($pattern, $value);
}

// 商品etcがあるか確認
function check_existing($items, $item_name) {
	if (empty($items)) {
		return ['err_msg' => $item_name . 'はありません。'];
	}
}

// XSS対策
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 偶数番目の商品欄かどうか判定
function is_even_number_section($section) {
	return $section % 2 === 0;
}