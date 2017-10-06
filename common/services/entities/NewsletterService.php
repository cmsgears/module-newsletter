<?php
namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\newsletter\common\models\base\NewsletterTables;

use cmsgears\newsletter\common\services\interfaces\entities\INewsletterService;

use cmsgears\core\common\services\traits\NameTypeTrait;
use cmsgears\core\common\services\traits\SlugTypeTrait;

/**
 * The class NewsletterService is base class to perform database activities for Newsletter Entity.
 */
class NewsletterService extends \cmsgears\core\common\services\base\EntityService implements INewsletterService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\newsletter\common\models\entities\Newsletter';

	public static $modelTable	= NewsletterTables::TABLE_NEWSLETTER;

	public static $typed		= true;

	public static $parentType	= NewsletterGlobal::TYPE_NEWSLETTER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterService ---------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$templateTable	= CoreTables::TABLE_TEMPLATE;

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
	            'template' => [
	                'asc' => [ "`$templateTable`.`name`" => SORT_ASC ],
	                'desc' => [ "`$templateTable`.`name`" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Template'
	            ],
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Slug'
	            ],
	            'type' => [
	                'asc' => [ 'type' => SORT_ASC ],
	                'desc' => ['type' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ 'icon' => SORT_ASC ],
	                'desc' => ['icon' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
	            'global' => [
	                'asc' => [ 'global' => SORT_ASC ],
	                'desc' => ['global' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Global'
	            ],
	            'active' => [
	                'asc' => [ 'active' => SORT_ASC ],
	                'desc' => ['active' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ],
	            'ldate' => [
	                'asc' => [ 'lastSentAt' => SORT_ASC ],
	                'desc' => ['lastSentAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Sent At'
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
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			switch( $status ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= true;

					break;
				}
				case 'global': {

					$config[ 'conditions' ][ "$modelTable.global" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'desc' => "$modelTable.description", 'content' => "$modelTable.content",
			'type' => "$modelTable.type", 'global' => "$modelTable.global", 'active' => "$modelTable.active"
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

	public function create( $model, $config = [] ) {

		if( empty( $model->type ) ) {

			$model->type	= CoreGlobal::TYPE_DEFAULT;
		}

		return parent::create( $model, $config );
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin		= isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;
		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'templateId', 'name', 'icon', 'description', 'content' ];

		if( $admin ) {

			$attributes[]	= 'global';
			$attributes[]	= 'active';
		}

		return parent::update( $model, [
			'attributes' => $attributes
		]);
 	}

	public function switchGlobal( $model, $config = [] ) {

		$global			= $model->global ? false : true;
		$model->global	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
 	}

	public function switchActive( $model, $config = [] ) {

		$global			= $model->global ? false : true;
		$model->global	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
 	}

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'global': {

						$model->global = true;

						$model->update();

						break;
					}
					case 'specific': {

						$model->global = false;

						$model->update();

						break;
					}
					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'block': {

						$model->active = false;

						$model->update();

						break;
					}
				}

				break;
			}
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

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NewsletterService ---------------------

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
