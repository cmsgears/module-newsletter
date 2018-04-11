<?php
// CMG Imports
use cmsgears\core\common\models\interfaces\base\IApproval;

use cmsgears\widgets\popup\Popup;

use cmsgears\widgets\grid\DataGrid;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Newsletters | ' . $coreProperties->getSiteTitle();

// View Templates
$moduleTemplates	= '@cmsgears/module-newsletter/admin/views/templates';
$themeTemplates		= '@themes/admin/views/templates';
?>
<?= DataGrid::widget([
	'dataProvider' => $dataProvider, 'add' => true, 'addUrl' => 'create', 'data' => [ ],
	'title' => 'Newsletters', 'options' => [ 'class' => 'grid-data grid-data-admin' ],
	'searchColumns' => [ 'name' => 'Name', 'title' => 'Title', 'desc' => 'Description', 'content' => 'Content' ],
	'sortColumns' => [
		'name' => 'Name', 'slug' => 'Slug', 'title' => 'Title', 'global' => 'Global', 'status' => 'Status',
		'cdate' => 'Created At', 'udate' => 'Updated At', 'ldate' => 'Sent At'
	],
	'filters' => [ 'status' => [ 'new' => 'New', 'active' => 'Active', 'blocked' => 'Blocked' ], 'model' => [ 'global' => 'Global' ] ],
	'reportColumns' => [
		'name' => [ 'title' => 'Name', 'type' => 'text' ],
		'title' => [ 'title' => 'Title', 'type' => 'text' ],
		'desc' => [ 'title' => 'Description', 'type' => 'text' ],
		'content' => [ 'title' => 'Content', 'type' => 'text' ],
		'global' => [ 'title' => 'Global', 'type' => 'flag' ],
		'status' => [ 'title' => 'Status', 'type' => 'select', 'options' => [ IApproval::STATUS_NEW => 'New', IApproval::STATUS_ACTIVE => 'Active', IApproval::STATUS_BLOCKED => 'Blocked' ] ],
		'cdate' => [ 'title' => 'Created At', 'type' => 'date' ],
		'udate' => [ 'title' => 'Updated At', 'type' => 'date' ],
		'ldate' => [ 'title' => 'Sent At', 'type' => 'date' ]
	],
	'bulkPopup' => 'popup-grid-bulk',
	'bulkActions' => [
		'status' => [ 'active' => 'Activate', 'block' => 'Block' ],
		'model' => [ 'global' => 'Global', 'specific' => 'Specific', 'delete' => 'Delete' ]
	],
	'header' => false, 'footer' => true,
	'grid' => true, 'columns' => [ 'root' => 'colf colf15', 'factor' => [ null, 'x3', 'x3', null, null, 'x2', 'x3', null ] ],
	'gridColumns' => [
		'bulk' => 'Action',
		'name' => 'Name',
		'title' => 'Title',
		'global' => [ 'title' => 'Global', 'generate' => function( $model ) { return $model->getGlobalStr(); } ],
		'status' => [ 'title' => 'Status', 'generate' => function( $model ) { return $model->getStatusStr(); } ],
		'template' => [ 'title' => 'Template', 'generate' => function( $model ) { return isset( $model->template ) ? $model->template->name : null; } ],
		'lastSentAt' => 'Sent At',
		'actions' => 'Actions'
	],
	'gridCards' => [ 'root' => 'col col12', 'factor' => 'x3' ],
	'templateDir' => "$themeTemplates/widget/grid",
	//'dataView' => "$moduleTemplates/grid/data/newsletter",
	//'cardView' => "$moduleTemplates/grid/cards/newsletter",
	//'actionView' => "$moduleTemplates/grid/actions/newsletter"
]) ?>

<?= Popup::widget([
	'title' => 'Apply Bulk Action', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'bulk',
	'data' => [ 'model' => 'Newsletter', 'app' => 'main', 'controller' => 'crud', 'action' => 'bulk', 'url' => "newsletter/newsletter/bulk" ]
]) ?>

<?= Popup::widget([
	'title' => 'Delete Newsletter', 'size' => 'medium',
	'templateDir' => Yii::getAlias( "$themeTemplates/widget/popup/grid" ), 'template' => 'delete',
	'data' => [ 'model' => 'Newsletter', 'app' => 'main', 'controller' => 'crud', 'action' => 'delete', 'url' => "newsletter/newsletter/delete?id=" ]
]) ?>
