<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\models\mappers;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\entities\Newsletter;
use cmsgears\newsletter\common\models\entities\NewsletterMember;

/**
 * NewsletterList maintains newsletter subscribers specific to newsletter.
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $memberId
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 *
 * @since 1.0.0
 */
class NewsletterList extends \cmsgears\core\common\models\base\Mapper {

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
            [ [ 'newsletterId', 'memberId' ], 'required' ],
            [ 'id', 'safe' ],
            [ [ 'newsletterId', 'memberId' ], 'unique', 'targetAttribute' => [ 'newsletterId', 'memberId' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
            [ 'active', 'boolean' ],
            [ [ 'newsletterId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'newsletterId' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_NEWSLETTER ),
            'memberId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MEMBER ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterList ------------------------

	/**
	 * Returns corresponding newsletter.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\Newsletter
	 */
	public function getNewsletter() {

		return $this->hasOne( Newsletter::class, [ 'id' => 'newsletterId' ] );
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
     * Returns string representation of active flag.
	 *
	 * @return string
     */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_LIST );
    }

	// CMG parent classes --------------------

	// NewsletterList ------------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'newsletter', 'member', 'member.user' ];

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

	/**
	 * Find and return the subscriber using given newsletter id and member id.
	 *
	 * @param integer $newsletterId
	 * @param integer $memberId
	 * @return NewsletterList
	 */
    public static function findByNewsletterIdMemberId( $newsletterId, $memberId ) {

        return self::find()->where( 'newsletterId=:nid AND memberId=:mid', [ ':nid' => $newsletterId, ':mid' => $memberId ] )->one();
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
