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

// CMG Imports
use cmsgears\newsletter\common\services\interfaces\resources\INewsletterTriggerService;

/**
 * NewsletterTriggerService provide service methods of newsletter trigger.
 *
 * @since 1.0.0
 */
class NewsletterTriggerService extends \cmsgears\core\common\services\base\ResourceService implements INewsletterTriggerService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\resources\NewsletterTrigger';

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

	// NewsletterTriggerService --------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$nlTable		= Yii::$app->factory->get( 'newsletterService' )->getModelTable();
		$nlEditionTable	= Yii::$app->factory->get( 'newsletterEditionService' )->getModelTable();
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
	            'sent' => [
	                'asc' => [ "$modelTable.sent" => SORT_ASC ],
	                'desc' => [ "$modelTable.sent" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Sent'
	            ],
	            'delivered' => [
	                'asc' => [ "$modelTable.delivered" => SORT_ASC ],
	                'desc' => [ "$modelTable.delivered" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Delivered'
	            ],
	            'mode' => [
	                'asc' => [ "$modelTable.mode" => SORT_ASC ],
	                'desc' => [ "$modelTable.mode" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Mode'
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
	            ],
	            'sdate' => [
	                'asc' => [ "$modelTable.sentAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.sentAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Sent At'
	            ],
	            'ddate' => [
	                'asc' => [ "$modelTable.deliveredAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.deliveredAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Delivered At'
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

				case 'sent': {

					$config[ 'conditions' ][ "$modelTable.sent" ] = true;

					break;
				}
				case 'delivered': {

					$config[ 'conditions' ][ "$modelTable.delivered" ] = true;

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
			'newsletter' => "$nlTable.name",
			'edition' => "$nlEditionTable.name"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$memberTable.name",
			'email' => "$memberTable.email",
			'newsletter' => "$nlTable.name",
			'edition' => "$nlEditionTable.name",
			'sent' => "$modelTable.sent",
			'delivered' => "$modelTable.delivered"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		//$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'sent', 'delivered', 'mode', 'sentAt', 'deliveredAt'
		];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	public function markSent( $model, $config = [] ) {

		$model->sent = true;

		return parent::update( $model, [
			'attributes' => [ 'sent' ]
		]);
	}

	public function markDelivered( $model, $config = [] ) {

		$model->delivered = false;

		return parent::update( $model, [
			'attributes' => [ 'delivered' ]
		]);
	}

	// Delete -------------

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'sent': {

						$this->markSent( $model );

						break;
					}
					case 'delivered': {

						$this->markDelivered( $model );

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

	// NewsletterTriggerService --------------

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
