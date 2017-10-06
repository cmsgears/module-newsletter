<?php
namespace cmsgears\newsletter\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ListController extends \cmsgears\core\admin\controllers\base\CrudController {

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
		$this->modelService		= Yii::$app->factory->get( 'newsletterListService' );

		// Sidebar
		$this->sidebar 			= [ 'parent' => 'sidebar-newsletter', 'child' => 'list' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'nllists' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/list/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Newsletters', 'url' =>  [ '/newsletter/newsletter/all' ] ] ],
			'all' => [ [ 'label' => 'Mailing Lists' ] ],
			'create' => [ [ 'label' => 'Mailing Lists', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Mailing Lists', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Mailing Lists', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ListController ------------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'nllists' );

		return parent::actionAll();
	}
}
