<?php
namespace cmsgears\newsletter\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\newsletter\common\models\base\NewsletterTables;

/**
 * NewsletterMember Entity
 *
 * @property long $id
 * @property long $userId
 * @property string $name
 * @property string $email
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class NewsletterMember extends \cmsgears\core\common\models\base\Entity {

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

        // model rules
        $rules = [
        	// Required, Safe
            [ [ 'email' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            // Unique
            [ 'email', 'unique' ],
            // Email
            [ 'email', 'email' ],
            // Text Limit
            [ [ 'name', 'email' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            // Other
            [ 'active', 'boolean' ],
            [ [ 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'email' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'user' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
            'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterMember ----------------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
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

        return NewsletterTables::TABLE_NEWSLETTER_MEMBER;
    }

	// CMG parent classes --------------------

	// NewsletterMember ----------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithUser( $config = [] ) {

		$config[ 'relations' ]	= [ 'user' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

    /**
     * @param string $email
     * @return NewsletterMember - by email
     */
    public static function findByEmail( $email ) {

        return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
    }

    /**
     * @param string $email
     * @return NewsletterMember - by email
     */
    public static function isExistByEmail( $email ) {

        $member = self::findByEmail( $email );

        return isset( $member );
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------

    /**
     * Delete the member.
     */
    public static function deleteByEmail( $email ) {

        self::deleteAll( 'email=:email', [ ':email' => $email ] );
    }
}
