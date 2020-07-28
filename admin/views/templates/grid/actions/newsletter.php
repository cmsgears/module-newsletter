<?php
use yii\helpers\Html;

$template = $model->template;
?>
<span title="Files"><?= Html::a( "", [ "newsletter/file/all?pid=$model->id" ], [ 'class' => 'cmti cmti-file' ] ) ?></span>
<?php if( $model->multiple ) { ?>
	<span title="Editions"><?= Html::a( "", [ "newsletter/edition/all?pid=$model->id" ], [ 'class' => 'cmti cmti-newspaper' ] ) ?></span>
<?php } ?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>

<?php if( isset( $template ) ) { ?>
	<?php if( !empty( $template->dataForm ) ) { ?>
		<span title="Data"><?= Html::a( "", [ "data?id=$model->id" ], [ 'class' => 'cmti cmti-briefcase' ] ) ?></span>
	<?php } ?>
	<?php if( !empty( $template->configForm ) ) { ?>
		<span title="Config"><?= Html::a( "", [ "config?id=$model->id" ], [ 'class' => 'cmti cmti-setting-o' ] ) ?></span>
	<?php } ?>
	<?php if( !empty( $template->settingsForm ) ) { ?>
		<span title="Settings"><?= Html::a( "", [ "settings?id=$model->id" ], [ 'class' => 'cmti cmti-setting' ] ) ?></span>
	<?php } ?>
<?php } ?>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
