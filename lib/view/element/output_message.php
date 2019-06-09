<?php
/**
 * @license CC BY-NC-SA 4.0
 * @license https://creativecommons.org/licenses/by-nc-sa/4.0/deed.ja
 * @copyright CodeCamp https://codecamp.jp
 */
?>
<?php if (empty($response['result_msg']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-success" role="alert">
		<?php echo h($response['result_msg']); ?>
	</div>
</div>
<?php } ?>

<?php if (empty($response['error_msg']) !== TRUE) { ?>
<div class="row">
	<div class="col-12 alert alert-danger" role="alert">
<?php
	if (is_array($response['error_msg'])) {
		echo h(implode('<br>', $response['error_msg']));
	} else {
		echo h($response['error_msg']);
	}
	?>
	</div>
</div>
<?php } ?>
