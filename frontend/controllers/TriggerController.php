<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TriggerController process the actions specific to Newsletter Trigger model.
 *
 * @since 1.0.0
 */
class TriggerController extends \cmsgears\newsletter\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Services
		$this->modelService = Yii::$app->factory->get( 'newsletterTriggerService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					// Protected actions
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'analytics' => [ 'get' ]
				]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TriggerController ---------------------

	/**
	 * Mark model to read and return the pixel image.
	 *
	 * @param int $id
	 * @param string $mgid
	 */
	public function actionAnalytics( $id, $mgid ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Match Member GID
		if( $model->member->gid == $mgid ) {

			// Read Model
			$model = $this->modelService->markRead( $model );

			$model->refresh();

			// Increment Read Count
			$this->modelService->incrementReadCount( $model );
		}

		// Return the pixel image

		$response = Yii::$app->response;

		$response->format = Response::FORMAT_RAW;

		$response->headers->add( 'content-type', 'image/jpg' );

		// Pixel Image
		$pixelImage = Yii::getAlias( '@frontend' ) . '/web/images/newsletter/pixelmap.jpg';

		$response->data = file_get_contents( $pixelImage );

		$response->data = trim( $response->data );

		return $response;
	}

}
