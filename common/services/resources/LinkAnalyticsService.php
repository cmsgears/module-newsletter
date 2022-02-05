<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\resources;

// Yii Imports
use Yii;
use yii\data\Sort;
use yii\db\Query;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\newsletter\common\services\interfaces\resources\ILinkAnalyticsService;

/**
 * LinkAnalyticsService provide service methods of link analytic.
 *
 * @since 1.0.0
 */
class LinkAnalyticsService extends \cmsgears\core\common\services\base\ResourceService implements ILinkAnalyticsService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\resources\LinkAnalytics';

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

	// LinkAnalyticsService ------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$nlTable		= Yii::$app->factory->get( 'newsletterService' )->getModelTable();
		$nlEditionTable	= Yii::$app->factory->get( 'newsletterEditionService' )->getModelTable();
		$nlLinkTable	= Yii::$app->factory->get( 'newsletterLinkService' )->getModelTable();
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
	            'newsletter' => [
	                'asc' => [ "$nlTable.name" => SORT_ASC ],
	                'desc' => [ "$nlTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Newsletter'
	            ],
	            'edition' => [
	                'asc' => [ "$nlEditionTable.name" => SORT_ASC ],
	                'desc' => [ "$nlEditionTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Edition'
	            ],
	            'link' => [
	                'asc' => [ "$nlLinkTable.title" => SORT_ASC ],
	                'desc' => [ "$nlLinkTable.title" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Link'
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
	            'visits' => [
	                'asc' => [ "$modelTable.visits" => SORT_ASC ],
	                'desc' => [ "$modelTable.visits" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Visits'
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

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$memberTable.name",
			'email' => "$memberTable.email",
			'newsletter' => "$nlTable.name",
			'edition' => "$nlEditionTable.name",
			'link' => "$nlLinkTable.title"
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
			'edition' => "$nlEditionTable.name",
			'link' => "$nlLinkTable.title",
			'visits' => "$modelTable.visits"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	public function getPageByLinkId( $linkId, $config = [] ) {

		$modelTable = $this->getModelTable();

		$config[ 'conditions'][ "$modelTable.linkId" ] = $linkId;

		return $this->getPage( $config );
	}
	
	// Read ---------------

    // Read - Models ---

	public function getByLinkIdMemberId( $linkId, $memberId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByLinkIdMemberId( $linkId, $memberId );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	public function getCountByNewsletterIdMemberId( $newsletterId, $memberId ) {

		$modelTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ 'sum(visits) as total' ] )
			->from( $modelTable )
			->where( [ 'newsletterId' => $newsletterId, 'memberId' => $memberId ] );

		$counts = $query->one();

       return !empty( $counts[ 'total' ] ) ? $counts[ 'total' ] : 0;
	}

	public function getCountByEditionIdMemberId( $editionId, $memberId ) {

		$modelTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ 'sum(visits) as total' ] )
			->from( $modelTable )
			->where( [ 'editionId' => $editionId, 'memberId' => $memberId ] );

		$counts = $query->one();

       return !empty( $counts[ 'total' ] ) ? $counts[ 'total' ] : 0;
	}

	// Create -------------

	public function create( $model, $config = [] ) {

		$model->agent	= Yii::$app->request->userAgent;
		$model->ip		= Yii::$app->request->userIP;
		$model->visits	= empty( $model->visits ) ? 1 : $model->visits;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'visits'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'newsletterId', 'editionId', 'linkId'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function incVisits( $model, $config = [] ) {

		$model->visits = $model->visits + 1;

		return parent::update( $model, [
			'attributes' => [ 'visits' ]
		]);
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

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

	// LinkAnalyticsService ------------------

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
