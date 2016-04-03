<?php
namespace cmsgears\newsletter\frontend;

// Yii Imports
use \Yii;

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\newsletter\frontend\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-newsletter/frontend/views' );
    }
}

?>