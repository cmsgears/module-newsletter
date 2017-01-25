<?php
namespace cmsgears\newsletter\frontend\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class SignUpForm extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $name;
	public $email;

	public $newsletterId;

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        $rules = [
			[ [ 'email' ], 'required' ],
			[ [ 'name', 'newsletterId' ], 'safe' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'newsletterId' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_NEWSLETTER )
		];
	}
}
