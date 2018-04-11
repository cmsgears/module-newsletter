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

// CMG Imports
use cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService;
use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

use cmsgears\core\common\services\base\EntityService;

/**
 * NewsletterMemberService provide service methods of newsletter member.
 *
 * @since 1.0.0
 */
class NewsletterMemberService extends EntityService implements INewsletterMemberService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\entities\NewsletterMember';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $newsletterListService;

	// Private ----------------

	// Traits ------------------------------------------------------

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
	            'active' => [
	                'asc' => [ "$modelTable.active" => SORT_ASC ],
	                'desc' => [ "$modelTable.active" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
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
	        ]
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

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'email' => "$modelTable.email"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'email' => "$modelTable.email",
			'active' => "$modelTable.active"
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

    // Read - Lists ----

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$config[ 'query' ]		= $modelClass::queryWithHasOne();
		$config[ 'columns' ]	= [ "$modelTable.id", "$modelTable.name", "$modelTable.email" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : false;

		$config[ 'query' ]->andWhere( "$modelTable.name like '$name%'" );

		$models = static::searchModels( $config );
		$result	= [];

		foreach( $models as $model ) {

			$result[] = [ 'id' => $model->id, 'name' => "$model->name, $model->email" ];
		}

		return $result;
	}

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

 	public function createByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			return $member;
		}

		return parent::createByParams( $params, $config );
 	}

	public function signUp( $signUpForm ) {

		$member	= $this->getByEmail( $signUpForm->email );

		// Create Newsletter Member
		if( !isset( $member ) ) {

			$member	= $this->getModelObject();

			$member->email 	= $signUpForm->email;
			$member->name 	= $signUpForm->name;
			$member->active = true;

			$member->save();
		}

		// Add to specific and selected mailing list
		if( isset( $signUpForm->newsletterId ) ) {

			$this->newsletterListService->createByParams( [ 'newsletterId' => $signUpForm->newsletterId, 'memberId' => $member->id, 'active' => true ] );
		}

		return $member;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'email', 'name', 'active' ]
		]);
 	}

	public function updateByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			return parent::update( $member, [
				'attributes' => [ 'name', 'active' ]
			]);
		}

		return $this->createByParams( $params, $config );
	}

	public function toggleActive( $model, $config = [] ) {

		$global	= $model->global ? false : true;

		$model->global = $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
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

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'inactive': {

						$model->active = false;

						$model->update();

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
