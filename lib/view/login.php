<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>ログイン：CodeCamp講師</title>

<!-- Bootstrap core CSS -->
<link href="./assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="./assets/css/login.css" rel="stylesheet">
</head>

<body class="text-center">
	<form method="post" action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>"
		class="form-signin">
<?php require DIR_VIEW_ELEMENT . 'output_message.php'; ?>
		<h1 class="h3 mb-3 font-weight-normal">CodeCampSHOP</h1>
				<label for="login-id" class="sr-only">Login ID</label> <input
			type="text" id="login-id" name="login_id" class="form-control"
			placeholder="Login ID" required autofocus> <label
			for="password" class="sr-only">Password</label> <input
			type="password" id="password" name="password" class="form-control"
			placeholder="Login Password" required>
		<div class="checkbox mb-3">
			</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<p class="mt-3 mb-3 text-muted">&copy; CodeCamp</p>
	</form>
</body>
</html>
