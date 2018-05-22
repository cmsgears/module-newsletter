<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\frontend\models\forms;

// Yii Imports
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

/**
 * SignUpForm to become newsletter member.
 *
 * @since 1.0.0
 */
class SignUpForm extends Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $name;
	public $email;

	public $newsletterId;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function rules() {

        $rules = [
			[ 'email', 'required' ],
			[ 'newsletterId', 'safe' ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'email', 'email' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	// yii\base\Model ---------

	public function attributeLabels() {

		return [
			'newsletterId' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_NEWSLETTER ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// SignUpForm ----------------------------

}
