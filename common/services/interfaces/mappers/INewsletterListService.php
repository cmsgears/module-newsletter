<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\interfaces\mappers;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IMapperService;

/**
 * INewsletterListService declares methods specific to newsletter list.
 *
 * @since 1.0.0
 */
interface INewsletterListService extends IMapperService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggleActive( $model, $config = [] );

	// Delete -------------

	public function deleteByMemberId( $memberId, $config = [] );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
