<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/2/25
 * Time: 下午2:24
 */

namespace api\modules\v4;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v4\controllers';
    public $defaultRoute = 'site';
     public function init()
    {
         parent::init();
    }
}