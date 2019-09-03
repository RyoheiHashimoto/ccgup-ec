<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>購入履歴</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<?php include DIR_VIEW_ELEMENT . 'output_navber.php'; ?>
	<div class="container-fluid px-md-3">
		<div class="row">
			<div class="col-12">
                <h1>購入明細</h1>
			</div>
		</div>
<?php 
include DIR_VIEW_ELEMENT . 'output_message.php'; ?>
<?php if (!empty($order_details)) { ?>
		<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
			<div class="row">
				<table class="table">	
					<caption>
						No.<?php echo h($order_history_id); ?>-<?php echo h($order_history['order_datetime']); ?>
					</caption>
					<thead>
						<tr>
							<th>商品名</th>
							<th>商品価格</th>
							<th>購入個数</th>
							<th>小計</th>
						</tr>
					</thead>
					<tbody>
<?php foreach ($order_details as $detail_section => $order_detail) {?>
						<tr class="<?php echo h((is_even_number_section($detail_section) === TRUE) ? 'stripe' : '' ); ?>">
							<td><?php echo h($order_detail['item_name']);?></td>
							<td><?php echo h($order_detail['item_price']);?></td>
							<td><?php echo h($order_detail['purchase_quantity']);?></td>
							<td><?php echo h($order_detail['subtotal']);?>円</td>
<?php } ?>
                    </tbody>
                    <tfoot>
						<tr>
                            <td></td>
                            <td></td>
                            <td></td>
							<td>合計<?php echo h($order_history['total_price']); ?>円</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
<?php }?>
	</div>
	<!-- /.container -->
	<script src="./assets/js/jquery/1.12.4/jquery.min.js"></script>
	<script src="./assets/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>