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

use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\entities\Newsletter;
use cmsgears\newsletter\common\models\entities\NewsletterEdition;
use cmsgears\newsletter\common\models\entities\NewsletterMember;

/**
 * LinkAnalytics maintains newsletter link analytic.
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $editionId
 * @property integer $linkId
 * @property integer $memberId
 * @property string $ip
 * @property integer $ipNum
 * @property string $agent
 * @property integer $visits
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 *
 * @since 1.0.0
 */
class LinkAnalytics extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

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
            [ [ 'newsletterId', 'linkId', 'memberId' ], 'required' ],
            [ 'id', 'safe' ],
			// Text Limit
			[ 'ip', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			// Other
            [ [ 'ipNum', 'visits' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'newsletterId', 'editionId', 'linkId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
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
            'linkId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK ),
            'memberId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MEMBER )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// LinkAnalytics -------------------------

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
	 * Returns corresponding newsletter link.
	 *
	 * @return \cmsgears\newsletter\common\models\resources\NewsletterLink
	 */
	public function getLink() {

		return $this->hasOne( NewsletterLink::class, [ 'id' => 'linkId' ] );
	}

	/**
	 * Returns corresponding newsletter member.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\NewsletterMember
	 */
	public function getMember() {

		return $this->hasOne( NewsletterMember::class, [ 'id' => 'memberId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_LINK_ANALYTICS );
    }

	// CMG parent classes --------------------

	// LinkAnalytics -------------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'newsletter', 'edition', 'link', 'member' ];

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

    public static function findByLinkIdMemberId( $linkId, $memberId ) {

        return self::find()->where( 'linkId=:lid AND memberId=:mid', [ ':lid' => $linkId, ':mid' => $memberId ] )->one();
    }

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
