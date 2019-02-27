<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\admin\controllers\newsletter;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

/**
 * FileController provides actions specific to newsletter files.
 *
 * @since 1.0.0
 */
class FileController extends \cmsgears\core\admin\controllers\base\FileController {

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
		$this->title	= 'Newsletter File';
		$this->apixBase	= 'newsletter/newsletter/file';

		// Services
		$this->parentService = Yii::$app->factory->get( 'newsletterService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];

		// Return Url
		$this->returnUrl = Url::previous( 'newsletter-files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/newsletter/newsletter/file/all' ], true );

		// All Url
		$allUrl = Url::previous( 'newsletters' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/newsletter/newsletter/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Newsletters', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Files' ] ],
			'create' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Create' ] ],
			'update' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

	public function actionAll( $pid ) {

		Url::remember( Yii::$app->request->getUrl(), 'newsletter-files' );

		return parent::actionAll( $pid );
	}

}
