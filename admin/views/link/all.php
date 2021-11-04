<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletter Links | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$siteUrl		= $coreProperties->getSiteUrl();

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Newsletter Links', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'title' => 'Title', 'redirect' => 'Redirect URL',
		'newsletter' => 'Newsletter', 'edition' => 'Edition'
	],
	'sortColumns' => [
		'newsletter' => 'Newsletter', 'edition' => 'Edition',
		'title' => 'Title', 'redirect' => 'Redirect URL',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [],
	'reportColumns' => [
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'redirect' => [ 'title' => 'Redirect URL', 'type' => 'text' ],
		'newsletter' => [ 'title' => 'Newsletter', 'type' => 'text' ],
		'edition' => [ 'title' => 'Edition', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x2', 'x2', 'x3', null, 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'title' => 'Title',
		'newsletter' => [ 'title' => 'Newsletter', 'generate' => function( $model ) { return $model->newsletter->name; } ],
		'edition' => [ 'title' => 'Edition', 'generate' => function( $model ) {
			return isset( $model->edition ) ? $model->edition->name : null;
		}],
		'redirect' => 'Redirect URL',
		'wrapBanner' => [ 'title' => 'Wraps Banner', 'generate' => function( $model ) {
			return $model->getWrapBannerStr();
		}],
		'embed' => [ 'title' => 'Analytics Link', 'generate' => function( $model ) {
			return $model->getAnalyticsLink();
		}],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/trigger",
	//'cardView' => "$moduleTemplates/grid/cards/trigger",
	//'actionView' => "$moduleTemplates/grid/actions/trigger"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Newsletter Link', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Newsletter Link', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter Link', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
