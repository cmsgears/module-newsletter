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

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * NewsletterLink maintains newsletter links specific to newsletter.
 *
 * @property integer $id
 * @property integer $newsletterId
 * @property integer $editionId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $title
 * @property string $redirect
 * @property boolean $wrapBanner
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 *
 * @since 1.0.0
 */
class NewsletterLink extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NewsletterGlobal::TYPE_NEWSLETTER_LINK;

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
            'authorBehavior' => [
                'class' => AuthorBehavior::class
            ],
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
            [ [ 'newsletterId', 'title', 'redirect' ], 'required' ],
            [ 'id', 'safe' ],
			// Text Limit
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'redirect', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ 'redirect', 'url' ],
			[ 'wrapBanner', 'boolean' ],
			[ [ 'newsletterId', 'editionId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
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
            'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'redirect' => 'Redirect URL',
			'wrapBanner' => 'Wraps Banner'
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterLink ------------------------

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

	public function getWrapBannerStr() {

		return Yii::$app->formatter->asBoolean( $this->wrapBanner );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_LINK );
    }

	// CMG parent classes --------------------

	// NewsletterLink ---------------------

	// Read - Query -----------

    /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'newsletter', 'edition' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the link with newsletter.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with newsletter.
	 */
	public static function queryWithNewsletter( $config = [] ) {

		$config[ 'relations' ] = [ 'newsletter' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete the link using given newsletter id.
	 *
	 * @param integer $newsletterId
	 * @return integer number of rows.
     */
    public static function deleteByNewsletterId( $newsletterId ) {

        return self::deleteAll( 'newsletterId=:id', [ ':id' => $newsletterId ] );
    }

}
