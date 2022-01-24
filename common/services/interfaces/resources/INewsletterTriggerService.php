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

	public function getPageByNewsletterId( $newsletterId, $config = [] );

	public function getPageByEditionId( $editionId, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByNewsletterId( $newsletterId );

	public function getByNewsletterIdMemberId( $newsletterId, $memberId );

	public function getByEditionId( $editionId );

	public function getByEditionIdMemberId( $editionId, $memberId );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function markSent( $model, $config = [] );

	public function markDelivered( $model, $config = [] );

	public function markRead( $model, $config = [] );

	public function incrementReadCount( $model, $config = [] );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
