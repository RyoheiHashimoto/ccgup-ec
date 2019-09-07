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
<title>商品一覧</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<?php include DIR_VIEW_ELEMENT . 'output_navber.php'; ?>
	<div class="container-fluid px-md-5">
		<div class="top-left-right">
			<div class="top-left">
				<h1>商品一覧</h1>
			</div>
			<div class="top-right">
				<form method="get" action="top.php">
					<select class="order_items" name="order">
						<option value="" <?php if ($order === '') {echo 'selected';} ?>>最新商品</option>
						<option value="ASC" <?php if ($order === 'ASC') {echo 'selected';} ?>>価格の安い順</option>
						<option value="DESC" <?php if ($order === 'DESC') {echo 'selected';} ?>>価格の高い順</option>
					</select>
				</form>
			</div>
		</div>
<?php include DIR_VIEW_ELEMENT . 'output_message.php'; ?>
		<div class="row">
<?php foreach ($items as $item)  { ?>
			<div class="card col-12 col-md-4 p-0 m-0 shadow-sm">
				<img class="item-img w-100 img-responsive" src="<?php echo h(DIR_IMG . $item['item_img']); ?>">
				<div class="card-body">
					<div class="row item-info">
						<div class="col-12 item-price">
							<?php echo h($item['item_name'] . '：' . number_format($item['item_price'])); ?>円
						</div>
						<div class="col-12 mt-1">
<?php if ($item['item_stock'] > 0) { ?>
							<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="post">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="item_id" value="<?php echo h($item['item_id']); ?>">
								<button type="submit" class="btn btn-primary cart-btn">カートに入れる</button>
							</form>
<?php } else { ?>
							<p class="sold-out">売り切れ</p>
<?php } ?>
						</div>
					</div>
				</div>
			</div>
<?php } ?>
		</div>
	</div>
	<!-- /.container -->
	<script src="./assets/js/jquery/1.12.4/jquery.min.js"></script>
	<script src="./assets/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$('.order_items').on('change', function(){
			$(this).parents('form').submit();
		});
	</script>
</body>
</html>
