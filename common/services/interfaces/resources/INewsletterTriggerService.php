<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IResourceService;

/**
 * INewsletterTriggerService declares methods specific to newsletter trigger.
 *
 * @since 1.0.0
 */
interface INewsletterTriggerService extends IResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function markSent( $model, $config = [] );

	public function markDelivered( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
