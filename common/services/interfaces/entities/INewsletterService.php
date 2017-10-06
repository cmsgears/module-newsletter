<?php
namespace cmsgears\newsletter\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface INewsletterService extends INameTypeService, ISlugTypeService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	public function switchGlobal( $model, $config = [] );

	public function switchActive( $model, $config = [] );

	// Delete -------------

}
