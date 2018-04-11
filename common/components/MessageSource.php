<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\components;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\base\MessageSource as BaseMessageSource;

/**
 * MessageSource stores and provide the messages and message templates available in
 * Newsletter Module.
 *
 * @since 1.0.0
 */
class MessageSource extends BaseMessageSource {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $messageDb = [
		// Generic Messages
		NewsletterGlobal::MESSAGE_NEWSLETTER_SIGNUP => 'Thanks for joining our newsletter.',

		// Generic Fields
		NewsletterGlobal::FIELD_NEWSLETTER => 'Newsletter'
	];

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

}
