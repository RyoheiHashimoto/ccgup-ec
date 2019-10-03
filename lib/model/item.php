<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */

// 商品データをDBに登録
function register_items($db, $item_name, $item_img, $item_price, $item_stock, $item_status) {
	// SQL文を作成
	$sql =
	'INSERT
	INTO items(
		item_name,
		item_img,
		item_price,
		item_stock,
		item_status,
		item_create_datetime,
		item_update_datetime
	)
	VALUES(
		:item_name,
		:item_img,
		:item_price,
		:item_stock,
		:item_status,
		NOW(),
		NOW()
	);';
	// 名前付きプレースホルダー
	$params = array(
		':item_name'	=>	$item_name,
		':item_img'		=>	$item_img,
		':item_price'	=>	$item_price,
		':item_stock'	=>	$item_stock,
		':item_status'	=>	$item_status
	);
	// 上記の内容でDBを更新
	return update_db($db, $sql, $params);
}

// 商品データをDBより削除
function delete_item($db, $item_id) {
	// 商品の画像データを削除、失敗すればFALSEを返す
	if (delete_item_img($db, $item_id) === FALSE) {
		return FALSE;
	}
	// SQL文を作成
	$sql =
	'DELETE
	FROM
		items
	WHERE
		item_id = ?;';
	// プレースホルダ
	$params = array($item_id);
	// 上記の内容でDBを更新
	return update_db($db, $sql, $params);
}

// 商品の画像データを削除
function delete_item_img($db, $item_id) {
	// 指定のitem_idに該当する商品データを取得
	$row = get_item($db, $item_id);
	// 該当の商品データが存在しなければ、FALSEを返す
	if (empty($row)) return FALSE;
	// 商品画像を削除、結果(boolean)を返す
	return @unlink(DIR_IMG_FULL . $row['item_img']);
}

// 第三引数$orderのデフォルト値を空文字とする
// GETで何も渡されていない場合は必然的に空文字となるので最新順がデフォルトとなる
// 商品データを取得
function get_items($db, $active_only = true, $order = '') {
	// SQL文を作成
	$sql =
	'SELECT
		item_id,
		item_name,
		item_price,
		item_img,
		item_stock,
		item_status,
		item_create_datetime,
		item_update_datetime
	FROM
		items';
	// マジックナンバー出現
	// 1や2ではなく直接ASCやDESCとする
	// 後々引数を追加することを考える
	// 変数名は基本的に名詞　sort→orderなど
	// 公開ステータスのみの商品を指定
	if ($active_only) {
		$sql .= ' WHERE item_status =' . ACTIVE;
	}
	// 引数$orderの値によりORDER句を分岐
	if ($order === '') {
		$sql .= ' ORDER BY item_create_datetime DESC';
	}
	if ($order === 'ASC') {
		$sql .= ' ORDER BY item_price ASC';
	}
	if ($order === 'DESC') {
		$sql .= ' ORDER BY item_price DESC';
	}
	// 指定されたデータをすべて取得	
	return get_rows($db, $sql);
}

// 商品データを1商品分だけ取得
function get_item($db, $item_id) {
	// SQL文を作成
	$sql =
	'SELECT
		item_id,
		item_name,
		item_price,
		item_img,
		item_stock,
		item_status,
		item_create_datetime,
		item_update_datetime
	FROM
		items
	WHERE
		item_id = ?;';
	// プレースホルダ
	$params = array($item_id);
	// 指定されたデータを1行取得
	return get_row($db, $sql, $params);
}

// 購入分の商品の在庫を減らす
function reduce_item_stock($db, $carts) {
	// カート内商品の個数分在庫数を更新する
	foreach ($carts as $cart) {
		update_item_stock($db, $cart['item_id'], $cart['item_stock'] - $cart['cart_amount']);
	}
}

// 商品在庫数の更新
function update_item_stock($db, $item_id, $item_stock) {
	// SQL文を作成
	$sql = 
	'UPDATE
		items
 	SET
	 	item_stock = ?,
		item_update_datetime = NOW()
	WHERE
		item_id = ?;';

	$params = array($item_stock, $item_id);
	// 上記の内容でDBを更新
	return update_db($db, $sql, $params);
}

// 商品ステータスの更新
function update_item_status($db, $item_id, $item_status) {
	// SQL文を作成
	$sql =
	'UPDATE
		items
 	SET
	 	item_status = ?,
		item_update_datetime = NOW()
	WHERE
		item_id = ?;';

	$params = array($item_status, $item_id); 
	// 上記の内容でDBを更新
	return update_db($db, $sql, $params);
}

// 商品ステータスが公開・非公開以外のものでないか判別
function is_valid_item_status($item_status) {
	return $item_status === ACTIVE || $item_status === INACTIVE;
}

// 商品ステータスが公開となっているか判定
function is_active_item($item) {
	return $item['item_status'] === ACTIVE;
}