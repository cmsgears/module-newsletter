<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletters | ' . $coreProperties->getSiteTitle();

// Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Newsletters', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'global' => 'Global', 'active' => 'Active',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'ldate' => 'Sent At'
	],
	'filters' => [ 'status' => [ 'global' => 'Global', 'active' => 'Active' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'global' => [ 'title' => 'Global', 'type' => 'flag' ],
		'active' => [ 'title' => 'Active', 'type' => 'flag' ]
	],
	'bulkPopup' => 'popup-grid-bulk', 'bulkActions' => [ 'status' => [ 'global' => 'Global', 'specific' => 'Specific', 'block' => 'Block', 'active' => 'Activate' ] ],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', null, null, 'x8', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'global' => [ 'title' => 'Global', 'generate' => function( $model ) { return $model->getGlobalStr( ); } ],
		'active' => [ 'title' => 'Active', 'generate' => function( $model ) { return $model->getActiveStr( ); } ],
		'description' => 'Description',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => '@themes/admin/views/templates/widget/grid',
	//'dataView' => "$moduleTemplates/grid/data/newsletter",
	//'cardView' => "$moduleTemplates/grid/cards/newsletter",
	//'actionView' => "$moduleTemplates/grid/actions/newsletter"
]) ?>

<?= Popup::widget([
		'title' => 'Update Newsletters', 'size' => 'medium',
		'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'bulk',
		'data' => [ 'model' => 'Newsletter', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "newsletter/newsletter/bulk" ]
]) ?>

<?= Popup::widget([
		'title' => 'Delete Newsletter', 'size' => 'medium',
		'templateDir' => Yii::getAlias( '@themes/admin/views/templates/widget/popup/grid' ), 'template' => 'delete',
		'data' => [ 'model' => 'Newsletter', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "newsletter/newsletter/delete?id=" ]
]) ?>
