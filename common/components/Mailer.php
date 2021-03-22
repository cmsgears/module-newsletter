<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\components;

/**
 * Mailer triggers the mails provided by Newsletter Module.
 *
 * @since 1.0.0
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-newsletter/common/mails/views';

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

}
