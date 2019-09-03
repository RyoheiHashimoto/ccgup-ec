<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
// config読み込み
require_once '../lib/config/const.php';

// model読み込み
require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'user.php';

// 処理開始
{
	// セッション開始
	session_start();
	// DB接続、ハンドルを変数に代入
	$db = connect_to_db();
	// ユーザーがログイン中かどうかチェック
	__check_logged_in($db); 
	// ログイン認証
	$msg = __authenticate_user($db);
	// トークン発行(CSRF対策)
	make_token();
	// view読み込み
	include_once DIR_VIEW  . 'login.php';
}

// ユーザーがログイン中かチェック
function __check_logged_in($db) {
	// ユーザーのセッションが存在しなければ処理を中止→ユーザー認証へ
	if (is_logged_in() === FALSE) {
		return;
	};
	// 登録されていないユーザーでログイン中であればログアウト処理
	if (is_registered_user($db) === FALSE) {
		redirect_to(LOGOUT_URL);
	}
	// 上記に当てはまらなければユーザーの権限ごとに適切なページにリダイレクト
	redirect_to_appropriate_page();
}

// ユーザー認証
function __authenticate_user($db) {
	// POSTメソッドがなければ以下の処理はしない
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		return;
	}
	// トークンチェック(CSRF対策)
	if (is_valid_token() === FALSE) {
		return ['error_msg' => 'リクエストが不適切です。'];
	}
	if (!isset($_POST['login_id'], $_POST['password'])) {
		return ['error_msg' => 'リクエストが不適切です。'];
	}
	// フォームからPOSTされたIDとPWで、登録済みユーザーを参照
	$registered_user = get_registered_user($db, $_POST['login_id'], $_POST['password']);
	// 該当のユーザーが無ければ処理を中止
	if (empty($registered_user)) {
		return ['error_msg' => 'IDまたはパスワードが違います。'];
	}
	// 上記チェックで問題なければセッション変数にユーザー情報を代入
	$_SESSION['user'] = $registered_user;
	// 上記に当てはまらなければユーザーの権限ごとに適切なページにリダイレクト
	redirect_to_appropriate_page();
}