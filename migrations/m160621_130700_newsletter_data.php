<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\newsletter\common\config\NewsletterGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;

class m160621_130700_newsletter_data extends \yii\db\Migration {

	public $prefix;

	private $site;

	private $master;

	public function init() {

		$this->prefix		= 'cmg_';

		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( 'demomaster' );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

    }

    public function down() {

        echo "m160621_130700_newsletter_data will be deleted with m160621_014408_core.\n";

        return true;
    }
}

?>
