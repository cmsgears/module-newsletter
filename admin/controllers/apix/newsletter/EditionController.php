<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\admin\controllers\apix\newsletter;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * EditionController provides actions specific to newsletter editions.
 *
 * @since 1.0.0
 */
class EditionController extends \cmsgears\core\admin\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = NewsletterGlobal::PERM_NEWSLETTER_ADMIN;

		// Services
		$this->modelService = Yii::$app->factory->get( 'newsletterEditionService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'auto-search' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'auto-search' => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EditionController ---------------------

	public function autoSearchAction() {

		$name	= Yii::$app->request->post( 'name' );
		$type	= Yii::$app->request->post( 'type' );
		$nid	= Yii::$app->request->post( 'nid' );
		$data	= [];
		$config	= [];

		$modelService = $this->controller->modelService;

		$modelTable	= $modelService->getModelTable();

		if( !empty( $nid ) ) {

			$config[ 'conditions' ][ "$modelTable.newsletterId" ] = $nid;

			// For models having type columns
			if( isset( $type ) ) {

				$data = $modelService->searchByNameType( $name, $type,$config );
			}
			else {

				$data = $modelService->searchByName( $name, $config );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
