<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\models\resources;

// CMG Imports
use cmsgears\newsletter\common\models\base\NewsletterTables;

use cmsgears\newsletter\common\models\entities\Newsletter;

/**
 * The meta model used to store newsletter meta data and attributes.
 *
 * @inheritdoc
 */
class NewsletterMeta extends \cmsgears\core\common\models\base\Meta {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// NewsletterMeta ------------------------

	/**
	 * Returns the site model using one-to-one(hasOne) relationship.
	 *
	 * @return \cmsgears\newsletter\common\models\entities\Newsletter Newsletter to which this meta belongs.
	 */
	public function getParent() {

		return $this->hasOne( Newsletter::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_META );
	}

	// CMG parent classes --------------------

	// NewsletterMeta ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
