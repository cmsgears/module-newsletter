<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\sendgrid\config\SendGridProperties;

$sendGridProperties = SendGridProperties::getInstance();
?>
<span title="Analytics"><?= Html::a( "", [ "/newsletter/link/analytics/all?pid=$model->id" ], [ 'class' => 'cmti cmti-list' ] ) ?></span>

<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] ) ?></span>
<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
