<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\models\resources\Stats;
use cmsgears\newsletter\common\models\base\NewsletterTables;

/**
 * The form stats migration insert the default row count for all the tables available in
 * form module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m160627_131962_newsletter_stats extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options = Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->insertTables();
	}

	private function insertTables() {

		$columns = [ 'tableName', 'type', 'count' ];

		$tableData = [
			[ $this->prefix . 'newsletter', 'rows', 0 ],
			[ $this->prefix . 'newsletter_meta', 'rows', 0 ],
			[ $this->prefix . 'newsletter_edition', 'rows', 0 ],
			[ $this->prefix . 'newsletter_member', 'rows', 0 ],
			[ $this->prefix . 'newsletter_list', 'rows', 0 ],
			[ $this->prefix . 'newsletter_trigger', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER ) );
		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_META ) );
		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_EDITION ) );
		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_MEMBER ) );
		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_LIST ) );
		Stats::deleteByTableName( NewsletterTables::getTableName( NewsletterTables::TABLE_NEWSLETTER_TRIGGER ) );
	}

}
