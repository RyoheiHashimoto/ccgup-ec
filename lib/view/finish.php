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
<title>購入完了</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<?php include DIR_VIEW_ELEMENT . 'output_navber.php'; ?>

<?php include DIR_VIEW_ELEMENT . 'output_message.php'; ?>

<?php if (!empty($cart_items)) { ?>
		<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
			<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th rowspan="2" style="width: 30%;"></th>
							<th colspan="3">商品名</th>
						</tr>
						<tr>
							<th>単価</th>
							<th>数量</th>
							<th>購入額</th>
						</tr>
					</thead>
					<tbody>
<?php foreach ($cart_items as $cart_item) {?>
						<tr class="<?php echo h((0 === ($cart_item % 2)) ? 'stripe' : '') ; ?>">
							<td rowspan="2"><img class="w-100"
								src="<?php echo h(DIR_IMG . $cart_item['item_img']); ?>"></td>
							<td colspan="3"><?php echo h($cart_item['item_name']); ?></td>
						</tr>
						<tr class="<?php echo h((0 === ($cart_item % 2)) ? 'stripe' : '') ; ?>">
							<td><?php echo h(number_format($cart_item['item_price'])); ?>円</td>
							<td><?php echo h(number_format($cart_item['cart_amount'])); ?>個</td>
							<td><?php echo h(number_format($cart_item['item_price'] * $cart_item['cart_amount'])) ;?>円</td>
						</tr>
<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td colspan="2">
								<div>
									<span>合計</span> <span><?php echo h(number_format($total_price)); ?>円</span>
								</div>
							</td>
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