<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete List Member | ' . $coreProperties->getSiteTitle();

// TODO: Add search options to search for Newsletter and Member
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete List Member</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-member' ] );?>

    	<?= $form->field( $model, 'newsletterId' ) ?>
    	<?= $form->field( $model, 'memberId' ) ?>
    	<?= $form->field( $model, 'active' )->checkbox() ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>