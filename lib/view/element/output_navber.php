<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
	<nav
		class="navbar navbar-expand-sm navbar-light fixed-top navbar-inverse">
		<a href="./index.html"> <img class="logo" src="./img/logo.png"
			alt="CodeSHOP"></a>
		<button class="navbar-toggler border-white" data-toggle="collapse"
			data-target="#mainNav">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="mainNav">
			<ul class="navbar-nav">
				<li class="nav-item"><a class="text-white nav-link" href="./top.php">ホーム</a></li>
				<li class="nav-item"><a class="text-white nav-link" href="./history.php">注文履歴</a></li>
				<li class="nav-item"><a class="text-white nav-link"	href="./cart.php">カート</a></li>
<?php if (empty($_SESSION['user'])) { ?>
				<li class="nav-item"><a class="text-white nav-link"
					href="./login.php">ログイン</a></li>
<?php } else { ?>
				<li class="nav-item"><a class="text-white nav-link"
					href="./logout.php">ログアウト</a></li>
<?php if (!empty($_SESSION['user']['is_admin'])) { ?>
				<li class="nav-item"><a class="text-white nav-link"
					href="./admin.php">管理</a></li>
<?php } ?>
<?php } ?>
			</ul>
		</div>
	</nav>
