<?php
namespace cmsgears\newsletter\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\entities\Newsletter;

class NewsletterController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'newsletterService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->sidebar 			= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];

		$this->returnUrl		= Url::previous( 'newsletters' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/newsletter/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// BlockController -----------------------

	public function actionAll() {

		Url::remember( [ 'newsletter/all' ], 'newsletters' );

		return parent::actionAll();
	}

	public function actionCreate() {

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

		$templatesMap	= $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

    	return $this->render( 'create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap	= $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

			// Render view
	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

			    	$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
			    }
			    catch( Exception $e ) {

				    throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$templatesMap	= $this->templateService->getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

			// Render view
	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
