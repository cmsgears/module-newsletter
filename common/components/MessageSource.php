<?php
namespace cmsgears\newsletter\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

class MessageSource extends Component {

	// Variables ---------------------------------------------------

	private $messageDb = [
		// Messages --------------------------------------------------------

		// Generic Messages
		CoreGlobal::MESSAGE_NEWSLETTER_SIGNUP => 'Thanks for joining our newsletter. We will keep you updated with latest news and happenings.',

		// Fields ----------------------------------------------------------

		// Generic Fields
		CoreGlobal::FIELD_NEWSLETTER => 'Newsletter'
	];

	/**
	 * Initialise the Newsletter Message DB Component.
	 */
    public function init() {

        parent::init();
    }

	public function getMessage( $messageKey, $params = [], $language = null ) {

		return $this->messageDb[ $messageKey ];
	}
}

?>