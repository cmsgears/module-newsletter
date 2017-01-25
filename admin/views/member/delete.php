<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Newsletter Member | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Newsletter Member</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-member' ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
    	<?= $form->field( $model, 'email' )->textInput( [ 'readonly' => 'true' ] ) ?>
    	<?= $form->field( $model, 'active' )->checkbox( [ 'disabled' => true ] ) ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', [ 'all' ], [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>