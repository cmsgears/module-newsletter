<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\admin\controllers\newsletter;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\behaviors\ActivityBehavior;

class TriggerController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $newsletterService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-newsletter/admin/views/trigger' );

		// Permission
		$this->crudPermission = NewsletterGlobal::PERM_NEWSLETTER_ADMIN;

		// Config
		$this->apixBase = 'newsletter/newsletter/trigger';

		// Services
		$this->modelService = Yii::$app->factory->get( 'newsletterTriggerService' );

		$this->newsletterService = Yii::$app->factory->get( 'newsletterService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'trigger' ];

		// Return Url
		$this->returnUrl = Url::previous( 'newsletter-triggers' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/newsletter/trigger/all' ], true );

		// All Url
		$allUrl = Url::previous( 'newsletters' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/newsletter/newsletter/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Newsletters', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Newsletter Triggers' ] ],
			'create' => [ [ 'label' => 'Newsletter Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Newsletter Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Newsletter Triggers', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
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
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TriggerController ---------------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll( $pid = null, $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'newsletter-triggers' );

		$parent = null;

		if( isset( $pid ) ) {

			Url::remember( Yii::$app->request->getUrl() . "?pid=$pid", 'newsletter-triggers' );

			$parent = $this->newsletterService->findById( $pid );

			$dataProvider = $this->modelService->getPageByNewsletterId( $parent->id );
		}
		else {

			$dataProvider = $this->modelService->getPage();
		}

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'parent' => $parent
		]);
	}

	public function actionCreate( $pid = null, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass();

		$parent = null;

		if( isset( $pid ) ) {

			$parent = $this->newsletterService->findById( $pid );

			$model->newsletterId = $parent->id;
		}

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

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'modeMap' => $modelClass::$modeMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->model = $model;

			    	$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
			    }
			    catch( Exception $e ) {

				    throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			// Render view
	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'modeMap' => $modelClass::$modeMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
