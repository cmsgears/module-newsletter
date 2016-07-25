<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->core;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'newsletter' ) && $user->isPermitted( 'core' ) ) { ?>
	<div id="sidebar-newsletter" class="collapsible-tab has-children <?php if( strcmp( $parent, 'sidebar-newsletter' ) == 0 ) echo 'active';?>">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf5 wrap-icon"><span class="cmti cmti-page"></span></div>
			<div class="colf colf5x4">Newsletters</div>
		</div>
		<div class="collapsible-tab-content clear <?php if( strcmp( $parent, 'sidebar-newsletter' ) == 0 ) echo 'expanded visible';?>">
			<ul>
				<li class='newsletter <?php if( strcmp( $child, 'newsletter' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Newsletters", ['/newsletter/newsletter/all'] ) ?></li>
				<li class='newsletter-template <?php if( strcmp( $child, 'newsletter-template' ) == 0 ) echo 'active'; ?>'><?= Html::a( 'Templates', [ '/newsletter/newsletter/template/all' ] ) ?></li>
				<li class='member <?php if( strcmp( $child, 'member' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Members", ['/newsletter/member/all'] ) ?></li>
				<li class='list <?php if( strcmp( $child, 'list' ) == 0 ) echo 'active'; ?>'><?= Html::a( "Mailing Lists", ['/newsletter/list/all'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>