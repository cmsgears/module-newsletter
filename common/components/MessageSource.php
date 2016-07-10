<?php
namespace cmsgears\newsletter\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $messageDb = [
		// Generic Messages
		CoreGlobal::MESSAGE_NEWSLETTER_SIGNUP => 'Thanks for joining our newsletter. We will keep you updated with latest news and happenings.',

		// Generic Fields
		CoreGlobal::FIELD_NEWSLETTER => 'Newsletter'
	];

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// MessageSource -------------------------

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
