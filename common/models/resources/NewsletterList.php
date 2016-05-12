<?php
namespace cmsgears\newsletter\common\models\resources;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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
class NewsletterList extends \cmsgears\core\common\models\base\CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

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

    // yii\base\Model --------------------

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
            'newsletterId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NEWSLETTER ),
            'memberId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MEMBER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

    // NewsletterList --------------------

	public function getNeqwsletter() {

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

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::TABLE_NEWSLETTER_LIST;
    }

    // NewsletterList --------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------

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

?>