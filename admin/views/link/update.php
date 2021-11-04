<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Newsletter Link | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$apixBase		= $this->context->apixBase;

$newsletter	= isset( $model->newsletterId ) ? $model->newsletter->name : null;
$edition	= isset( $model->editionId ) ? $model->edition->name : null;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-link', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="cmt-newsletter-wrap row max-cols-100 layer layer-5">
						<div class="col col2">
							<div class="cmt-newsletter-fill auto-fill auto-fill-basic">
								<div class="auto-fill-source" cmt-app="newsletter" cmt-controller="newsletter" cmt-action="autoSearch" action="newsletter/newsletter/auto-search" cmt-keep cmt-custom>
									<div class="relative">
										<div class="auto-fill-search clearfix">
											<label>Newsletter</label>
											<div class="frm-icon-element icon-right">
												<span class="icon cmti cmti-search"></span>
												<input class="cmt-key-up auto-fill-text search-name" type="text" name="name" value="<?= $newsletter ?>" placeholder="Newsletter" autocomplete="off">
											</div>
										</div>
										<div class="auto-fill-items-wrap">
											<ul class="auto-fill-items vnav"></ul>
										</div>
									</div>
								</div>
								<div class="auto-fill-target">
									<div class="form-group">
										<input type="hidden" class="target" name="NewsletterLink[newsletterId]" value="<?= $model->newsletterId ?>">
										<div class="help-block"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col col2">
							<div class="cmt-edition-fill auto-fill auto-fill-basic">
								<div class="auto-fill-source" cmt-app="newsletter" cmt-controller="edition" cmt-action="autoSearch" action="newsletter/newsletter/edition/auto-search" cmt-keep cmt-custom>
									<div class="relative">
										<div class="auto-fill-search clearfix">
											<label>Edition</label>
											<div class="frm-icon-element icon-right">
												<span class="icon cmti cmti-search"></span>
												<input class="cmt-key-up auto-fill-text search-name" type="text" name="name" value="<?= $edition ?>" placeholder="Edition" autocomplete="off">
												<input class="search-nid" type="hidden" name="nid" />
											</div>
										</div>
										<div class="auto-fill-items-wrap">
											<ul class="auto-fill-items vnav"></ul>
										</div>
									</div>
								</div>
								<div class="auto-fill-target">
									<div class="form-group">
										<input type="hidden" class="target" name="NewsletterLink[editionId]" value="<?= $model->editionId ?>">
										<div class="help-block"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row max-cols-100 layer layer-1">
						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'redirect' ) ?>
						</div>
					</div>
					<div class="row max-cols-100 layer layer-1">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'wrapBanner' ) ?>
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
