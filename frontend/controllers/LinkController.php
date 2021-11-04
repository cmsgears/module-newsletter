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

/**
 * LinkController process the actions specific to Newsletter Link model.
 *
 * @since 1.0.0
 */
class LinkController extends \cmsgears\newsletter\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $memberService;

	protected $linkAnalyticsService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Services
		$this->modelService		= Yii::$app->factory->get( 'newsletterLinkService' );
		$this->memberService	= Yii::$app->factory->get( 'newsletterMemberService' );

		$this->linkAnalyticsService = Yii::$app->factory->get( 'newsletterLinkAnalyticsService' );
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

	// LinkController ------------------------

	/**
	 * Mark model to read and return the pixel image.
	 *
	 * @param int $id
	 * @param string $mgid
	 */
	public function actionAnalytics( $id, $mgid ) {

		// Find Model
		$model	= $this->modelService->getById( $id );
		$member = $this->memberService->getByGid( $mgid );

		// Match Member GID
		if( isset( $model ) && isset( $member ) ) {

			// Get Link Analytics Model
			$analytics = $this->linkAnalyticsService->getByLinkIdMemberId( $model->id, $member->id );

			if( empty( $analytics ) ) {

				$this->linkAnalyticsService->createByParams([
					'newsletterId' => $model->newsletterId,
					'editionId' => $model->editionId,
					'linkId' => $model->id,
					'memberId' => $member->id,
					'visits' => 1
				]);
			}
			else {

				$analytics->visits++;

				$this->linkAnalyticsService->update( $analytics );
			}
		}

		// Redirect to Link
		return $this->redirect( $model->redirect );
	}

}
