<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletter Triggers | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';

$addUrl = isset( $parent ) ? "create?pid=$parent->id" : 'create';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => $addUrl, 'data' => [ ],
	'title' => 'Newsletter Triggers', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'email' => 'Email',
		'newsletter' => 'Newsletter', 'edition' => 'Edition'
	],
	'sortColumns' => [
		'newsletter' => 'Newsletter', 'edition' => 'Edition',
		'name' => 'Name', 'email' => 'Email',
		'sent' => 'Sent', 'delivered' => 'Delivered', 'mode' => 'Mode',
		'cdate' => 'Created At', 'udate' => 'Updated At',
		'sdate' => 'Sent At', 'ddate' => 'Delivered At',
	],
	'filters' => [
		'model' => [
			'sent' => 'Sent', 'delivered' => 'Delivered'
		]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'newsletter' => [ 'title' => 'Newsletter', 'type' => 'text' ],
		'edition' => [ 'title' => 'Edition', 'type' => 'text' ],
		'sent' => [ 'title' => 'Sent', 'type' => 'flag' ],
		'delivered' => [ 'title' => 'Delivered', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'sent' => 'Sent', 'delivered' => 'Delivered', 'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', 'x2', 'x2', null, null, null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->member->name; } ],
		'email' => [ 'title' => 'Email', 'generate' => function( $model ) { return $model->member->email; } ],
		'newsletter' => [ 'title' => 'Newsletter', 'generate' => function( $model ) { return $model->newsletter->name; } ],
		'edition' => [ 'title' => 'Edition', 'generate' => function( $model ) {
			return isset( $model->edition ) ? $model->edition->name : null;
		}],
		'sent' => [ 'title' => 'Sent', 'generate' => function( $model ) { return $model->getSentStr(); } ],
		'delivered' => [ 'title' => 'Delivered', 'generate' => function( $model ) { return $model->getDeliveredStr(); } ],
		'mode' => [ 'title' => 'Mode', 'generate' => function( $model ) { return $model->getModeStr(); } ],
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
	'data' => [ 'model' => 'Newsletter Trigger', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Newsletter Trigger', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter Trigger', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
