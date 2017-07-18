<?php
namespace cmsgears\newsletter\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class MemberController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		// Permissions
		$this->crudPermission 	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'newsletterMemberService' );

		// Sidebar
		$this->sidebar 			= [ 'parent' => 'sidebar-newsletter', 'child' => 'member' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'members' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/member/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ 'label' => 'Newsletters', 'url' =>  [ '/newsletter/newsletter/all' ] ],
			'all' => [ [ 'label' => 'Members' ] ],
			'create' => [ [ 'label' => 'Members', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Members', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Members', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MemberController ----------------------

	public function actionAll() {

		Url::remember( [ 'member/all' ], 'members' );

		return parent::actionAll();
	}
}
