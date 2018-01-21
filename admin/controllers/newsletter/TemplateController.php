<?php
namespace cmsgears\newsletter\admin\controllers\newsletter;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

class TemplateController extends \cmsgears\core\admin\controllers\base\TemplateController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->type			= NewsletterGlobal::TYPE_NEWSLETTER;

		// Sidebar
		$this->sidebar 		= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter-template' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'templates' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/newsletter/template/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [ [ 'label' => 'Newsletters', 'url' =>  [ '/newsletter/newsletter/all' ] ] ],
			'all' => [ [ 'label' => 'Templates' ] ],
			'create' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Templates', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'templates' );

		return parent::actionAll();
	}
}
