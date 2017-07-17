<?php
namespace cmsgears\newsletter\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\newsletter\common\models\base\NewsletterTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\TemplateTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Newsletter Entity
 *
 * @property long $id
 * @property long $templateId
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property boolean $global
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 * @property string $content
 * @property string $data
 */
class Newsletter extends \cmsgears\core\common\models\base\Entity {

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

    use CreateModifyTrait;
    use DataTrait;
    use FileTrait;
	use MetaTrait;
	use NameTypeTrait;
	use SlugTypeTrait;
    use TemplateTrait;

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
                'class' => AuthorBehavior::className()
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::className(),
				'attribute' => 'name',
				'slugAttribute' => 'slug',
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => 'type' ]
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
			[ [ 'name' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Unique
			[ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'slug', 'description' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'global', 'active' ], 'boolean' ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'global' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GLOBAL ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

	// yii\db\BaseActiveRecord

	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Newsletter ----------------------------

    public function getGlobalStr() {

        return Yii::$app->formatter->asBoolean( $this->global );
    }

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

        return NewsletterTables::TABLE_NEWSLETTER;
    }

	// CMG parent classes --------------------

	// Newsletter ----------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'template', 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithTemplate( $config = [] ) {

		$config[ 'relations' ]	= [ 'template' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
