<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Link Analytics | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;

$member = isset( $model->memberId ) ? $model->member->name . ', ' . $model->member->email : null;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-mailing-list', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100 layer layer-1">
						<div class="col col2">
							<span class="bold">Newsletter:</span>
							<span> <?= isset($model->newsletterId) ? $model->newsletter->displayName: null ?></span>
						</div>
						<div class="col col2">
							<span class="bold">Edition:</span>
							<span> <?= isset($model->editionId) ? $model->edition->displayName: null ?></span>
						</div>
					</div>
					<div class="filler-height filler-height-medium"></div>
					<div class="row max-cols-100 layer layer-1">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'memberId', [
								'placeholder' => 'Member Email', 'icon' => 'cmti cmti-search',
								'value' => $member, 'url' => 'newsletter/member/auto-search'
							])?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'visits' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
