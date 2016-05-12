<?php
namespace cmsgears\newsletter\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\entities\Newsletter;

use cmsgears\core\admin\services\entities\TemplateService;
use cmsgears\newsletter\common\services\entities\NewsletterService;
use cmsgears\newsletter\common\services\entities\NewsletterMemberService;

class NewsletterController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'members' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'all'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ],
	                'members' => [ 'get' ]
                ]
            ]
        ];
    }

	// NewsletterController --------------

	public function actionIndex() {

		$this->redirect( [ 'all' ] );
	}

	public function actionAll() {

		$dataProvider = NewsletterService::getPagination();

	    return $this->render('all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model	= new Newsletter();

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Newsletter' )  && $model->validate() ) {

			if( NewsletterService::create( $model ) ) {

				$this->redirect( [ 'all' ] );
			}
		}

		$templatesMap	= TemplateService::getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

    	return $this->render('create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= NewsletterService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Newsletter' )  && $model->validate() ) {

				if( NewsletterService::update( $model ) ) {

					$this->redirect( [ 'all' ] );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= NewsletterService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Newsletter' ) ) {

				if( NewsletterService::delete( $model ) ) {

					$this->redirect( [ 'all' ] );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( NewsletterGlobal::TYPE_NEWSLETTER, [ 'default' => true ] );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionMembers() {

		$this->sidebar 	= [ 'parent' => 'sidebar-newsletter', 'child' => 'member' ];
		$dataProvider 	= NewsletterMemberService::getPagination();

	    return $this->render( 'members', [
	         'dataProvider' => $dataProvider
	    ]);
	}
}

?>