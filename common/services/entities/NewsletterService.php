<?php
namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\newsletter\common\models\entities\Newsletter;

/**
 * The class NewsletterService is base class to perform database activities for Newsletter Entity.
 */
class NewsletterService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Newsletter
	 */
	public static function findById( $id ) {

		return Newsletter::findById( $id );
	}

	// Data Provider ----

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
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
	            ],
	            'ldate' => [
	                'asc' => [ 'lastSentAt' => SORT_ASC ],
	                'desc' => ['lastSentAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'udate',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Newsletter(), $config );
	}

	// Create -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function create( $newsletter ) {

		// Template
		if( isset( $newsletter->templateId ) && $newsletter->templateId <= 0 ) {

			$newsletter->templateId = null;
		}

		// Create Newsletter
		$newsletter->save();

		// Return Newsletter
		return $newsletter;
	}

	// Update -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function update( $newsletter ) {

		// Template
		if( isset( $newsletter->templateId ) && $newsletter->templateId <= 0 ) {

			$newsletter->templateId = null;
		}

		// Find existing Newsletter
		$nlToUpdate	= self::findById( $newsletter->id );

		// Copy and set Attributes
		$nlToUpdate->copyForUpdateFrom( $newsletter, [ 'templateId', 'name', 'description', 'content' ] );

		// Update Newsletter
		$nlToUpdate->update();

		// Return updated Newsletter
		return $nlToUpdate;
	}

	// Delete -----------

	/**
	 * @param Newsletter $newsletter
	 * @return boolean
	 */
	public static function delete( $newsletter ) {

		// Find existing Newsletter
		$nlToDelete	= self::findById( $newsletter->id );

		// Delete Newsletter
		$nlToDelete->delete();

		return true;
	}
}

?>