<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\services\entities;

// Yii Imports
use Yii;
use yii\base\Exception;
use yii\data\Sort;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\newsletter\common\services\interfaces\entities\INewsletterService;

use cmsgears\core\common\services\traits\base\ApprovalTrait;
use cmsgears\core\common\services\traits\base\MultisiteTrait;
use cmsgears\core\common\services\traits\base\NameTypeTrait;
use cmsgears\core\common\services\traits\base\SlugTypeTrait;
use cmsgears\core\common\services\traits\cache\GridCacheTrait;
use cmsgears\core\common\services\traits\resources\DataTrait;

use cmsgears\core\common\utilities\DateUtil;

/**
 * NewsletterService provide service methods of newsletter model.
 *
 * @since 1.0.0
 */
class NewsletterService extends \cmsgears\core\common\services\base\EntityService implements INewsletterService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\newsletter\common\models\entities\Newsletter';

	public static $typed = true;

	public static $parentType = NewsletterGlobal::TYPE_NEWSLETTER;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;
	use GridCacheTrait;
	use MultisiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	use ApprovalTrait {

		activate as baseActivate;
	}

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// NewsletterService ---------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$templateTable = Yii::$app->factory->get( 'templateService' )->getModelTable();

		// Sorting ----------

	    $sort = new Sort([
	        'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
	            'template' => [
	                'asc' => [ "$templateTable.name" => SORT_ASC ],
	                'desc' => [ "$templateTable.name" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Template'
	            ],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ "$modelTable.slug" => SORT_ASC ],
					'desc' => [ "$modelTable.slug" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
	            'icon' => [
	                'asc' => [ "$modelTable.icon" => SORT_ASC ],
	                'desc' => [ "$modelTable.icon" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Icon'
	            ],
				'title' => [
					'asc' => [ "$modelTable.title" => SORT_ASC ],
					'desc' => [ "$modelTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title'
				],
	            'multiple' => [
	                'asc' => [ "$modelTable.multiple" => SORT_ASC ],
	                'desc' => [ "$modelTable.multiple" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Multiple'
	            ],
	            'global' => [
	                'asc' => [ "$modelTable.global" => SORT_ASC ],
	                'desc' => [ "$modelTable.global" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Global'
	            ],
	            'status' => [
	                'asc' => [ "$modelTable.status" => SORT_ASC ],
	                'desc' => [ "$modelTable.status" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Status'
	            ],
				'triggered' => [
	                'asc' => [ "$modelTable.triggered" => SORT_ASC ],
	                'desc' => [ "$modelTable.triggered" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Triggered'
	            ],
				'cdate' => [
					'asc' => [ "$modelTable.createdAt" => SORT_ASC ],
					'desc' => [ "$modelTable.createdAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Created At'
				],
				'udate' => [
					'asc' => [ "$modelTable.modifiedAt" => SORT_ASC ],
					'desc' => [ "$modelTable.modifiedAt" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Updated At'
				],
	            'pdate' => [
	                'asc' => [ "$modelTable.publishedAt" => SORT_ASC ],
	                'desc' => [ "$modelTable.publishedAt" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Published At'
	            ]
	        ],
			'defaultOrder' => $defaultSort
	    ]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$status	= Yii::$app->request->getQueryParam( 'status' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Status
		if( isset( $status ) && isset( $modelClass::$urlRevStatusMap[ $status ] ) ) {

			$config[ 'conditions' ][ "$modelTable.status" ]	= $modelClass::$urlRevStatusMap[ $status ];
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'multiple': {

					$config[ 'conditions' ][ "$modelTable.multiple" ] = true;

					break;
				}
				case 'global': {

					$config[ 'conditions' ][ "$modelTable.global" ]	= true;

					break;
				}
				case 'triggered': {

					$config[ 'conditions' ][ "$modelTable.triggered" ]	= true;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'content' => "$modelTable.content"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'type' => "$modelTable.type",
			'title' => "$modelTable.title",
			'desc' => "$modelTable.description",
			'multiple' => "$modelTable.multiple",
			'global' => "$modelTable.global",
			'status' => "$modelTable.status",
			'triggered' => "$modelTable.triggered",
			'content' => "$modelTable.content",
			'cdate' => "$modelTable.createdAt",
			'udate' => "$modelTable.modifiedAt",
			'pdate' => "$modelTable.publishedAt"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		// Copy Template
		$config[ 'template' ] = $model->template;

		$this->copyTemplate( $model, $config );

		return parent::create( $model, $config );
 	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$admin = isset( $config[ 'admin' ] ) ? $config[ 'admin' ] : false;

		$attributes	= isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'templateId', 'name', 'slug', 'title', 'icon', 'description', 'content'
		];

		if( $admin ) {

			$attributes	= ArrayHelper::merge( $attributes, [
				'multiple', 'global', 'status', 'triggered'
			]);
		}

		// Copy Template
		$config[ 'template' ] = $model->template;

		if( $this->copyTemplate( $model, $config ) ) {

			$attributes[] = 'data';
		}

		// Model Checks
		$oldStatus = $model->getOldAttribute( 'status' );

		$model = parent::update( $model, [
			'attributes' => $attributes
		]);

		// Check status change and notify User
		if( isset( $model->userId ) && $oldStatus != $model->status ) {

			$config[ 'users' ] = [ $model->userId ];

			$config[ 'data' ][ 'message' ] = 'Newsletter status changed.';

			$this->checkStatusChange( $model, $oldStatus, $config );
		}

		return $model;
	}

	public function switchGlobal( $model, $config = [] ) {

		$global	= $model->global ? false : true;

		$model->global = $global;

		return parent::updateSelective( $model, [
			'attributes' => [ 'global' ]
		]);
 	}

	public function activate( $model, $config = [] ) {

		if( empty( $model->publishedAt ) ) {

			$model->publishedAt	= DateUtil::getDateTime();
		}

		return $this->baseActivate( $model, $config );
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		$config[ 'hard' ] = $config[ 'hard' ] ?? !Yii::$app->core->isSoftDelete();

		if( $config[ 'hard' ] ) {

			$transaction = Yii::$app->db->beginTransaction();

			try {

				// Delete Model Files
				$this->fileService->deleteFiles( $model->files );

				// TODO: Delete Editions

				// TODO: Delete Mailing List

				// TODO: Delete Triggers

				// Commit
				$transaction->commit();
			}
			catch( Exception $e ) {

				$transaction->rollBack();

				throw new Exception( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY ) );
			}
		}

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		$direct = isset( $config[ 'direct' ] ) ? $config[ 'direct' ] : false; // Trigger direct notifications
		$users	= isset( $config[ 'users' ] ) ? $config[ 'users' ] : ( isset( $model->userId ) ? [ $model->userId ] : [] ); // Trigger user notifications

		switch( $column ) {

			case 'status': {

				switch( $action ) {

					case 'approve': {

						$this->approve( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'reject': {

						$this->reject( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'activate': {

						$this->activate( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'freeze': {

						$this->freeze( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'block': {

						$this->block( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
					case 'terminate': {

						$this->terminate( $model, [ 'direct' => $direct, 'users' => $users ] );

						break;
					}
				}

				break;
			}
			case 'model': {

				switch( $action ) {

					case 'multiple': {

						$model->multiple = true;

						$model->update();

						break;
					}
					case 'single': {

						$model->multiple = false;

						$model->update();

						break;
					}
					case 'global': {

						$model->global = true;

						$model->update();

						break;
					}
					case 'specific': {

						$model->global = false;

						$model->update();

						break;
					}
					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// NewsletterService ---------------------

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
