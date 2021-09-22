<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Mailing List | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [],
	'title' => 'Mailing List', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'email' => 'Email', 'newsletter' => 'Newsletter' ],
	'sortColumns' => [
		'name' => 'Name', 'email' => 'Email', 'newsletter' => 'Newsletter',
		'active' => 'Active', 'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [
		'model' => [
			'active' => 'Active', 'disabled' => 'Disabled'
		]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'newsletter' => [ 'title' => 'Newsletter', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'activate' => 'Activate', 'disable' => 'Disable', 'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x4', 'x4', 'x4', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'newsletter' => [ 'title' => 'Newsletter', 'generate' => function( $model ) { return $model->newsletter->name; } ],
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->member->name; } ],
		'email' => [ 'title' => 'Email', 'generate' => function( $model ) { return $model->member->email; } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/list",
	//'cardView' => "$moduleTemplates/grid/cards/list",
	//'actionView' => "$moduleTemplates/grid/actions/list"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Mailing List Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Mailing List Member', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Mailing List Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
