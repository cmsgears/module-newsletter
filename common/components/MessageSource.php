<?php
namespace cmsgears\newsletter\common\components;

// Yii Imports
use yii\base\Component;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	// Global -----------------

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

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}
