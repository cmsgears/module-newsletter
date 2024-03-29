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
use cmsgears\core\common\services\interfaces\base\IEntityService;
use cmsgears\core\common\services\interfaces\base\IMultiSite;

/**
 * INewsletterMemberService declares methods specific to newsletter member.
 *
 * @since 1.0.0
 */
interface INewsletterMemberService extends IEntityService, IMultiSite {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByEmail( $email );

	public function getByGid( $gid );

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function signUp( $signUpForm );

	// Update -------------

	public function activate( $model, $config = [] );

	public function disable( $model, $config = [] );

	public function toggleActive( $model, $config = [] );

	public function bounced( $model, $config = [] );

	// Delete -------------

	public function deleteByEmail( $email, $config = [] );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
