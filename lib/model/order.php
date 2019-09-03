<?php

// 購入履歴登録(DB情報、ユーザID)
// 購入ごとの投入のため一行ずつ
function register_order_history($db, $user_id) {
    $sql =
    'INSERT
    INTO
        order_histories (user_id, order_datetime)
    VALUES
        (?, NOW());';
    $params = array($user_id);
    return update_db($db, $sql, $params);
}

// 購入明細投入(カート情報登録)(DB情報、履歴のID、購入時のカート内容)
// カート内の商品ごとに行が増えていくため、foreach文でカート内容一行ごとからカートの商品一行ごとの情報とする
function register_order_details($db, $order_history_id, $cart_items) {
    foreach($cart_items as $cart_item) {
        // 購入明細投入(カート内商品登録)(DB情報、)
        register_order_detail($db, $order_history_id, $cart_item['item_id'], $cart_item['cart_amount']);
    }
}

// order_detailsにはhistory_idとitem_idとpurchase_quantityが必要
// history_idはhistories、item_idはitems
// purchase_quantityは商品ごとの購入数量
function register_order_detail($db, $order_history_id, $item_id, $purchase_quantity) {
    $sql =
    'INSERT
    INTO
        order_details (order_history_id, item_id, purchase_quantity)
    VALUES
        (?, ?, ?);';
    $params = array($order_history_id, $item_id, $purchase_quantity);
    update_db($db, $sql, $params);
}

// 注文履歴を取得する関数(DB情報、ユーザーID)
// 該当ユーザーIDに紐づく注文履歴を取得
function get_order_histories($db, $user_id) {
    $sql =
    'SELECT 
        order_histories.order_history_id,
        order_histories.order_datetime,
        SUM(items.item_price * order_details.purchase_quantity) AS total_price
    FROM
        order_histories
    INNER JOIN
        order_details
    ON
        order_histories.order_history_id = order_details.order_history_id
    INNER JOIN
        items
    ON
        order_details.item_id = items.item_id
    WHERE
        order_histories.user_id = ?
    GROUP BY
        order_histories.order_history_id
    ORDER BY
        order_datetime DESC;';
    $params = array($user_id);
    return get_rows($db, $sql, $params);
}

function get_order_history($db, $order_history_id) {
    $sql =
    'SELECT
        order_histories.order_datetime,
        SUM(items.item_price * order_details.purchase_quantity) AS total_price
    FROM
        order_histories
    INNER JOIN
        order_details
    ON
        order_histories.order_history_id = order_details.order_history_id
    INNER JOIN
        items
    ON
        order_details.item_id = items.item_id
    WHERE
        order_histories.order_history_id = ?
    GROUP BY
        order_histories.order_history_id;';
    $params = array($order_history_id);
    return get_row($db, $sql, $params);
}

// 購入明細を取得する関数(DB情報、注文履歴ID)
function get_order_details ($db, $order_history_id) {
    $sql =
    'SELECT
        items.item_name,
        items.item_price,
        order_details.purchase_quantity,
        order_details.purchase_quantity * items.item_price AS subtotal
    FROM
        order_details
    INNER JOIN
        items
    ON
        order_details.item_id = items.item_id
    WHERE
        order_details.order_history_id = ?;';
    $params = array($order_history_id);
    return get_rows($db, $sql, $params);
}