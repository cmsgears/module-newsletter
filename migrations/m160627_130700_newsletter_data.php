<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The newsletter data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m160627_130700_newsletter_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create RBAC and Site Members
		$this->insertRolePermission();

		// Create newsletter permission groups and CRUD permissions
		$this->insertNewsletterPermissions();

		$this->insertNotificationTemplates();
    }

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Newsletter Admin', 'newsletter-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Newsletter Admin is limited to manage newsletters from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$nlAdminRole		= Role::findBySlugType( 'newsletter-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin Newsletters', 'admin-newsletters', CoreGlobal::TYPE_SYSTEM, null, 'The permission admin newsletters is to manage newsletters from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$nlAdminPerm	= Permission::findBySlugType( 'admin-newsletters', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $nlAdminPerm->id ],
			[ $adminRole->id, $nlAdminPerm->id ],
			[ $nlAdminRole->id, $adminPerm->id ], [ $nlAdminRole->id, $userPerm->id ], [ $nlAdminRole->id, $nlAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertNewsletterPermissions() {

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Permission Groups - Default - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'Manage Newsletters', 'manage-newsletters', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission manage newsletters allows user to manage newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Newsletter Author', 'newsletter-author', CoreGlobal::TYPE_SYSTEM, NULL, true, 'The permission newsletter author allows user to perform crud operations of newsletter belonging to respective author from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],

			// Newsletter Permissions - Hard Coded - Website - Individual, Organization
			[ $this->master->id, $this->master->id, 'View Newsletters', 'view-newsletters', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission view newsletters allows users to view their newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Add Newsletter', 'add-newsletter', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission add newsletter allows users to create newsletter from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Update Newsletter', 'update-newsletter', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission update newsletter allows users to update newsletter from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Delete Newsletter', 'delete-newsletter', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission delete newsletter allows users to delete newsletter from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Approve Newsletter', 'approve-newsletter', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission approve newsletter allows user to approve, freeze or block newsletter from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Print Newsletter', 'print-newsletter', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission print newsletter allows user to print newsletter from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Import Newsletters', 'import-newsletters', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission import newsletters allows user to import newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Export Newsletters', 'export-newsletters', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission export newsletters allows user to export newsletters from website.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Permission Groups
		$nlManagerPerm	= Permission::findBySlugType( 'manage-newsletters', CoreGlobal::TYPE_SYSTEM );
		$nlAuthorPerm	= Permission::findBySlugType( 'newsletter-author', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$vNewslettersPerm	= Permission::findBySlugType( 'view-newsletters', CoreGlobal::TYPE_SYSTEM );
		$aNewsletterPerm	= Permission::findBySlugType( 'add-newsletter', CoreGlobal::TYPE_SYSTEM );
		$uNewsletterPerm	= Permission::findBySlugType( 'update-newsletter', CoreGlobal::TYPE_SYSTEM );
		$dNewsletterPerm	= Permission::findBySlugType( 'delete-newsletter', CoreGlobal::TYPE_SYSTEM );
		$apNewsletterPerm	= Permission::findBySlugType( 'approve-newsletter', CoreGlobal::TYPE_SYSTEM );
		$pNewsletterPerm	= Permission::findBySlugType( 'print-newsletter', CoreGlobal::TYPE_SYSTEM );
		$iNewslettersPerm	= Permission::findBySlugType( 'import-newsletters', CoreGlobal::TYPE_SYSTEM );
		$eNewslettersPerm	= Permission::findBySlugType( 'export-newsletters', CoreGlobal::TYPE_SYSTEM );

		//Hierarchy

		$columns = [ 'parentId', 'childId', 'rootId', 'parentType', 'lValue', 'rValue' ];

		$hierarchy = [
			// Newsletter Manager - Organization, Approver
			[ null, null, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 18 ],
			[ $nlManagerPerm->id, $vNewslettersPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $nlManagerPerm->id, $aNewsletterPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $nlManagerPerm->id, $uNewsletterPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $nlManagerPerm->id, $dNewsletterPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $nlManagerPerm->id, $apNewsletterPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $nlManagerPerm->id, $pNewsletterPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $nlManagerPerm->id, $iNewslettersPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ],
			[ $nlManagerPerm->id, $eNewslettersPerm->id, $nlManagerPerm->id, CoreGlobal::TYPE_PERMISSION, 16, 17 ],

			// Newsletter Author- Individual
			[ null, null, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 1, 16 ],
			[ $nlAuthorPerm->id, $vNewslettersPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 2, 3 ],
			[ $nlAuthorPerm->id, $aNewsletterPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 4, 5 ],
			[ $nlAuthorPerm->id, $uNewsletterPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 6, 7 ],
			[ $nlAuthorPerm->id, $dNewsletterPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 8, 9 ],
			[ $nlAuthorPerm->id, $pNewsletterPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 10, 11 ],
			[ $nlAuthorPerm->id, $iNewslettersPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 12, 13 ],
			[ $nlAuthorPerm->id, $eNewslettersPerm->id, $nlAuthorPerm->id, CoreGlobal::TYPE_PERMISSION, 14, 15 ]
		];

		$this->batchInsert( $this->prefix . 'core_model_hierarchy', $columns, $hierarchy );
	}

	private function insertNotificationTemplates() {

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'description', 'active', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'createdAt', 'modifiedAt', 'message', 'content', 'data' ];

		$templates = [
			[ $this->master->id, $this->master->id, 'Newsletter Signup', NewsletterGlobal::TPL_NOTIFY_NEWSLETTER_SIGNUP, null, 'notification', 'Trigger notification to Site Admin when new member sign up for newsletters.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'Newsletter Signup - <b>{{model.name}}</b>', 'A new member (<b>{{model.name}}</b>, <b>{{model.email}}</b>) signed up for newsletters.', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"0","userEmail":"0","directEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

    public function down() {

        echo "m160627_130700_newsletter_data will be deleted with m160627_014408_core.\n";

        return true;
    }

}
