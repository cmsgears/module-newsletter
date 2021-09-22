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
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\services\interfaces\resources\INewsletterLinkService;

/**
 * NewsletterLinkService provide service methods of newsletter link.
 *
 * @since 1.0.0
 */
class NewsletterLinkService extends \cmsgears\core\common\services\base\ResourceService implements INewsletterLinkService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\resources\NewsletterLink';

	public static $parentType = NewsletterGlobal::TYPE_NEWSLETTER_LINK;

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

	// NewsletterLinkService --------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$nlTable		= Yii::$app->factory->get( 'newsletterService' )->getModelTable();
		$nlEditionTable	= Yii::$app->factory->get( 'newsletterEditionService' )->getModelTable();

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
				'title' => [
	                'asc' => [ "$modelTable.title" => SORT_ASC ],
	                'desc' => [ "$modelTable.title" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Title'
	            ],
				'redirect' => [
	                'asc' => [ "$modelTable.redirect" => SORT_ASC ],
	                'desc' => [ "$modelTable.redirect" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Redirect URL'
	            ],
				'wbanner' => [
	                'asc' => [ "$modelTable.wrapBanner" => SORT_ASC ],
	                'desc' => [ "$modelTable.wrapBanner" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Wrap Banner'
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
			'title' => "$modelTable.title",
			'redirect' => "$modelTable.redirect",
			'newsletter' => "$nlTable.name",
			'edition' => "$nlEditionTable.name"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$modelTable.title",
			'redirect' => "$modelTable.redirect",
			'newsletter' => "$nlTable.name",
			'edition' => "$nlEditionTable.name",
			'wbanner' => "$modelTable.wrapBanner"
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

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'title', 'redirect', 'wrapBanner'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'newsletterId', 'editionId'
			]);
		}

		return parent::update( $model, [
			'attributes' => $attributes
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

	// NewsletterLinkService -----------------

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
