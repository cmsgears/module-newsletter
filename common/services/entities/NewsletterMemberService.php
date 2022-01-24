<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService;
use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

use cmsgears\core\common\services\traits\base\MultiSiteTrait;

/**
 * NewsletterMemberService provide service methods of newsletter member.
 *
 * @since 1.0.0
 */
class NewsletterMemberService extends \cmsgears\core\common\services\base\EntityService implements INewsletterMemberService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\entities\NewsletterMember';

	public static $parentType = NewsletterGlobal::TYPE_NEWSLETTER_MEMBER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $newsletterListService;

	// Private ----------------

	// Traits ------------------------------------------------------

	use MultiSiteTrait;

	// Constructor and Initialisation ------------------------------

    public function __construct( INewsletterListService $newsletterListService, $config = [] ) {

		$this->newsletterListService = $newsletterListService;

        parent::__construct( $config );
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterMemberService ---------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$userTable = Yii::$app->factory->get( 'userService' )->getModelTable();

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'user' => [
					'asc' => [ "$userTable.name" => SORT_ASC ],
					'desc' => [ "$userTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
	            'email' => [
	                'asc' => [ "$modelTable.email" => SORT_ASC ],
	                'desc' => [ "$modelTable.email" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Email'
	            ],
	            'mobile' => [
	                'asc' => [ "$modelTable.mobile" => SORT_ASC ],
	                'desc' => [ "$modelTable.mobile" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Mobile'
	            ],
	            'enabled' => [
	                'asc' => [ "$modelTable.enabled" => SORT_ASC ],
	                'desc' => [ "$modelTable.enabled" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
	            'bounced' => [
	                'asc' => [ "$modelTable.bounced" => SORT_ASC ],
	                'desc' => [ "$modelTable.bounced" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Bounced'
	            ],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				]
	        ],
			'defaultOrder' => $defaultSort
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Model
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'enabled': {

					$config[ 'conditions' ][ "$modelTable.enabled" ] = true;

					break;
				}
				case 'bounced': {

					$config[ 'conditions' ][ "$modelTable.bounced" ] = true;

					break;
				}
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.enabled" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'mobile' => "$modelTable.mobile"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'mobile' => "$modelTable.mobile",
			'enabled' => "$modelTable.enabled",
			'bounced' => "$modelTable.bounced"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByEmail( $email ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByEmail( $email );
	}

	public function getByGid( $gid ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByGid( $gid );
	}

	public function getActive( $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findActive();
	}

    // Read - Lists ----

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= $modelClass::queryWithHasOne();
		$config[ 'columns' ]	= [ "$modelTable.id", "$modelTable.name", "$modelTable.email", "$modelTable.mobile" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : false;

		$siteId		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;
		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( !$ignoreSite ) {

			$config[ 'siteId' ]	= $siteId;
		}

		$config[ 'query' ]->andWhere( "$modelTable.email like '$name%'" );

		$models = static::searchModels( $config );
		$result	= [];

		foreach( $models as $model ) {

			$result[] = [ 'id' => $model->id, 'name' => "$model->name, $model->email", 'mobile' => $model->mobile ];
		}

		return $result;
	}

    // Read - Maps -----

	// Read - Others ---

	public function getActiveCount() {

		$modelClass	= static::$modelClass;

		return $modelClass::activeCount();
	}

	// Create -------------

 	public function createByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			$member->enabled = true;

			return parent::update( $member, [
				'attributes' => [ 'enabled' ]
			]);
		}

		return parent::createByParams( $params, $config );
 	}

	public function signUp( $signUpForm ) {

		$notification = isset( $config[ 'notification' ] ) ? $config[ 'notification' ] : [];

		$member	= $this->getByEmail( $signUpForm->email );

		// Create Newsletter Member
		if( empty( $member ) ) {

			$member	= $this->getModelObject();

			$member->name 	= $signUpForm->name;
			$member->email 	= $signUpForm->email;
			$member->mobile	= $signUpForm->mobile;

			$member->enabled	= true;
			$member->bounced	= false;

			$member = $this->create( $member );

			$notification[ 'template' ]		= isset( $notification[ 'template' ] ) ? $notification[ 'template' ] : NewsletterGlobal::TPL_NOTIFY_NEWSLETTER_SIGNUP;
			$notification[ 'adminLink' ]	= isset( $notification[ 'adminLink' ] ) ? $notification[ 'adminLink' ] : "newsletter/member/update?id=$member->id";

			// Trigger Notification
			$this->notifyAdmin( $member, $notification );
		}

		// Add to specific and selected mailing list
		if( isset( $signUpForm->newsletterId ) ) {

			$this->newsletterListService->createByParams([
				'newsletterId' => $signUpForm->newsletterId,
				'memberId' => $member->id, 'enabled' => true
			]);
		}

		return $member;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'name', 'mobile', 'enabled', 'bounced'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'userId', 'email'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function updateByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			return parent::update( $member, [
				'attributes' => [ 'name', 'mobile', 'enabled', 'bounced' ]
			]);
		}

		return $this->createByParams( $params, $config );
	}

	public function activate( $model, $config = [] ) {

		$model->enabled = true;

		return parent::update( $model, [
			'attributes' => [ 'enabled' ]
		]);
	}

	public function disable( $model, $config = [] ) {

		$model->enabled = true;

		return parent::update( $model, [
			'attributes' => [ 'enabled' ]
		]);
	}

	public function toggleActive( $model, $config = [] ) {

		$enabled = $model->enabled ? false : true;

		$model->enabled = $enabled;

		return parent::updateSelective( $model, [
			'attributes' => [ 'enabled' ]
		]);
 	}

	public function bounced( $model, $config = [] ) {

		$model->bounced = true;

		return parent::update( $model, [
			'attributes' => [ 'bounced' ]
		]);
	}

	// Delete -------------

	public function deleteByEmail( $email, $config = [] ) {

		$member	= $this->getByEmail( $email );

		if( isset( $member ) ) {

			$modelClass	= static::$modelClass;

			// Delete from mailing list
			Yii::$app->factory->get( 'newsletterListService' )->deleteByMemberId( $member->id );

			// Delete member
			return $modelClass::deleteByEmail( $email );
		}

		return false;
	}

	public function delete( $model, $config = [] ) {

		$model = $this->getById( $model->id );

		if( isset( $model ) ) {

			// Delete from mailing lists
			Yii::$app->factory->get( 'newsletterListService' )->deleteByMemberId( $model->id );

			// Delete member
			return parent::delete( $model );
		}

		return false;
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'activate': {

						$this->activate( $model );

						break;
					}
					case 'disable': {

						$this->disable( $model );

						break;
					}
					case 'bounced': {

						$this->bounced( $model );

						break;
					}
					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NewsletterMemberService ---------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
