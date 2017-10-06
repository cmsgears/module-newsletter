<?php
namespace cmsgears\newsletter\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\components\MessageSource;

use cmsgears\newsletter\frontend\models\forms\SignUpForm;

use cmsgears\core\common\utilities\AjaxUtil;

class SiteController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $newsletterMemberService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->newsletterMemberService	= Yii::$app->factory->get( 'newsletterMemberService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    public function behaviors() {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'signUp' => [ 'post' ]
                ]
            ]
        ];
    }

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteController ------------------------

    public function actionSignUp() {

		// Create Form Model
		$model			= new SignUpForm();
		$MessageSource	= new MessageSource();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Newsletter' ) && $model->validate() ) {

			if( $this->newsletterMemberService->signUp( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::MESSAGE_NEWSLETTER_SIGNUP )  );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }
}
