<?php
namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\newsletter\common\models\base\NewsletterTables;
use cmsgears\newsletter\common\models\entities\NewsletterMember;

use cmsgears\newsletter\common\services\interfaces\entities\INewsletterMemberService;
use cmsgears\newsletter\common\services\interfaces\mappers\INewsletterListService;

/**
 * The class NewsletterMemberService is base class to perform database activities for NewsletterMember Entity.
 */
class NewsletterMemberService extends \cmsgears\core\common\services\base\EntityService implements INewsletterMemberService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\newsletter\common\models\entities\NewsletterMember';

	public static $modelTable	= NewsletterTables::TABLE_NEWSLETTER_MEMBER;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $newsletterListService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

    public function __construct( INewsletterListService $newsletterListService, $config = [] ) {

		$this->newsletterListService	= $newsletterListService;

        parent::__construct( $config );
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterMemberService ---------------

	// Data Provider ------

	public function getPage( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'email',
	            ]
	        ]
	    ]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

    // Read - Models ---

	public function getByEmail( $email ) {

		return NewsletterMember::findByEmail( $email );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

 	public function createByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			return $member;
		}

		return parent::createByParams( $params, $config );
 	}

	public function signUp( $signUpForm ) {

		$member	= $this->getByEmail( $signUpForm->email );

		// Create Newsletter Member
		if( !isset( $member ) ) {

			$member			= new NewsletterMember();

			$member->email 	= $signUpForm->email;
			$member->name 	= $signUpForm->name;
			$member->active = true;

			$member->save();
		}

		// Add to specific and selected mailing list
		if( isset( $signUpForm->newsletterId ) ) {

			$this->newsletterListService->createByParams( [ 'newsletterId' => $signUpForm->newsletterId, 'memberId' => $member->id, 'active' => true ] );
		}

		return $member;
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'email', 'name', 'active' ]
		]);
 	}

	public function updateByParams( $params = [], $config = [] ) {

		$member	= $this->getByEmail( $params[ 'email' ] );

		if( isset( $member ) ) {

			return parent::update( $member, [
				'attributes' => [ 'name', 'active' ]
			]);
		}

		return $this->createByParams( $params, $config );
	}

	// Delete -------------

	public static function deleteByEmail( $email ) {

		$member	= $this->getByEmail( $email );

		if( isset( $member ) ) {

			NewsletterMember::deleteByEmail( $email );
		}

		return true;
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NewsletterMemberService ---------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}
