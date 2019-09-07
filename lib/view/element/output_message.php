<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<?php
foreach ($messages as $message) { ?>
<?php if (empty($message['success']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-success" role="alert">
		<?php echo h($message['success']); ?>
	</div>
</div>
<?php } ?>
<?php if (empty($message['error']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-danger" role="alert">
<?php
	if (is_array($message['error'])) {
		echo h(implode("\n", $message['error']));
	} else {
		echo h($message['error']);
	}
	?>
	</div>
</div>
<?php } ?>
<?php } ?>
