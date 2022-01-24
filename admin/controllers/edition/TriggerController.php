<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\admin\controllers\edition;

// Yii Imports
use Yii;
use yii\helpers\Url;

class TriggerController extends \cmsgears\newsletter\admin\controllers\newsletter\TriggerController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $editionService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		// Services
		$this->editionService = Yii::$app->factory->get( 'newsletterEditionService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];

		// Return Url
		$this->returnUrl = Url::previous( 'newsletter-etriggers' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/edition/trigger/all' ], true );

		// All Url
		$allUrl = Url::previous( 'newsletters' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/newsletter/newsletter/all' ], true );

		$editionUrl = Url::previous( 'newsletter-editions' );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Newsletters', 'url' =>  $allUrl ],
				[ 'label' => 'Newsletter Editions', 'url' =>  $editionUrl ]
			],
			'all' => [ [ 'label' => 'Edition Triggers' ] ],
			'create' => [ [ 'label' => 'Edition Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Edition Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Edition Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TriggerController ---------------------

	public function actionAll( $pid = null, $config = [] ) {

		Url::remember( Yii::$app->request->getUrl() . "?pid=$pid", 'newsletter-etriggers' );

		$parent = $this->editionService->findById( $pid );

		$dataProvider = $this->modelService->getPageByEditionId( $parent->id );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'parent' => $parent
		]);
	}

	public function actionCreate( $pid = null, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass();

		$parent = $this->editionService->findById( $pid );

		$model->newsletterId	= $parent->newsletterId;
		$model->editionId		= $parent->id;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

			if( $this->model ) {

				return $this->redirect( $this->returnUrl );
			}
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'modeMap' => $modelClass::$modeMap,
			'parent' => $parent
    	]);
	}

}
