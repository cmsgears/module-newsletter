<?php
namespace cmsgears\newsletter\common\services\mappers;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\mappers\NewsletterList;

use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

/**
 * The class NewsletterListService is base class to perform database activities for NewsletterList Entity.
 */
class NewsletterListService extends \cmsgears\core\common\services\base\EntityService implements INewsletterListService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\newsletter\common\models\mappers\NewsletterList';

	public static $modelTable	= NewsletterTables::TABLE_NEWSLETTER_LIST;

	public static $parentType	= null;

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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;

		$nlTable		= NewsletterTables::TABLE_NEWSLETTER;
		$memberTable	= NewsletterTables::TABLE_NEWSLETTER_MEMBER;
		$userTable		= CoreTables::TABLE_USER;

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
	            'user' => [
					'asc' => [ "`$userTable`.`firstName`" => SORT_ASC, "`$userTable`.`lastName`" => SORT_ASC ],
					'desc' => [ "`$userTable`.`firstName`" => SORT_DESC, "`$userTable`.`lastName`" => SORT_DESC ],
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
	                'asc' => [ 'active' => SORT_ASC ],
	                'desc' => ['active' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'cdate',
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
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
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$memberTable.name", 'email' => "$memberTable.email", 'newsletter' => "$nlTable.name" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$memberTable.name", 'email' => "$memberTable.email", 'newsletter' => "$nlTable.name",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByMemberId( $memberId ) {

		return NewsletterList::findByMemberId( $memberId );
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

	public static function deleteByMemberId( $memberId ) {

		$member	= $this->getByMemberId( $memberId );

		if( isset( $member ) ) {

			NewsletterList::deleteByMemberId( $memberId );

			return true;
		}

		return false;
	}

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
