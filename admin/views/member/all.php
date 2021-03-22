<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletter Members | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [],
	'title' => 'Newsletter Members', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'email' => 'Email', 'mobile' => 'Mobile' ],
	'sortColumns' => [
		'name' => 'Name', 'email' => 'Email',
		'mobile' => 'Mobile', 'user' => 'User',
		'active' => 'Active', 'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'model' => [ 'active' => 'Active', 'disabled' => 'Disabled' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
		'mobile' => [ 'title' => 'Mobile', 'type' => 'text' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'model' => [ 'activate' => 'Activate', 'disable' => 'Disable', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', 'x2', 'x4', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'email' => 'Email',
		'mobile' => 'Mobile',
		'user' => [ 'title' => 'User', 'generate' => function( $model ) {
			return isset( $model->user ) ? $model->user->name : null;
		}],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/member",
	//'cardView' => "$moduleTemplates/grid/cards/member",
	//'actionView' => "$moduleTemplates/grid/actions/member"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Newsletter Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Newsletter Member', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter Member', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
