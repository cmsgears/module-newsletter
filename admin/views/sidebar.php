<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

$core	= Yii::$app->core;
$user	= Yii::$app->core->getUser();

$siteRootUrl = Yii::$app->core->getSiteRootUrl();
?>
<?php if( $core->hasModule( 'newsletter' ) && $user->isPermitted( NewsletterGlobal::PERM_NEWSLETTER_ADMIN ) ) { ?>
	<div id="sidebar-newsletter" class="collapsible-tab has-children <?= $parent == 'sidebar-newsletter' ? 'active' : null ?>">
		<div class="row tab-header">
			<div class="tab-icon"><span class="cmti cmti-newspaper"></span></div>
			<div class="tab-title">Newsletters</div>
		</div>
		<div class="tab-content clear <?= $parent == 'sidebar-newsletter' ? 'expanded visible' : null ?>">
			<ul>
				<li class="newsletter <?= $child == 'newsletter' ? 'active' : null ?>"><?= Html::a( 'Newsletters', [ "$siteRootUrl/newsletter/newsletter/all" ] ) ?></li>
				<li class="member <?= $child == 'member' ? 'active' : null ?>"><?= Html::a( 'Members', [ "$siteRootUrl/newsletter/member/all" ] ) ?></li>
				<li class="list <?= $child == 'list' ? 'active' : null ?>"><?= Html::a( 'Mailing Lists', [ "$siteRootUrl/newsletter/list/all" ] ) ?></li>
				<li class="link <?= $child == 'link' ? 'active' : null ?>"><?= Html::a( 'Newsletter Links', [ "$siteRootUrl/newsletter/link/all" ] ) ?></li>
				<li class="trigger <?= $child == 'trigger' ? 'active' : null ?>"><?= Html::a( 'Newsletter Triggers', [ "$siteRootUrl/newsletter/trigger/all" ] ) ?></li>
				<li class="newsletter-template <?= $child == 'newsletter-template' ? 'active' : null ?>"><?= Html::a( 'Newsletter Templates', [ "$siteRootUrl/newsletter/newsletter/template/all" ] ) ?></li>
				<li class="edition-template <?= $child == 'edition-template' ? 'active' : null ?>"><?= Html::a( 'Edition Templates', [ "$siteRootUrl/newsletter/edition/template/all" ] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>
