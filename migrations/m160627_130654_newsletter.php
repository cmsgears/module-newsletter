<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\models\base\Meta;

/**
 * The newsletter migration inserts the database tables of newsletter module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * @since 1.0.0
 */
class m160627_130654_newsletter extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

    public function up() {

		// Newsletter
		$this->upNewsletter();
		$this->upNewsletterMeta();

		// Members and Mailing List
		$this->upNewsletterEdition();
		$this->upNewsletterMember();
		$this->upNewsletterList();

		$this->upNewsletterTrigger();

		$this->upNewsletterLink();
		$this->upNewsletterLinkAnalytics();

		$this->upNewsletterEvent();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
    }

	private function upNewsletter() {

        $this->createTable( $this->prefix . 'newsletter', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'multiple' => $this->boolean()->notNull()->defaultValue( false ),
			'global' => $this->boolean()->notNull()->defaultValue( false ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'triggered' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'publishedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_user', $this->prefix . 'newsletter', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_banner', $this->prefix . 'newsletter', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy' );
	}

	private function upNewsletterMeta() {

		$this->createTable( $this->prefix . 'newsletter_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for column parent
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_meta_parent', $this->prefix . 'newsletter_meta', 'modelId' );
	}

	private function upNewsletterEdition() {

        $this->createTable( $this->prefix . 'newsletter_edition', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
			'bannerId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'triggered' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'publishedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'edition_newsletter', $this->prefix . 'newsletter_edition', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'edition_banner', $this->prefix . 'newsletter_edition', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'edition_template', $this->prefix . 'newsletter_edition', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'edition_creator', $this->prefix . 'newsletter_edition', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'edition_modifier', $this->prefix . 'newsletter_edition', 'modifiedBy' );
	}

	private function upNewsletterMember() {

        $this->createTable( $this->prefix . 'newsletter_member', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'gid' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'name' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'mobile' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'bounced' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member', 'userId' );
	}

	private function upNewsletterList() {

        $this->createTable( $this->prefix . 'newsletter_list', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
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

	private function upNewsletterTrigger() {

        $this->createTable( $this->prefix . 'newsletter_trigger', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
			'editionId' => $this->bigInteger( 20 ),
			'memberId' => $this->bigInteger( 20 )->notNull(),
			'sent' => $this->boolean()->notNull()->defaultValue( false ),
			'delivered' => $this->boolean()->notNull()->defaultValue( false ),
			'mode' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'read' => $this->boolean()->notNull()->defaultValue( false ),
			'emailId' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'sentAt' => $this->dateTime(),
			'deliveredAt' => $this->dateTime(),
			'readAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_trigger_parent', $this->prefix . 'newsletter_trigger', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_trigger_edition', $this->prefix . 'newsletter_trigger', 'editionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_trigger_member', $this->prefix . 'newsletter_trigger', 'memberId' );
	}

	private function upNewsletterLink() {

        $this->createTable( $this->prefix . 'newsletter_link', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
			'editionId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'redirect' => $this->string( Yii::$app->core->xxxLargeText )->notNull(),
			'wrapBanner' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_link_parent', $this->prefix . 'newsletter_link', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_link_edition', $this->prefix . 'newsletter_link', 'editionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_link_creator', $this->prefix . 'newsletter_link', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_link_modifier', $this->prefix . 'newsletter_link', 'modifiedBy' );
	}

	private function upNewsletterLinkAnalytics() {

        $this->createTable( $this->prefix . 'newsletter_link_analytics', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
			'editionId' => $this->bigInteger( 20 ),
			'linkId' => $this->bigInteger( 20 )->notNull(),
			'memberId' => $this->bigInteger( 20 )->notNull(),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer( 11 )->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'visits' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_lanalytics_parent', $this->prefix . 'newsletter_link_analytics', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_lanalytics_edition', $this->prefix . 'newsletter_link_analytics', 'editionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_lanalytics_link', $this->prefix . 'newsletter_link_analytics', 'linkId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_lanalytics_member', $this->prefix . 'newsletter_link_analytics', 'memberId' );
	}

	private function upNewsletterEvent() {

        $this->createTable( $this->prefix . 'newsletter_event', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 )->notNull(),
			'editionId' => $this->bigInteger( 20 ),
			'memberId' => $this->bigInteger( 20 ),
			'triggerId' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer( 11 )->defaultValue( null ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'status' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'message' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'generatedAt' => $this->dateTime(),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_event_parent', $this->prefix . 'newsletter_event', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_event_edition', $this->prefix . 'newsletter_event', 'editionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_event_member', $this->prefix . 'newsletter_event', 'memberId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_event_trigger', $this->prefix . 'newsletter_event', 'triggerId' );
	}

	private function generateForeignKeys() {

		// Newsletter
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_user', $this->prefix . 'newsletter', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_banner', $this->prefix . 'newsletter', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Newsletter Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_meta_parent', $this->prefix . 'newsletter_meta', 'modelId', $this->prefix . 'newsletter', 'id', 'CASCADE' );

		// Newsletter Edition
		$this->addForeignKey( 'fk_' . $this->prefix . 'edition_newsletter', $this->prefix . 'newsletter_edition', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'edition_banner', $this->prefix . 'newsletter_edition', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'edition_template', $this->prefix . 'newsletter_edition', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'edition_creator', $this->prefix . 'newsletter_edition', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'edition_modifier', $this->prefix . 'newsletter_edition', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Newsletter Member
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_member_site', $this->prefix . 'newsletter_member', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Newsletter List
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list', 'memberId', $this->prefix . 'newsletter_member', 'id', 'CASCADE' );

		// Newsletter Trigger
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_parent', $this->prefix . 'newsletter_trigger', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_edition', $this->prefix . 'newsletter_trigger', 'editionId', $this->prefix . 'newsletter_edition', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_member', $this->prefix . 'newsletter_trigger', 'memberId', $this->prefix . 'newsletter_member', 'id', 'CASCADE' );

		// Newsletter Link
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_link_parent', $this->prefix . 'newsletter_link', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_link_edition', $this->prefix . 'newsletter_link', 'editionId', $this->prefix . 'newsletter_edition', 'id', 'CASCADE' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_link_creator', $this->prefix . 'newsletter_link', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_link_modifier', $this->prefix . 'newsletter_link', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Newsletter Link Analytics
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_parent', $this->prefix . 'newsletter_link_analytics', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_edition', $this->prefix . 'newsletter_link_analytics', 'editionId', $this->prefix . 'newsletter_edition', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_link', $this->prefix . 'newsletter_link_analytics', 'linkId', $this->prefix . 'newsletter_link', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_member', $this->prefix . 'newsletter_link_analytics', 'memberId', $this->prefix . 'newsletter_member', 'id', 'CASCADE' );

		// Newsletter Event
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_event_parent', $this->prefix . 'newsletter_event', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_event_edition', $this->prefix . 'newsletter_event', 'editionId', $this->prefix . 'newsletter_edition', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_event_member', $this->prefix . 'newsletter_event', 'memberId', $this->prefix . 'newsletter_member', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_event_trigger', $this->prefix . 'newsletter_event', 'triggerId', $this->prefix . 'newsletter_trigger', 'id', 'CASCADE' );
	}

    public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

        $this->dropTable( $this->prefix . 'newsletter' );
		$this->dropTable( $this->prefix . 'newsletter_meta' );

		$this->dropTable( $this->prefix . 'newsletter_edition' );
		$this->dropTable( $this->prefix . 'newsletter_member' );
		$this->dropTable( $this->prefix . 'newsletter_list' );

		$this->dropTable( $this->prefix . 'newsletter_trigger' );

		$this->dropTable( $this->prefix . 'newsletter_link' );
		$this->dropTable( $this->prefix . 'newsletter_link_analytics' );

		$this->dropTable( $this->prefix . 'newsletter_event' );
    }

	private function dropForeignKeys() {

		// Newsletter
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_site', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_user', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_banner', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter' );

		// Newsletter Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_meta_parent', $this->prefix . 'newsletter_meta' );

		// Newsletter Edition
		$this->dropForeignKey( 'fk_' . $this->prefix . 'edition_newsletter', $this->prefix . 'newsletter_edition' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'edition_banner', $this->prefix . 'newsletter_edition' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'edition_template', $this->prefix . 'newsletter_edition' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'edition_creator', $this->prefix . 'newsletter_edition' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'edition_modifier', $this->prefix . 'newsletter_edition' );

		// Newsletter Member
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_member_site', $this->prefix . 'newsletter_member' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_member_user', $this->prefix . 'newsletter_member' );

		// Newsletter List
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list' );

		// Newsletter Trigger
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_parent', $this->prefix . 'newsletter_trigger' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_edition', $this->prefix . 'newsletter_trigger' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_trigger_member', $this->prefix . 'newsletter_trigger' );

		// Newsletter Link
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_link_parent', $this->prefix . 'newsletter_link' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_link_edition', $this->prefix . 'newsletter_link' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_link_creator', $this->prefix . 'newsletter_link' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_link_modifier', $this->prefix . 'newsletter_link' );

		// Newsletter Link Analytics
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_parent', $this->prefix . 'newsletter_link_analytics' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_edition', $this->prefix . 'newsletter_link_analytics' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_link', $this->prefix . 'newsletter_link_analytics' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_lanalytics_member', $this->prefix . 'newsletter_link_analytics' );

		// Newsletter Event
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_event_parent', $this->prefix . 'newsletter_event', 'newsletterId' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_event_edition', $this->prefix . 'newsletter_event', 'editionId' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_event_member', $this->prefix . 'newsletter_event', 'memberId' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_event_trigger', $this->prefix . 'newsletter_event', 'triggerId' );
	}

}
