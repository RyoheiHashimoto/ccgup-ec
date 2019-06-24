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
<title>商品の登録</title>
<link href="./assets/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link rel="stylesheet" href="./assets/css/style.css">
</head>
<body class="admin">

<?php include DIR_VIEW_ELEMENT . 'output_navber.php'; ?>

	<div class="container-fluid px-md-3">
<?php include DIR_VIEW_ELEMENT . 'output_message.php'; ?>
		<div class="row d-md-none">
			<div class="col-12 alert alert-danger" role="alert">
				このページはスマートフォンには対応していません。 <br>パソコン・タブレットにてご利用ください。
			</div>
		</div>
		<div>
			<section>
				<div class="col-xs-12 col-md-10 offset-md-1">
					<div class="my-4">
						<h2>商品の登録</h2>
						<form method="post" enctype="multipart/form-data">
							<input type="hidden" name="action" value="regist">
							<div class="form-row">
								<div class="form-group col-md-6 row">
									<label for="name" class="col-4 col-form-label-sm text-right">商品名:</label>
									<div class="col-8">
										<input class="form-control" type="text" id="name" name="name" value="">
									</div>
								</div>
								<div class="form-group col-md-6 row">
									<label for="img" class="col-4 col-form-label-sm text-right">商品画像:</label>
									<div class="col-8">
										<input class="form-control" type="file" id="img" name="img">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6 row">
									<label for="price" class="col-4 col-form-label-sm text-right">価格:</label>
									<div class="col-8">
										<input class="form-control" type="number" id="price" name="price">
									</div>
								</div>
								<div class="form-group col-md-6 row">
									<label for="stock" class="col-4 col-form-label-sm text-right">在庫数:</label>
									<div class="col-8">
										<input class="form-control" type="number" id="stock" name="stock" value="">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="form-group col-md-6 row">
									<label for="status" class="col-4 col-form-label-sm text-right">ステータス:</label>
									<div class="col-8">
										<select class="form-control" id="status" name="status">
											<option value="0">非公開</option>
											<option value="1" selected>公開</option>
										</select>
									</div>
								</div>
								<div class="form-group col-md-6 row pl-5">
									<button type="submit" class="btn btn-primary">商品を登録する</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
			<section>
				<div class="col-xs-12 col-md-10 offset-md-1">
					<h2>商品情報の一覧・変更</h2>
				</div>
<?php if ( !empty($response['items'])) {?>
				<div class="col-xs-12 col-md-10 offset-md-1 cart-list">
					<div class="row">
						<table class="table">
							<thead>
								<tr>
									<th rowspan="2" style="width: 30%;">商品画像</th>
									<th>商品名</th>
									<th>価格</th>
									<th>ステータス</th>
								</tr>
								<tr>
									<th colspan="2">在庫数</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
<?php foreach ( $response['items']as $key => $item ) {?>
								<tr
									class="<?php echo h((0 === ($key % 2)) ? 'stripe' : '' ); ?> <?php echo h(('1' !== $item['status']) ? 'disable' : '' ); ?>">
									<td rowspan="2"><img class="w-100"
										src="<?php echo h(DIR_IMG . $item['img']); ?>"></td>
									<td><?php echo h($item['name']); ?></td>
									<td><?php echo h(number_format($item['price'])); ?>円</td>
									<td>
										<form method="post">
											<input type="hidden" name="id"
												value="<?php echo h($item['id']); ?>"> <input
												type="hidden" name="action" value="update_status">
<?php if ($item['status'] === '1') { ?>
											<button type="submit" class="btn btn-success">公開 → 非公開にする</button>
											<input type="hidden" name="status" value="0">
<?php } else { ?>
											<button type="submit" class="btn btn-success">非公開 → 公開にする</button>
											<input type="hidden" name="status" value="1">
<?php } ?>
										</form>
									</td>
								</tr>
								<tr
									class="<?php echo h((0 === ($key % 2)) ? 'stripe' : '' ); ?> <?php echo h(('1' !== $item['status']) ? 'disable' : '' ); ?>">
									<td colspan="2">
										<form method="post" class="form-inline">
											<input type="hidden" name="id"
												value="<?php echo h($item['id']); ?>"> <input
												type="hidden" name="action" value="update_stock">

											<div class="form-group">
												<input class="form-control" style="width: 80px;"
													type="number" class="input_text_width text_align_right"
													name="stock" value="<?php echo h($item['stock']); ?>">
												<span>個</span>
											</div>
											<button type="submit" class="btn btn-primary ml-2">変更する</button>
										</form>
									</td>
									<td>
										<form method="post" onsubmit="return check()">
											<input type="hidden" name="action" value="delete"> <input
												type="hidden" name="id"
												value="<?php echo h($item['id']); ?>">
											<button type="submit" class="btn btn-danger">削除する</button>
										</form>
									</td>
								</tr>
<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
<?php }?>
			</section>
		</div>
	</div>
	<!-- /.container -->
	<script src="./assets/js/jquery/1.12.4/jquery.min.js"></script>
	<script src="./assets/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
        function check() {
            if(window.confirm('削除してよろしいですか？')) {
                return true;
            } else{
                return false;
            }
        }
   </script>
</body>
</html>
