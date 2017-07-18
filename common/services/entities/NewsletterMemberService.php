<?php
namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
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

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$userTable		= CoreTables::TABLE_USER;

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
	            'user' => [
					'asc' => [ "`$userTable`.`firstName`" => SORT_ASC, "`$userTable`.`lastName`" => SORT_ASC ],
					'desc' => [ "`$userTable`.`firstName`" => SORT_DESC, "`$userTable`.`lastName`" => SORT_DESC ],
					'default' => SORT_DESC,
	                'label' => 'User'
	            ],
				'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => [ 'name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Name'
	            ],
	            'email' => [
	                'asc' => [ 'email' => SORT_ASC ],
	                'desc' => ['email' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Email'
	            ],
	            'active' => [
	                'asc' => [ 'active' => SORT_ASC ],
	                'desc' => ['active' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Active'
	            ],
	            'cdate' => [
	                'asc' => [ 'createdAt' => SORT_ASC ],
	                'desc' => ['createdAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Created At'
	            ],
	            'udate' => [
	                'asc' => [ 'modifiedAt' => SORT_ASC ],
	                'desc' => ['modifiedAt' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Updated At'
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Filter - Status
		$status	= Yii::$app->request->getQueryParam( 'status' );

		if( isset( $status ) ) {

			switch( $status ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'email' => "$modelTable.email" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'email' => "$modelTable.email",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
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

	public function switchActive( $model, $config = [] ) {

		$global			= $model->global ? false : true;
		$model->global	= $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
 	}

	protected function applyBulk( $model, $column, $action, $target ) {

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'active': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'block': {

						$model->active = false;

						$model->update();

						break;
					}
				}

				break;
			}
		}
	}

	// Delete -------------

	public static function deleteByEmail( $email ) {

		$member	= $this->getByEmail( $email );

		if( isset( $member ) ) {

			// Delete from mailing list
			Yii::$app->factory->get( 'newsletterListService' )->deleteByMemberId( $member->id );

			// Delete member
			NewsletterMember::deleteByEmail( $email );

			return true;
		}

		return false;
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
