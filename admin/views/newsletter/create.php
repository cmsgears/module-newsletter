<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Newsletter';

// Breadcrumbs
$this->params[ 'breadcrumbs' ][]	= [ 'label' => 'Newsletters', 'url' => [ '/newsletter/newsletter/all' ] ];
$this->params[ 'breadcrumbs' ][]	= [ 'label' => 'Add' ];

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true, 'fonts' => 'site', 'config' => [ 'controls' => 'mini' ] ] );
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Newsletter</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-newsletter', 'options' => [ 'class' => 'form' ] ] ); ?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>

		<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'global', null, 'cmti cmti-checkbox' ) ?>
		<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', null, 'cmti cmti-checkbox' ) ?>

		<?= $form->field( $model, 'templateId' )->dropDownList( $templatesMap, [ 'class' => 'cmt-select' ] ) ?>

		<div class="box-content clearfix">
			<div class="header">Newsletter Content</div>
			<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
		</div>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?= Html::a( 'Cancel', [ 'all' ], [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
