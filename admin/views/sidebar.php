<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->core->getUser();
?>
<?php if( $core->hasModule( 'newsletter' ) && $user->isPermitted( NewsletterGlobal::PERM_NEWSLETTER_ADMIN ) ) { ?>
	<div id="sidebar-newsletter" class="collapsible-tab has-children <?= $parent == 'sidebar-newsletter' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-newspaper"></span></div>
			<div class="tab-title">Newsletters</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-newsletter' ? 'expanded visible' : null ?>">
			<ul>
				<li class="newsletter <?= $child == 'newsletter' ? 'active' : null ?>"><?= Html::a( 'Newsletters', [ '/newsletter/newsletter/all' ] ) ?></li>
				<li class="newsletter-template <?= $child == 'newsletter-template' ? 'active' : null ?>"><?= Html::a( 'Templates', [ '/newsletter/newsletter/template/all' ] ) ?></li>
				<li class="member <?= $child == 'member' ? 'active' : null ?>"><?= Html::a( 'Members', [ '/newsletter/member/all' ] ) ?></li>
				<li class="member <?= $child == 'list' ? 'active' : null ?>"><?= Html::a( 'Mailing Lists', [ '/newsletter/list/all' ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
