<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Newsletter Member | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$userName = isset( $model->user ) ? "{$model->user->name}, {$model->user->email}" : null;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-newsletter', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col3"></div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'userId', [
								'placeholder' => 'Search User', 'icon' => 'cmti cmti-search',
								'app' => 'core', 'controller' => 'user',
								'value' => $userName, 'url' => 'core/user/auto-search'
							])?>
						</div>
						<div class="col col3"></div>
					</div>
					<div class="note margin margin-small-v align align-center">
						<span>Or</span>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'email' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'mobile' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active' ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'bounced' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
