<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Mailing List Member | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$newsletter	= isset( $model->newsletter ) ? $model->newsletter->name : null;
$member		= isset( $model->member ) ? $model->member->name . ', ' . $model->member->email : null;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-mailing-list', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'newsletterId', [
								'placeholder' => 'Newsletter', 'icon' => 'cmti cmti-search',
								'value' => $newsletter, 'url' => 'newsletter/newsletter/auto-search'
							])?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'memberId', [
								'placeholder' => 'Member', 'icon' => 'cmti cmti-search',
								'value' => $member, 'url' => 'newsletter/member/auto-search'
							])?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', null, 'cmti cmti-checkbox' ) ?>
						</div>
						<div class="col col2">

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
