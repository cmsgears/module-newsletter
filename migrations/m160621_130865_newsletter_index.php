<?php

class m160621_130865_newsletter_index extends \yii\db\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix	= 'cmg_';
	}

	public function up() {

		$this->upPrimary();
	}

	private function upPrimary() {

		// Newsletter
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_name', $this->prefix . 'newsletter', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_slug', $this->prefix . 'newsletter', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_type', $this->prefix . 'newsletter', 'type' );
		$this->createIndex( 'idx_' . $this->prefix . 'newsletter_icon', $this->prefix . 'newsletter', 'icon' );

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
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_icon', $this->prefix . 'newsletter' );

		// Newsletter Member
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_member_name', $this->prefix . 'newsletter_member' );
		$this->dropIndex( 'idx_' . $this->prefix . 'newsletter_member_email', $this->prefix . 'newsletter_member' );
	}
}