<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Mailing List | ' . $coreProperties->getSiteTitle();

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Mailing List', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'email' => 'Email', 'newsletter' => 'Newsletter' ],
	'sortColumns' => [
		'user' => 'User', 'member' => 'Name', 'email' => 'Email', 'newsletter' => 'Newsletter', 'active' => 'Active',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'model' => [ 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'newsletter' => [ 'title' => 'Newsletter', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'active' => 'Activate', 'inactive' => 'Disable', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x4', 'x4', 'x4', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => [ 'title' => 'Name', 'generate' => function( $model ) { return $model->member->name; } ],
		'email' => [ 'title' => 'Email', 'generate' => function( $model ) { return $model->member->email; } ],
		'newsletter' => [ 'title' => 'Newsletter', 'generate' => function( $model ) { return $model->newsletter->name; } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/list",
	//'cardView' => "$moduleTemplates/grid/cards/list",
	//'actionView' => "$moduleTemplates/grid/actions/list"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Mailing List Member', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "newsletter/list/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Mailing List Member', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Mailing List Member', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "newsletter/list/delete?id=" ]
]) ?>
