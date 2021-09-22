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
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IApproval;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\IFile;

use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\resources\NewsletterMeta;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Newsletter publishes the digital content via mails.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $bannerId
 * @property integer $templateId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property boolean $multiple
 * @property boolean $global
 * @property integer $status
 * @property boolean $triggered
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $publishedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Newsletter extends \cmsgears\core\common\models\base\Entity implements IAuthor, IApproval, IData,
	IFile, IGridCache, IMultiSite, INameType, IOwner, ISlugType, ITemplate, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = NewsletterGlobal::TYPE_NEWSLETTER;

	// Private ----------------

	// Traits ------------------------------------------------------

    use AuthorTrait;
	use ApprovalTrait;
    use DataTrait;
    use FileTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use OwnerTrait;
	use SlugTypeTrait;
    use TemplateTrait;
	use VisualTrait;

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
            ],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'slug' ] ]
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
			[ [ 'siteId', 'name' ], 'required' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			//[ 'name', 'unique', 'targetAttribute' => [ 'siteId', 'type', 'name' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NAME ) ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'multiple', 'global', 'triggered', 'gridCacheValid' ], 'boolean' ],
			[ 'status', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'siteId', 'userId', 'bannerId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'publishedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

       // Trim Text
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'multiple' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MULTIPLE ),
			'global' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GLOBAL ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'triggered' => Yii::$app->newsletterMessage->getMessage( NewsletterGlobal::FIELD_TRIGGERED ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
        ];
    }

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Template
			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			// Default User
			if( empty( $this->userId ) || $this->userId <= 0 ) {

				$this->userId = null;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Newsletter ----------------------------

	/**
	 * Return meta data of the newsletter.
	 *
	 * @return \cmsgears\newsletter\common\models\resources\NewsletterMeta[]
	 */
	public function getMetas() {

		return $this->hasMany( NewsletterMeta::class, [ 'modelId' => 'id' ] );
	}

	/**
	 * String representation of multiple flag.
	 *
	 * @return string
	 */
    public function getMultipleStr() {

        return Yii::$app->formatter->asBoolean( $this->multiple );
    }

	/**
	 * String representation of global flag.
	 *
	 * @return string
	 */
    public function getGlobalStr() {

        return Yii::$app->formatter->asBoolean( $this->global );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

     /**
     * @inheritdoc
     */
    public static function tableName() {

        return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER );
    }

	// CMG parent classes --------------------

	// Newsletter ----------------------------

	// Read - Query -----------

     /**
     * @inheritdoc
     */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'template', 'user' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
