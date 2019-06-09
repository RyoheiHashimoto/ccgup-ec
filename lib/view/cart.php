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
<?php require DIR_VIEW_ELEMENT . 'output_navber.php'; ?>

	<div class="container-fluid px-md-3">
		<div class="row">
			<div class="col-12">
				<h1>カート</h1>
			</div>
		</div>

<?php require DIR_VIEW_ELEMENT . 'output_message.php'; ?>

<?php if ( !empty($response['cart_items'])) { ?>
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
<?php foreach ( $response['cart_items'] as $key => $value ) {?>
						<tr class="<?php echo (0 === ($key % 2)) ? 'stripe' : '' ; ?>">
							<td rowspan="2"><img class="w-100"
								src="<?php echo DIR_IMG . $value['img']; ?>"></td>
							<td colspan="3"><?php echo $value['name']?></td>
						</tr>
						<tr class="<?php echo (0 === ($key % 2)) ? 'stripe' : '' ; ?>">
							<td>
								<form action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="post">
									<button type="submit" class="btn btn-danger btn-sm">削除</button>
									<input type="hidden" name="id"
										value="<?php echo $value['id']; ?>"> <input
										type="hidden" name="action" value="delete">
								</form>
							</td>
							<td><?php echo number_format($value['price'])?>円</td>
							<td>
								<form id="form_select_amount<?php echo $value['id']; ?>"
									action="<?php echo $_SERVER['SCRIPT_NAME']?>" method="post">
									<select name="amount"
										onchange="submit_change_amount(<?php echo $value['id']; ?>)">
<?php $max_count = 10; if ((int)$value['amount'] > $max_count){$max_count = (int)$value['amount'];}; ?>
<?php for ($count = 1; $count <= $max_count; $count++)  { ?>
										<option value="<?php echo $count; ?>"
											<?php if ((int)$value['amount'] === $count){echo 'selected';}; ?>><?php echo $count;?></option>
<?php } ?>
                        </select> <input type="hidden" name="id"
										value="<?php echo $value['id']; ?>"> <input
										type="hidden" name="action" value="update">
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
								<div">
									<span>合計</span> <span><?php echo number_format($response['total_price']); ?>円</span>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="4">
								<div>
									<form action="./finish.php" method="post">
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
		function submit_change_amount(id) {
			document.getElementById('form_select_amount' + id).submit();
		}
	</script>

</body>
</html>
