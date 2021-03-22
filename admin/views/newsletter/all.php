<?php
// CMG Imports
use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletters | ' . $coreProperties->getSiteTitle();
$apixBase		= $this->context->apixBase;

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [],
	'title' => 'Newsletters', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [
		'name' => 'Name', 'title' => 'Title',
		'desc' => 'Description', 'content' => 'Content'
	],
	'sortColumns' => [
		'name' => 'Name', 'title' => 'Title', 'template' => 'Template',
		'multiple' => 'Multiple', 'global' => 'Global',
		'status' => 'Status', 'pdate' => 'Published At',
		'cdate' => 'Created At', 'udate' => 'Updated At',
	],
	'filters' => [
		'status' => $filterStatusMap,
		'model' => [ 'multiple' => 'Multiple', 'global' => 'Global' ]
	],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'multiple' => [ 'title' => 'Multiple', 'type' => 'flag' ],
		'global' => [ 'title' => 'Global', 'type' => 'flag' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => $statusMap ],
		'cdate' => [ 'title' => 'Created At', 'type' => 'date' ],
		'udate' => [ 'title' => 'Updated At', 'type' => 'date' ],
		'pdate' => [ 'title' => 'Published At', 'type' => 'date' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [
			'reject' => 'Reject', 'approve' => 'Approve', 'activate' => 'Activate',
			'freeze' => 'Freeze', 'block' => 'Block', 'terminate' => 'Terminate'
		],
		'model' => [
			'multiple' => 'Multiple', 'single' => 'Single',
			'global' => 'Global', 'specific' => 'Specific',
			'delete' => 'Delete'
		]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x2', 'x3', 'x2', null, null, null, 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'template' => [ 'title' => 'Template', 'generate' => function( $model ) { return isset( $model->template ) ? $model->template->name : null; } ],
		'multiple' => [ 'title' => 'Multiple', 'generate' => function( $model ) { return $model->getMultipleStr(); } ],
		'global' => [ 'title' => 'Global', 'generate' => function( $model ) { return $model->getGlobalStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'publishedAt' => 'Published At',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/newsletter",
	//'cardView' => "$moduleTemplates/grid/cards/newsletter",
	'actionView' => "$moduleTemplates/grid/actions/newsletter"
])?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Newsletter', 'app' => 'grid', 'controller' => 'crud', 'action' => 'bulk', 'url' => "$apixBase/bulk" ]
])?>

<?= Popup::widget([
	'title' => 'Delete Newsletter', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter', 'app' => 'grid', 'controller' => 'crud', 'action' => 'delete', 'url' => "$apixBase/delete?id=" ]
])?>
