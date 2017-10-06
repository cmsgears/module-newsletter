<?php
namespace cmsgears\newsletter\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The mail component for CMSGears newsletter module. It must be initialised for app using the name cmgNlMailer.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Global -----------------

	//const MAIL_CONTACT			= "contact";

	// Public -----------------

    public $htmlLayout 		= '@cmsgears/module-newsletter/common/mails/layouts/html';
    public $textLayout 		= '@cmsgears/module-newsletter/common/mails/layouts/text';
    public $viewPath 		= '@cmsgears/module-newsletter/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	/*
    public function sendContactMail( $contactForm ) {

		$mailProperties	= $this->mailProperties;
		$adminEmail		= $mailProperties->getSenderEmail();
		$adminName		= $mailProperties->getSenderName();

		$fromEmail 		= $mailProperties->getContactEmail();
		$fromName 		= $mailProperties->getContactName();

		// User Mail
        $this->getMailer()->compose( self::MAIL_CONTACT, [ 'coreProperties' => $this->coreProperties, FormsGlobal::FORM_CONTACT => $contactForm ] )
            ->setTo( $contactForm->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( $contactForm->subject )
            //->setTextBody( $contact->contact_message )
            ->send();
    }
	*/
}
