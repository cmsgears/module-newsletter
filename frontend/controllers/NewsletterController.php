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
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\CoreGlobalWeb;
use cmsgears\newsletter\common\config\NewsletterGlobal;

/**
 * NewsletterController process the actions specific to Newsletter model.
 *
 * @since 1.0.0
 */
class NewsletterController extends \cmsgears\newsletter\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $memberService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->layout = CoreGlobalWeb::LAYOUT_PUBLIC;

		// Services
		$this->modelService = Yii::$app->factory->get( 'newsletterService' );

		// Services
		$this->memberService = Yii::$app->factory->get( 'newsletterMemberService' );
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
					'unsubscribe' => [ 'get', 'post' ]
				]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterController ------------------

	/**
	 * Unsubscribe the member.
	 *
	 * @param int $id
	 * @param string $mgid
	 */
	public function actionUnsubscribe( $id, $mgid ) {

		// Find Model
		$model	= $this->modelService->getById( $id );
		$member = $this->memberService->getByGid( $mgid );

		if( isset( $model ) && isset( $member ) ) {

			if( $member->load( Yii::$app->request->post(), $member->getClassName() ) && $member->validate() ) {

				$member = $this->memberService->disable( $member );

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::MESSAGE_UNSUBSCRIBE_GLOBAL ) );

				// Refresh the Page
				return $this->refresh();
			}

			return $this->render( 'unsubscribe', [
				'model' => $model,
				'member' => $member
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
