<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<?php if (empty($msg['result_msg']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-success" role="alert">
		<?php echo h($msg['result_msg']); ?>
	</div>
</div>
<?php } ?>

<?php if (empty($msg['err_msg']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-danger" role="alert">
<?php
	if (is_array($msg['err_msg'])) {
		echo h(implode("\n", $msg['err_msg']));
	} else {
		echo h($msg['err_msg']);
	}
	?>
	</div>
</div>
<?php } ?>
