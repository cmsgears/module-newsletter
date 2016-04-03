<?php
namespace cmsgears\newsletter\admin;

// Yii Imports
use \Yii;

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\newsletter\admin\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-newsletter/admin/views' );
    }

	public function getSidebarHtml() {

		$path	= Yii::getAlias( "@cmsgears" ) . "/module-newsletter/admin/views/sidebar.php";

		return $path;
	}
}

?>