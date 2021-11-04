<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\entities\User;

use cmsgears\newsletter\common\models\base\NewsletterTables;

use cmsgears\core\common\models\traits\base\MultiSiteTrait;

/**
 * NewsletterMember maintains the list of newsletter subscribers. These can be either
 * application users or subscribers without having application account.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property string $gid
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property boolean $active
 * @property boolean $bounced
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 *
 * @since 1.0.0
 */
class NewsletterMember extends \cmsgears\core\common\models\base\Entity implements IMultiSite {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NewsletterGlobal::TYPE_NEWSLETTER_MEMBER;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultiSiteTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
    public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ 'email', 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ 'email', 'unique' ],
			[ 'mobile', 'unique' ],
			// Email
			[ 'email', 'email' ],
			// Text Limit
			[ [ 'gid', 'mobile' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'email' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'active', 'bounced' ], 'boolean' ],
			[ 'userId', 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'email', 'mobile' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Generate GID
			if( empty( $this->gid ) ) {

				$this->gid = Yii::$app->security->generateRandomString();
			}

			// Default Active
			if( empty( $this->active ) ) {

				$this->active = true;
			}

			// Default Bounced
			if( empty( $this->bounced ) ) {

				$this->bounced = false;
			}

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterMember ----------------------

	/**
	 * Return the corresponding user.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser() {

		return $this->hasOne( User::class, [ 'id' => 'userId' ] );
	}

    /**
	 * Returns string representation of active flag.
	 *
     * @return string
     */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

    /**
	 * Returns string representation of bounced flag.
	 *
     * @return string
     */
    public function getBouncedStr() {

        return Yii::$app->formatter->asBoolean( $this->bounced );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_MEMBER );
    }

	// CMG parent classes --------------------

	// NewsletterMember ----------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the member with user.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with user.
	 */
	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ] = [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

    /**
	 * Find and return the newsletter member using given email.
	 *
     * @param string $email
     * @return NewsletterMember
     */
    public static function findByEmail( $email ) {

        return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
    }

    /**
	 * Check whether newsletter member exist using given email.
	 *
     * @param string $email
     * @return boolean
     */
    public static function isExistByEmail( $email ) {

        $member = self::findByEmail( $email );

        return isset( $member );
    }

    public static function findByGid( $gid ) {

        return self::find()->where( 'gid=:gid', [ ':gid' => $gid ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete the member using given email.
	 *
	 * @param string $email
	 * @return integer number of rows.
     */
    public static function deleteByEmail( $email ) {

        return self::deleteAll( 'email=:email', [ ':email' => $email ] );
    }

}
