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
 * The newsletter migration inserts the database tables of newsletter module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m160621_130654_newsletter extends Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

    public function up() {

		// Newsletter
		$this->upNewsletter();
		$this->upNewsletterMember();
		$this->upNewsletterList();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
    }

	private function upNewsletter() {

        $this->createTable( $this->prefix . 'newsletter', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'global' => $this->boolean()->notNull()->defaultValue( false ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'lastSentAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy' );
	}

	private function upNewsletterMember() {

        $this->createTable( $this->prefix . 'newsletter_member', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member', 'userId' );
	}

	private function upNewsletterList() {

        $this->createTable( $this->prefix . 'newsletter_list', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 ),
			'memberId' => $this->bigInteger( 20 )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'lastSentAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list', 'memberId' );
	}

	private function generateForeignKeys() {

		// Newsletter
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Newsletter Member
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Newsletter List
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list', 'memberId', $this->prefix . 'newsletter_member', 'id', 'CASCADE' );
	}

    public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

        $this->dropTable( $this->prefix . 'newsletter' );
		$this->dropTable( $this->prefix . 'newsletter_member' );
		$this->dropTable( $this->prefix . 'newsletter_list' );
    }

	private function dropForeignKeys() {

		// Newsletter
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter' );

		// Newsletter Member
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member' );

		// Newsletter List
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list' );
	}
}
