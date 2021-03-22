<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IApproval;
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\base\INameType;
use cmsgears\core\common\services\interfaces\base\ISlugType;
use cmsgears\core\common\services\interfaces\cache\IGridCacheable;
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * INewsletterEditionService declares methods specific to newsletter member.
 *
 * @since 1.0.0
 */
interface INewsletterEditionService extends IEntityService, IApproval, IData,
	IGridCacheable, INameType, ISlugType {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
