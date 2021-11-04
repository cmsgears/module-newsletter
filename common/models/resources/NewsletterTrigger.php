<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\entities\Newsletter;
use cmsgears\newsletter\common\models\entities\NewsletterEdition;
use cmsgears\newsletter\common\models\entities\NewsletterMember;

/**
 * NewsletterTrigger maintains newsletter triggers specific to newsletter.
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $editionId
 * @property integer $memberId
 * @property boolean $sent
 * @property boolean $delivered
 * @property integer $mode
 * @property boolean $read
 * @property boolean $emailId
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $sentAt
 * @property datetime $deliveredAt
 * @property datetime $readAt
 *
 * @since 1.0.0
 */
class NewsletterTrigger extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const MODE_ONLINE = 0;

	const MODE_OFFLINE = 10;

	// Public -----------------

	public static $modeMap = [
		self::MODE_ONLINE => 'Online',
		self::MODE_OFFLINE => 'Offline'
	];

	// Used for external docs
	public static $revModeMap = [
		'Online' => self::MODE_ONLINE,
		'Offline' => self::MODE_OFFLINE
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'online' => self::MODE_ONLINE,
		'offline' => self::MODE_OFFLINE
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NewsletterGlobal::TYPE_NEWSLETTER_TRIGGER;

	// Private ----------------

	// Traits ------------------------------------------------------

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
            [ [ 'newsletterId', 'memberId' ], 'required' ],
            [ 'id', 'safe' ],
			// Other
            [ [ 'sent', 'delivered', 'read' ], 'boolean' ],
            [ [ 'mode' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'newsletterId', 'editionId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'sentAt', 'deliveredAt', 'readAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'newsletterId' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_NEWSLETTER ),
			'editionId' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_NEWSLETTER_EDITION ),
            'memberId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MEMBER ),
            'sent' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SENT ),
			'delivered' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DELIVERED ),
			'read' => 'Read',
			'mode' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MODE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterTrigger ---------------------

	/**
	 * Returns corresponding newsletter.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\Newsletter
	 */
	public function getNewsletter() {

		return $this->hasOne( Newsletter::class, [ 'id' => 'newsletterId' ] );
	}

	/**
	 * Returns corresponding newsletter edition.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\NewsletterMember
	 */
	public function getEdition() {

		return $this->hasOne( NewsletterEdition::class, [ 'id' => 'editionId' ] );
	}

	/**
	 * Returns corresponding newsletter member.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\NewsletterMember
	 */
	public function getMember() {

		return $this->hasOne( NewsletterMember::class, [ 'id' => 'memberId' ] );
	}

    /**
     * Returns string representation of sent flag.
	 *
	 * @return string
     */
    public function getSentStr() {

        return Yii::$app->formatter->asBoolean( $this->sent );
    }

    /**
     * Returns string representation of delivered flag.
	 *
	 * @return string
     */
    public function getDeliveredStr() {

        return Yii::$app->formatter->asBoolean( $this->delivered );
    }

    /**
     * Returns string representation of sent flag.
	 *
	 * @return string
     */
    public function getReadStr() {

        return Yii::$app->formatter->asBoolean( $this->read );
    }

    public function isOnline() {

        return $this->mode == self::MODE_ONLINE;
    }

    public function isOffline() {

        return $this->mode == self::MODE_OFFLINE;
    }

    /**
     * Returns string representation of mode.
	 *
	 * @return string
     */
    public function getModeStr() {

        return static::$modeMap[ $this->mode ];
    }

	/**
	 * Returns the link to embed in the email for tracking purposes.
	 *
	 * @return string
	 */
	public function getAnalyticsLink() {

		$siteUrl = CoreProperties::getInstance()->getSiteUrl();

		return "{$siteUrl}/newsletter/trigger/analytics/{$this->id}/{$this->member->gid}";
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_TRIGGER );
    }

	// CMG parent classes --------------------

	// NewsletterTrigger ---------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'newsletter', 'edition', 'member', 'member.user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the subscriber with newsletter.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with newsletter.
	 */
	public static function queryWithNewsletter( $config = [] ) {

		$config[ 'relations' ] = [ 'newsletter' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the subscriber with newsletter member.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with newsletter member.
	 */
	public static function queryWithMember( $config = [] ) {

		$config[ 'relations' ] = [ 'member', 'member.user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete the subscriber using given newsletter id.
	 *
	 * @param integer $newsletterId
	 * @return integer number of rows.
     */
    public static function deleteByNewsletterId( $newsletterId ) {

        return self::deleteAll( 'newsletterId=:id', [ ':id' => $newsletterId ] );
    }

    /**
     * Delete the subscriber using given member id.
	 *
	 * @param integer $memberId
	 * @return integer number of rows.
     */
    public static function deleteByMemberId( $memberId ) {

        return self::deleteAll( 'memberId=:id', [ ':id' => $memberId ] );
    }

}
