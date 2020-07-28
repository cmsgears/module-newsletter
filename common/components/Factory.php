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

/**
 * The Newsletter Factory component initialise the services available in Newsletter Module.
 *
 * @since 1.0.0
 */
class Factory extends \cmsgears\core\common\base\Component {

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Register services
		$this->registerServices();

		// Register service alias
		$this->registerServiceAlias();
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Factory -------------------------------

	public function registerServices() {

		$this->registerResourceServices();
		$this->registerMapperServices();
		$this->registerEntityServices();
	}

	public function registerServiceAlias() {

		$this->registerResourceAliases();
		$this->registerMapperAliases();
		$this->registerEntityAliases();
	}

	/**
	 * Registers resource services.
	 */
	public function registerResourceServices() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\resources\INewsletterMetaService', 'cmsgears\newsletter\common\services\resources\NewsletterMetaService' );

		$factory->set( 'cmsgears\newsletter\common\services\interfaces\resources\INewsletterTriggerService', 'cmsgears\newsletter\common\services\resources\NewsletterTriggerService' );
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
		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterEditionService', 'cmsgears\newsletter\common\services\entities\NewsletterEditionService' );
		$factory->set( 'cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}

	/**
	 * Registers resource aliases.
	 */
	public function registerResourceAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterMetaService', 'cmsgears\newsletter\common\services\resources\NewsletterMetaService' );

		$factory->set( 'newsletterTriggerService', 'cmsgears\newsletter\common\services\resources\NewsletterTriggerService' );
	}

	/**
	 * Registers mapper aliases.
	 */
	public function registerMapperAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterListService', 'cmsgears\newsletter\common\services\mappers\NewsletterListService' );
	}

	/**
	 * Registers entity aliases.
	 */
	public function registerEntityAliases() {

		$factory = Yii::$app->factory->getContainer();

		$factory->set( 'newsletterService', 'cmsgears\newsletter\common\services\entities\NewsletterService' );
		$factory->set( 'newsletterEditionService', 'cmsgears\newsletter\common\services\entities\NewsletterEditionService' );
		$factory->set( 'newsletterMemberService', 'cmsgears\newsletter\common\services\entities\NewsletterMemberService' );
	}

}
