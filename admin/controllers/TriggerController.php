<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

class TriggerController extends \cmsgears\core\admin\controllers\base\CrudController {

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

		// Config
		$this->apixBase = 'newsletter/trigger';

		// Services
		$this->modelService = Yii::$app->factory->get( 'newsletterTriggerService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'trigger' ];

		// Return Url
		$this->returnUrl = Url::previous( 'nltriggers' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/trigger/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
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

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TriggerController ---------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'nltriggers' );

		return parent::actionAll( $config );
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'modeMap' => $modelClass::$modeMap
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
