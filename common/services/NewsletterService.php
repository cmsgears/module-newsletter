<?php
namespace cmsgears\newsletter\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;
use cmsgears\newsletter\common\models\entities\Newsletter;

/**
 * The class NewsletterService is base class to perform database activities for Newsletter Entity.
 */
class NewsletterService extends \cmsgears\core\common\services\Service {

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

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

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