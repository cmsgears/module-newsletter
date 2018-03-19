<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

/**
 * The newsletter index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m160621_130865_newsletter_index extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Newsletter
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_name', $this->prefix . 'newsletter', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_slug', $this->prefix . 'newsletter', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_type', $this->prefix . 'newsletter', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'newsletter_icon', $this->prefix . 'newsletter', 'icon' );

		// Newsletter Meta
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_name', $this->prefix . 'newsletter_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_type', $this->prefix . 'newsletter_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_label', $this->prefix . 'newsletter_meta', 'label' );
		//$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_vtype', $this->prefix . 'newsletter_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_mit', $this->prefix . 'newsletter_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_mitn', $this->prefix . 'newsletter_meta', [ 'modelId', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'newsletter_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'newsletter_meta_search' . '(name ASC, value ASC)' );

		// Newsletter Member
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_member_name', $this->prefix . 'newsletter_member', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_member_email', $this->prefix . 'newsletter_member', 'email' );
	}

	public function down() {

		$this->downPrimary();
	}

	private function downPrimary() {

		// Newsletter
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_name', $this->prefix . 'newsletter' );
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_slug', $this->prefix . 'newsletter' );
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_type', $this->prefix . 'newsletter' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_icon', $this->prefix . 'newsletter' );

		// Newsletter Meta
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_name', $this->prefix . 'newsletter_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_type', $this->prefix . 'newsletter_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_label', $this->prefix . 'newsletter_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_vtype', $this->prefix . 'newsletter_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_mit', $this->prefix . 'newsletter_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_mitn', $this->prefix . 'newsletter_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_meta_search', $this->prefix . 'newsletter_meta' );

		// Newsletter Member
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_member_name', $this->prefix . 'newsletter_member' );
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_member_email', $this->prefix . 'newsletter_member' );
	}

}
