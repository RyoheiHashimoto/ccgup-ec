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
				<h1>注文履歴</h1>
			</div>
		</div>

<?php 
include DIR_VIEW_ELEMENT . 'output_message.php'; ?>
<?php if ( !empty($order_histories_list)) { ?>
		<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
			<div class="row">
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>購入日時</th>
							<th>合計金額</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
<?php foreach ($order_histories_list as $key => $value) {?>
						<tr class="<?php echo h((0 === ($key % 2)) ? 'stripe' : '' ); ?>">
							<td>No.<?php echo h($value['order_history_id']);?></td>
							<td><?php echo h($value['order_datetime']);?></td>
							<td><?php echo h($value['total_price']);?>円</td>
							<td>
								<form action="detail.php" method="get">
									<button type=“submit” class="btn btn-info btn-sm">購入明細</button>
									<input type="hidden" name="order_history_id" value="<?php echo h($value['order_history_id']); ?>">
								</form>
							</td>
<?php } ?>
						<tr>
							<td colspan="4"></td>
						</tr>
					</tbody>
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