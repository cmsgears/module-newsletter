<?php
namespace cmsgears\newsletter\admin\controllers\newsletter;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 		= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter-template' ];

		$this->type			= NewsletterGlobal::TYPE_NEWSLETTER;
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ] = [
								                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
								                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
								                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
								                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ]
							                ];

		return $behaviors;
    }

	// CategoryController --------------------

	public function actionAll() {

		Url::remember( [ 'newsletter/template/all' ], 'templates' );

		return parent::actionAll();
	}
}

?>