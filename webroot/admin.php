<?php
/**
 * 
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
// config読み込み
require_once '../lib/config/const.php';

// model読み込み
require_once DIR_MODEL . 'function.php';
require_once DIR_MODEL . 'item.php';
require_once DIR_MODEL . 'user.php';

// 処理開始
{
	// session_start関数をコール、セッションID新規発行orセッション再開
	session_start();
	// DB接続、ハンドルを変数に代入
	$db = connect_to_db();
	// ログイン済かチェック
	check_logged_in($db);
	// DBを更新
	$messages[] = __update($db);
	// 商品一覧を取得
	$items = get_items($db, FALSE);
	// トークン発行(CSRF対策)
	make_token();
	// view読み込み
	include_once DIR_VIEW . 'admin.php';
}

// DB更新(登録・削除・在庫数更新・ステータス更新)
function __update($db) {
    // POSTメソッドでなければ更新せずreturn
    if(is_post() === FALSE) {
		return;
    }
	// トークンチェックの結果tokenキーの値が空(FALSE)ならばreturn
	if (is_valid_token() === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// POSTされたactionにより更新動作分岐
	return __switch_update_action($db);
}

function __switch_update_action($db) {
	if (get_post_data('action') === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	switch ($_POST['action']) {
		// 商品登録へ
		case 'regist' :
			return __register($db);
		// 商品削除へ
		case 'delete' :
			return __delete($db);
		// 在庫数更新へ
		case 'update_stock' :
			return __update_stock($db);
		// ステータス変更へ
		case 'update_status' :
			return __update_status($db);
	}
	return ['error' => 'リクエストが不適切です。'];
}

// 商品登録
function __register($db) {
	// フォームに入力されたデータをチェック
	$error_messages = __check_input_item_data($_POST['item_name'], $_POST['item_price'], $_POST['item_stock'], $_POST['item_status'],  $_FILES['item_img']['tmp_name']);
	// エラーがあれば中止
	if(!empty($error_messages)) {
		return $error_messages;
	}
	// ファイル拡張子を取得
	$file_extension = get_file_extension('item_img');
	// アップロードされた画像をチェック
	$error_messages = __check_uploaded_file($_FILES['item_img']['tmp_name'], $file_extension);
	// エラーがあれば中止
	if(!empty($error_messages)) {
		return $error_messages;
	}
	// 保存用のユニークなファイル名を取得
	$unique_file_name = set_unique_file_name(DIR_IMG_FULL, $file_extension); 
	// アップロードされた画像を画像ディレクトリに保存
	if (save_uploaded_file($_FILES['item_img']['tmp_name'], DIR_IMG_FULL, $unique_file_name) === FALSE) {
		return ['error' => 'ファイルアップロードに失敗しました。'];
	}
	// 商品データを登録。失敗すればerror、成功すればresultのmessageを返す
	if (register_items($db, $_POST['item_name'], $unique_file_name, $_POST['item_price'], $_POST['item_stock'], $_POST['item_status']) === FALSE) {
		return ['error' => '商品の登録に失敗しました。'];
	}
	return ['success' => '商品を登録しました。'];
}

// フォームに入力された商品データのチェック
function __check_input_item_data($item_name, $item_price, $stock, $item_status, $tmp_img_file) {
	// 配列宣言
	$messages = array();
	if (!isset($item_name)) {
		$messages['error'][] = '商品名を指定してください。';
	// 商品名の長さが制限値の範囲外の場合はerror
	} else if (is_valid_str_length($item_name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === FALSE) {
		$messages['error'][]= '商品名は' . ITEM_NAME_LENGTH_MIN . '文字以上、' .ITEM_NAME_LENGTH_MAX. '文字以内で入力してください。';
	}
	// 商品価格が未入力空文字or数字でない場合はerror
	if (!isset($item_price) || !is_number($item_price)) {
		$messages['error'][] = '価格を数値で入力してください。';
	}
	// 在庫数が未入力から文字or数字でない場合はerror
	if (!isset($stock) || !is_number($stock)) {
		$messages['error'][] = '在庫数を数値で入力してください。';
	}
	// ステータスが指定されていない場合はerror
	if (!isset($item_status) || !is_valid_item_status((int)$item_status)) {
		$messages['error'][] = 'ステータスの指定が不適切です。';
	}
	// tempファイルがない場合はerror
	if (!isset($tmp_img_file)) {
		$messages['error'][] = '商品画像を指定してください。';
	}
	return $messages;
}

// ファイルがHTTP_POSTによりアップされたものかチェック
function __check_uploaded_file($tmp_file, $file_extension) {
	if (is_uploaded_file($tmp_file) === FALSE) {
		return ['error' => 'ファイルを選択してください'];
	}
	// ファイル拡張子が適切でない場合はerror
	if (is_valid_file_extension($file_extension) === FALSE) {
		return ['error' => 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。'];
	}
}

// 商品削除
function __delete($db) {
	// item_idがPOSTされてなければerror
	if (get_post_data('item_id') === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// 商品データを削除。失敗すればerror、成功すればresultのmessageを返す
	if (delete_item($db, $_POST['item_id']) === FALSE) {
		return ['error' => '商品の削除に失敗しました。'];
	}
	return ['success' => '商品を削除しました。'];
}

// 商品在庫数更新
function __update_stock($db) {
	// item_idがPOSTされてなければerror
	if (get_post_data('item_id') === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// 在庫数が指定されていなければerror
	if (!isset($_POST['item_stock']) || !is_number($_POST['item_stock'])) {
		return ['error' => '在庫数を数値で入力してください。'];
	}
		// 商品データの在庫数を更新。失敗すればerror、成功すればresultのmessageを返す
	if (update_item_stock($db, $_POST['item_id'], $_POST['item_stock']) === FALSE) {
		return ['error' => '在庫数の更新に失敗しました。'];
	}
	return ['success' => '在庫数を更新しました。'];
}

// 商品ステータス更新
function __update_status($db) {
	// item_idがPOSTされていなければerror
	if (get_post_data('item_id') === FALSE) {
		return ['error' => 'リクエストが不適切です。'];
	}
	// ステータスが指定されていないまたは想定外のものであった場合はerror
	if (!isset($_POST['item_status']) || !is_valid_item_status((int)$_POST['item_status'])) {
		return ['error' => 'ステータスの指定が不適切です。'];
	}
		// 商品データのステータスを更新。失敗すればerror、成功すればresultのmessageを返す
	if (update_item_status($db, $_POST['item_id'], $_POST['item_status']) === FALSE) {
		return ['error' => 'ステータスの更新に失敗しました。'];
	}
	return ['success' => 'ステータスを更新しました。'];
}