<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\frontend\models\forms\Register;
use cmsgears\core\frontend\models\forms\Newsletter;

use cmsgears\core\common\services\entities\SiteMemberService;
use cmsgears\core\frontend\services\entities\UserService;
use cmsgears\core\frontend\services\NewsletterMemberService;

use cmsgears\core\common\utilities\AjaxUtil;

class SiteController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

	public function _construct( $id, $module, $config = [] )  {

		parent::_construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'register' => [ 'post' ],
                    'login' => [ 'post' ],
                    'forgotPassword' => [ 'post' ],
                    'newsletter' => [ 'post' ]
                ]
            ]
        ];
    }

	// SiteController

    public function actionNewsletter() {

		// Create Form Model
		$model = new Newsletter();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Newsletter' ) && $model->validate() ) {

			if( NewsletterMemberService::signUp( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_NEWSLETTER_SIGNUP ) );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }
}

?>