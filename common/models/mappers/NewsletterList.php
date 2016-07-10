<?php
namespace cmsgears\newsletter\common\models\mappers;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\entities\Newsletter;
use cmsgears\newsletter\common\models\entities\NewsletterMember;

/**
 * NewsletterList Entity
 *
 * @property long $id
 * @property long $newsletterId
 * @property long $memberId
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
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
                'class' => TimestampBehavior::className(),
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

        return [
            [ [ 'newsletterId', 'memberId' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'newsletterId', 'memberId' ], 'unique', 'targetAttribute' => [ 'newsletterId', 'memberId' ] ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'newsletterId', 'memberId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
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

	public function getNewsletter() {

		return $this->hasOne( Newsletter::className(), [ 'id' => 'newsletterId' ] );
	}

	public function getMember() {

		return $this->hasOne( NewsletterMember::className(), [ 'id' => 'memberId' ] );
	}

    /**
     * @return string representation of flag
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

        return NewsletterTables::TABLE_NEWSLETTER_LIST;
    }

	// CMG parent classes --------------------

	// NewsletterList ------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'newsletter', 'member', 'member.user' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithNewsletter( $config = [] ) {

		$config[ 'relations' ]	= [ 'newsletter' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithMember( $config = [] ) {

		$config[ 'relations' ]	= [ 'member', 'member.user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete the member.
     */
    public static function deleteByNewsletterId( $newsletterId ) {

        self::deleteAll( 'newsletterId=:id', [ ':id' => $newsletterId ] );
    }

    /**
     * Delete the member.
     */
    public static function deleteByMemberId( $memberId ) {

        self::deleteAll( 'memberId=:id', [ ':id' => $memberId ] );
    }
}
