<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\newsletter\common\models\base;

// CMG Imports
use cmsgears\core\common\models\base\DbTables;

class NewsletterTables extends DbTables {

	// Entities -------------

	const TABLE_NEWSLETTER			= 'cmg_newsletter';
	const TABLE_NEWSLETTER_MEMBER	= 'cmg_newsletter_member';

	// Resources ------------

	// Mappers --------------

	const TABLE_NEWSLETTER_LIST		= 'cmg_newsletter_list';
}
