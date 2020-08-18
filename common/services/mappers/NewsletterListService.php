<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\mappers;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

/**
 * NewsletterListService provide service methods of newsletter list.
 *
 * @since 1.0.0
 */
class NewsletterListService extends \cmsgears\core\common\services\base\MapperService implements INewsletterListService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\mappers\NewsletterList';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterListService -----------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$nlTable		= Yii::$app->factory->get( 'newsletterService' )->getModelTable();
		$memberTable	= Yii::$app->factory->get( 'newsletterMemberService' )->getModelTable();

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'name' => [
	                'asc' => [ "$memberTable.name" => SORT_ASC ],
	                'desc' => [ "$memberTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Name'
	            ],
				'email' => [
	                'asc' => [ "$memberTable.email" => SORT_ASC ],
	                'desc' => [ "$memberTable.email" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Email'
	            ],
	            'newsletter' => [
	                'asc' => [ "$nlTable.name" => SORT_ASC ],
	                'desc' => [ "$nlTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Newsletter'
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
	                'label' => 'Created At',
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

		// Filter - Status
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= true;

					break;
				}
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$memberTable.name",
			'email' => "$memberTable.email",
			'newsletter' => "$nlTable.name"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$memberTable.name",
			'email' => "$memberTable.email",
			'newsletter' => "$nlTable.name",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByNewsletterIdMemberId( $newsletterId, $memberId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByNewsletterIdMemberId( $newsletterId, $memberId );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'lastSentAt'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'newsletterId', 'memberId', 'active'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function activate( $model, $config = [] ) {

		$model->active = true;

		return parent::update( $model, [
			'attributes' => [ 'active' ]
		]);
	}

	public function disable( $model, $config = [] ) {

		$model->active = false;

		return parent::update( $model, [
			'attributes' => [ 'active' ]
		]);
	}

	public function toggleActive( $model, $config = [] ) {

		$active	= $model->active ? false : true;

		$model->active = $active;

		return parent::updateSelective( $model, [
			'attributes' => [ 'active' ]
		]);
 	}

	// Delete -------------

	public function deleteByMemberId( $memberId, $config = [] ) {

		$modelClass	= static::$modelClass;

		return $modelClass::deleteByMemberId( $memberId );
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

	// NewsletterListService -----------------

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
