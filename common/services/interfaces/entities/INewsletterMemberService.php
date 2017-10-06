<?php
namespace cmsgears\newsletter\common\services\interfaces\entities;

interface INewsletterMemberService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByEmail( $email );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	public function signUp( $signUpForm );

	// Update -------------

	public function switchActive( $model, $config = [] );

	// Delete -------------

}
