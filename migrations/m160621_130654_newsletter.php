<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class m160621_130654_newsletter extends \yii\db\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix		= 'cmg_';

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
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( CoreGlobal::TEXT_MEDIUM )->notNull(),
			'description' => $this->string( CoreGlobal::TEXT_XLARGE )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'lastSentAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy' );
	}

	private function upNewsletterMember() {

        $this->createTable( $this->prefix . 'newsletter_member', [
			'id' => $this->bigPrimaryKey( 20 ),
			'name' => $this->string( CoreGlobal::TEXT_MEDIUM )->defaultValue( null ),
			'email' => $this->string( CoreGlobal::TEXT_XLARGE )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );
	}

	private function upNewsletterList() {

        $this->createTable( $this->prefix . 'newsletter_list', [
			'id' => $this->bigPrimaryKey( 20 ),
			'newsletterId' => $this->bigInteger( 20 ),
			'memberId' => $this->bigInteger( 20 )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
        ], $this->options );

        // Index for columns template, creator and modifier
        $this->createIndex( 'idx_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list', 'newsletterId' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list', 'memberId' );
	}

	private function generateForeignKeys() {

		// Newsletter
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
        $this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Newsletter List
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list', 'newsletterId', $this->prefix . 'newsletter', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list', 'memberId', $this->prefix . 'core_user', 'id', 'CASCADE' );
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
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_template', $this->prefix . 'newsletter' );
        $this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_creator', $this->prefix . 'newsletter' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_modifier', $this->prefix . 'newsletter' );

		// Newsletter List
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_parent', $this->prefix . 'newsletter_list' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'newsletter_list_member', $this->prefix . 'newsletter_list' );
	}
}

?>