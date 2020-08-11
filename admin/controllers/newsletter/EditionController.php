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

/**
 * EditionController provide actions specific to Newsletter Editions.
 *
 * @since 1.0.0
 */
class EditionController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $templateService;

	protected $newsletterService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		// Permission
		$this->crudPermission = NewsletterGlobal::PERM_NEWSLETTER_ADMIN;

		// Config
		$this->apixBase = 'newsletter/newsletter/edition';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'newsletterEditionService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->newsletterService = Yii::$app->factory->get( 'newsletterService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];

		// Return Url
		$this->returnUrl = Url::previous( 'newsletter-editions' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/newsletter/edition/all' ], true );

		// All Url
		$allUrl = Url::previous( 'newsletters' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/newsletter/newsletter/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Newsletters', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Newsletter Editions' ] ],
			'create' => [ [ 'label' => 'Newsletter Editions', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Newsletter Editions', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Newsletter Editions', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'data' => [ [ 'label' => 'Newsletters', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'attributes' => [ [ 'label' => 'Newsletters', 'url' => $this->returnUrl ], [ 'label' => 'Attributes' ] ],
			'config' => [ [ 'label' => 'Newsletters', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Newsletters', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
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
					'delete' => [ 'permission' => $this->crudPermission ],
					'pdf' => [ 'permission' => $this->crudPermission ],
					'import' => [ 'permission' => $this->crudPermission ],
					'export' => [ 'permission' => $this->crudPermission ],
					'data' => [ 'permission' => $this->crudPermission ],
					'attributes' => [ 'permission' => $this->crudPermission ],
					'config' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ],
					'pdf' => [ 'get' ],
					'import' => [ 'post' ],
					'export' => [ 'get' ],
					'data' => [ 'get', 'post' ],
					'attributes' => [ 'get', 'post' ],
					'config' => [ 'get', 'post' ],
					'settings' => [ 'get', 'post' ]
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

	public function actions() {

		$actions = parent::actions();

		$actions[ 'data' ] = [ 'class' => 'cmsgears\core\common\actions\data\data\Form' ];
		$actions[ 'attributes' ] = [ 'class' => 'cmsgears\core\common\actions\data\attributes\Form' ];
		$actions[ 'config' ] = [ 'class' => 'cmsgears\core\common\actions\data\config\Form' ];
		$actions[ 'settings' ] = [ 'class' => 'cmsgears\core\common\actions\data\setting\Form' ];

		return $actions;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// EditionController ---------------------

	public function actionAll( $pid, $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'newsletter-editions' );

		$parent = $this->newsletterService->getById( $pid );

		if( isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$dataProvider = $this->modelService->getPage();

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent,
				'baseStatusMap' => $modelClass::$baseStatusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid, $config = [] ) {

		$parent = $this->newsletterService->getById( $pid );

		if( isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$model = new $modelClass();

			$model->newsletterId = $parent->id;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

				if( $this->model->isActive() ) {

					$this->modelService->activate( $model );
				}

				return $this->redirect( 'all?pid=' . $parent->id );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

			return $this->render( 'create', [
				'parent' => $parent,
				'model' => $model,
				'templatesMap' => $templatesMap,
				'statusMap' => $modelClass::$statusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$modelClass = $this->modelService->getModelClass();

			$template = $model->template;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'oldTemplate' => $template
				]);

				if( $this->model->isActive() ) {

					$this->modelService->activate( $model );
				}

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

			// Render view
	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap,
				'statusMap' => $modelClass::$statusMap
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

			$templatesMap = $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

			// Render view
	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap,
				'statusMap' => $modelClass::$statusMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
