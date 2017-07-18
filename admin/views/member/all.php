<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletter Members | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Newsletter Members', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'email' => 'Email' ],
	'sortColumns' => [
		'user' => 'User', 'name' => 'Name', 'email' => 'Email', 'active' => 'Active',
		'cdate' => 'Created At', 'udate' => 'Updated At'
	],
	'filters' => [ 'status' => [ 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'email' => [ 'title' => 'Email', 'type' => 'text' ],
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [ 'status' => [ 'block' => 'Block', 'active' => 'Activate' ] ],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x6', 'x6', null, null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'email' => 'Email',
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr(); } ],
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/member",
	//'cardView' => "$moduleTemplates/grid/cards/member",
	//'actionView' => "$moduleTemplates/grid/actions/member"
]) ?>

<?= Popup::widget([
		'title' => 'Update Newsletter Members', 'size' => 'medium',
		'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
		'data' => [ 'model' => 'Newsletter Member', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "newsletter/member/bulk" ]
]) ?>

<?= Popup::widget([
		'title' => 'Delete Newsletter Member', 'size' => 'medium',
		'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
		'data' => [ 'model' => 'Newsletter Member', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "newsletter/member/delete?id=" ]
]) ?>
