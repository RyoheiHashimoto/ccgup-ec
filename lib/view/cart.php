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
<title>カート</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
<?php include DIR_VIEW_ELEMENT . 'output_navber.php'; ?>

	<div class="container-fluid px-md-3">
		<div class="row">
			<div class="col-12">
				<h1>カート</h1>
			</div>
		</div>

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
							<th>削除</th>
							<th>価格</th>
							<th>数量</th>
						</tr>
					</thead>
					<tbody>
<?php foreach ($cart_items as $cart_section => $cart_item) {?>
						<tr class="<?php echo h((is_even_number_section($cart_section) === TRUE) ? 'stripe' : '' ); ?>">
							<td rowspan="2">
								<img class="w-100" src="<?php echo h(DIR_IMG . $cart_item['item_img']); ?>">
							</td>
							<td colspan="3">
								<?php echo h($cart_item['item_name']);?>
							</td>
						</tr>
						<tr class="<?php echo h((is_even_number_section($cart_section) === TRUE) ? 'stripe' : '' ); ?>">
							<td>
								<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="post">
									<button type="submit" class="btn btn-danger btn-sm">削除</button>
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="cart_id" value="<?php echo h($cart_item['cart_id']); ?>">
									<input type="hidden" name="action" value="delete_cart">
								</form>
							</td>
							<td><?php echo h(number_format($cart_item['item_price'])); ?>円</td>
							<td>
								<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="post">
									<select class="amount_select" name="cart_amount">
<?php $max_count = SELECTABLE_CART_AMOUNT_MAX; if ((int)$cart_item['cart_amount'] > $max_count){$max_count = (int)$cart_item['cart_amount'];}; ?>
<?php for ($count = SELECTABLE_CART_AMOUNT_MIN; $count <= $max_count; $count++)  { ?>
										<option value="<?php echo h($count); ?>"
											<?php if ((int)$cart_item['cart_amount'] === $count){echo 'selected';}; ?>>
											<?php echo h($count);?>
										</option>
<?php } ?>
									</select>
									<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
									<input type="hidden" name="cart_id" value="<?php echo h($cart_item['cart_id']); ?>">
									<input type="hidden" name="action" value="update_cart">
								</form>
							</td>
						</tr>
<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td colspan="2">
								<div>
									<span>合計</span>
									<span><?php echo h(number_format($total_price)); ?>円</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<div>
									<form action="./finish.php" method="post">
										<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
										<button type="submit" class="btn btn-warning btn-lg btn-block">購入する</button>
									</form>
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
	<script type="text/javascript">
		// selectが変更されたときにonchange実行
		// 指定したフォームをsubmit
		$('.amount_select').on('change', function(){
			$(this).parents('form').submit();
		});
	</script>
</body>
</html>
