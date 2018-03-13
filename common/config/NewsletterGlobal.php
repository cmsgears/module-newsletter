<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\config;

/**
 * NewsletterGlobal defines the global constants and variables available for newsletter and
 * dependent modules.
 *
 * @since 1.0.0
 */
class NewsletterGlobal {

	// Grouping by type ------------------------------------------------

	const TYPE_NEWSLETTER		= 'newsletter';

	// Templates -------------------------------------------------------

	// Config ----------------------------------------------------------

	// Roles -----------------------------------------------------------

	const ROLE_NEWSLETTER_ADMIN		= 'newsletter-admin';

	// Permissions -----------------------------------------------------

	// Newsletter
	const PERM_NEWSLETTER_ADMIN		= 'admin-newsletters';

	const PERM_NEWSLETTER_MANAGE	= 'manage-newsletters';
	const PERM_NEWSLETTER_AUTHOR	= 'newsletter-author';

	const PERM_NEWSLETTER_VIEW		= 'view-newsletters';
	const PERM_NEWSLETTER_ADD		= 'add-newsletter';
	const PERM_NEWSLETTER_UPDATE	= 'update-newsletter';
	const PERM_NEWSLETTER_DELETE	= 'delete-newsletter';
	const PERM_NEWSLETTER_APPROVE	= 'approve-newsletter';
	const PERM_NEWSLETTER_PRINT		= 'print-newsletter';
	const PERM_NEWSLETTER_IMPORT	= 'import-newsletters';
	const PERM_NEWSLETTER_EXPORT	= 'export-newsletters';

	// Errors ----------------------------------------------------------

	// Model Fields ----------------------------------------------------

	// Generic Messages
	const MESSAGE_NEWSLETTER_SIGNUP = 'newsletterSignupMessage';

	// Generic Fields
	const FIELD_NEWSLETTER			= 'newsletterField';

}
