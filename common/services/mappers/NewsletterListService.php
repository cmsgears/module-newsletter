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

// CMG Imports
use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

use cmsgears\core\common\services\base\MapperService;

/**
 * NewsletterListService provide service methods of newsletter list.
 *
 * @since 1.0.0
 */
class NewsletterListService extends MapperService implements INewsletterListService {

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

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$nlTable		= Yii::$app->factory->get( 'newsletterService' )->getModelTable();
		$memberTable	= Yii::$app->factory->get( 'newsletterMemberService' )->getModelTable();
		$userTable		= Yii::$app->factory->get( 'userService' )->getModelTable();

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
				'member' => [
	                'asc' => [ "`$memberTable`.`name`" => SORT_ASC ],
	                'desc' => [ "`$memberTable`.`name`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Name'
	            ],
				'email' => [
	                'asc' => [ "`$memberTable`.`email`" => SORT_ASC ],
	                'desc' => [ "`$memberTable`.`email`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Email'
	            ],
	            'newsletter' => [
	                'asc' => [ "`$nlTable`.`name`" => SORT_ASC ],
	                'desc' => [ "`$nlTable`.`name`" => SORT_DESC ],
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

		// Filter - Status
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
				'name' => "$memberTable.name",
				'email' => "$memberTable.email",
				'newsletter' => "$nlTable.name"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
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

	public function getByMemberId( $memberId ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByMemberId( $memberId );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'newsletterId', 'memberId', 'active' ]
		]);
 	}

	public function toggleActive( $model, $config = [] ) {

		$global	= $model->global ? false : true;

		$model->global = $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
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
