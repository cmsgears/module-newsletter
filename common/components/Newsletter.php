<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\components;

// Yii Imports
use Yii;
use yii\base\Component;

/**
 * Newsletter component register the services provided by Newsletter Module.
 *
 * @since 1.0.0
 */
class Newsletter extends Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	/**
	 * Initialize the services.
	 */
	public function init() {

		parent::init();

		// Register components and objects
		$this->registerComponents();
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Newsletter ----------------------------

	// Properties ----------------

	// Components and Objects ----

	/**
	 * Register the services.
	 */
	public function registerComponents() {

		// Register services
		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();

		// Init services
		$this->initResourceServices();
		$this->initMapperServices();
		$this->initEntityServices();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\resources\INewsletterMetaService', 'cmsgears\newsletter\common\services\resources\NewsletterMetaService' );
	}

	/**
	 * Registers mapper services.
	 */
	public function registerMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService', 'cmsgears\newsletter\common\services\mappers\NewsletterListService' );
	}

	/**
	 * Registers entity services.
	 */
	public function registerEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterService', 'cmsgears\newsletter\common\services\entities\NewsletterService' );
		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}

	/**
	 * Initialize resource services.
	 */
	public function initResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterMetaService', 'cmsgears\newsletter\common\services\resources\NewsletterMetaService' );
	}

	/**
	 * Initialize mapper services.
	 */
	public function initMapperServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterListService', 'cmsgears\newsletter\common\services\mappers\NewsletterListService' );
	}

	/**
	 * Initialize entity services.
	 */
	public function initEntityServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterService', 'cmsgears\newsletter\common\services\entities\NewsletterService' );
		$factory->set( 'newsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}

}
