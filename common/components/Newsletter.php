<?php
namespace cmsgears\newsletter\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Newsletter extends \yii\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

    /**
     * Initialise the CMG Core Component.
     */
    public function init() {

        parent::init();

		// Register application components and objects i.e. CMG and Project
		$this->registerComponents();
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Cms -----------------------------------

	// Properties

	// Components and Objects

	public function registerComponents() {

		// Register services
		$this->registerMapperServices();
		$this->registerEntityServices();

		// Init services
		$this->initMapperServices();
		$this->initEntityServices();
	}

	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService', 'cmsgears\newsletter\common\services\mappers\NewsletterListService' );
	}

	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterService', 'cmsgears\newsletter\common\services\entities\NewsletterService' );
		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}

	public function initMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterListService', 'cmsgears\newsletter\common\services\mappers\NewsletterListService' );
	}

	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterService', 'cmsgears\newsletter\common\services\entities\NewsletterService' );
		$factory->set( 'newsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}
}
