<?php
// 購入履歴登録(DB情報、ユーザID)
// 購入ごとの投入のため一行ずつ
function order_history_regist($db, $user_id) {
    $sql =
    'INSERT INTO order_histories (user_id, order_datetime)
    VALUES (?, NOW());';
    $params = array($user_id);
    return db_update($db, $sql, $params);
}
// 購入明細投入(カート情報登録)(DB情報、履歴のID、購入時のカート内容)
// カート内の商品ごとに行が増えていくため、foreach文でカート内容一行ごとからカートの商品一行ごとの情報とする
function order_details_regist($db, $order_history_id, $cart_items) {
    foreach($cart_items as $cart_item) {
        // 購入明細投入(カート内商品登録)(DB情報、)
        order_detail_regist($db, $order_history_id, $cart_item['item_id'], $cart_item['amount']);
    }
}
// order_detailsにはhistory_idとitem_idとpurchase_quantityが必要
// history_idはhistories、item_idはitems
// purchase_quantityは商品ごとの購入数量
function order_detail_regist($db, $order_history_id, $item_id, $purchase_quantity) {
    $sql =
    'INSERT INTO order_details (order_history_id, item_id, purchase_quantity)
    VALUES (?, ?, ?);';
    $params = array($order_history_id, $item_id, $purchase_quantity);
    db_update($db, $sql, $params);
}
// 注文履歴を取得する関数(DB情報、ユーザーID)
// 該当ユーザーIDに紐づく注文履歴を取得
function order_histories_select ($db, $user_id) {
    $sql =
    'SELECT 
        order_histories.order_history_id,
        order_histories.order_datetime,
        SUM(items.price * order_details.purchase_quantity) AS total_price
    FROM order_histories
    INNER JOIN order_details
        ON order_histories.order_history_id = order_details.order_history_id
    INNER JOIN items
        ON order_details.item_id = items.id
    WHERE order_histories.user_id = ?
    GROUP BY order_histories.order_history_id
    ORDER BY order_datetime DESC;';
    $params = array($user_id);
    return db_select($db, $sql, $params);
}

// 購入明細を取得する関数(DB情報、注文履歴ID)
function order_details_select ($db, $order_history_id) {
    $sql =
    'SELECT
        items.name,
        items.price,
        order_details.purchase_quantity,
        order_details.purchase_quantity * items.price AS subtotal
    FROM order_details
    INNER JOIN items
        ON order_details.item_id = items.id
    WHERE order_details.order_history_id = ?;';
    $params = array($order_history_id);
    return db_select($db, $sql, $params);
}

function select_order_history ($db, $order_history_id) {
    $sql =
    'SELECT
        order_histories.order_datetime,
        SUM(items.price * order_details.purchase_quantity) AS total_price
    FROM order_histories
    INNER JOIN order_details
        ON order_histories.order_history_id = order_details.order_history_id
    INNER JOIN items
        ON order_details.item_id = items.id
    WHERE order_histories.order_history_id = ?
    GROUP BY order_histories.order_history_id;';
    $params = array($order_history_id);
    return db_select_one($db, $sql, $params);
}