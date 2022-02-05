<?php
namespace cmsgears\newsletter\admin\controllers\link;

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
 * AnalyticsController provide actions specific to Link Analytics.
 *
 * @since 1.0.0
 */
class AnalyticsController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $linkService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		// Permission
		$this->crudPermission = NewsletterGlobal::PERM_NEWSLETTER_ADMIN;

		// Config
		$this->apixBase = 'newsletter/link/analytics';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'newsletterLinkAnalyticsService' );
		$this->linkService	= Yii::$app->factory->get( 'newsletterLinkService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'link' ];

		// Return Url
		$this->returnUrl = Url::previous( 'nllink-analytics' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/link/analytics/all' ], true );

		// All Url
		$allUrl = Url::previous( 'nllinks' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/newsletter/link/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Newsletter Links', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Newsletter Link Analytics' ] ],
			'create' => [ [ 'label' => 'Newsletter Link Analytics', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Newsletter Link Analytics', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Newsletter Link Analytics', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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
					'delete' => [ 'get', 'post' ],
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

	// EditionController ---------------------

	public function actionAll( $pid, $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'nllink-analytics' );

		$parent = $this->linkService->getById( $pid );

		if( isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$dataProvider = $this->modelService->getPageByLinkId( $pid );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid, $config = [] ) {

		$parent = $this->linkService->getById( $pid );

		if( isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$model = new $modelClass();

			$model->linkId			= $parent->id;
			$model->newsletterId	= $parent->newsletterId;
			$model->editionId		= $parent->editionId;
			$model->visits			= 0;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

				if( $this->model ) {

					return $this->redirect( 'all?pid=' . $parent->id );
				}
			}

			return $this->render( 'create', [
				'parent' => $parent,
				'model' => $model
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

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
	    	return $this->render( 'update', [
	    		'model' => $model
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
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
