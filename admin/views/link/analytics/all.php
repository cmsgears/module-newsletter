<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletter Link Analytics | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;
$siteUrl		= $coreProperties->getSiteUrl();

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => "create?pid={$parent->id}", 'data' => [ ],
	'title' => 'Newsletter Links', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'email' => 'Email'
	],
	'sortColumns' => [
		'name' => 'Name', 'email' => 'Email', 'visits' => 'Visits',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x5', 'x5', 'x2', 'x2' ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->member->name; } ],
		'email' => [ 'title' => 'Email', 'generate' => function( $model ) { return $model->member->email; } ],
		'visits' => 'Visits',
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
	'data' => [ 'model' => 'Newsletter Link Analytics', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Newsletter Link Analytics', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter Link Analytics', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
