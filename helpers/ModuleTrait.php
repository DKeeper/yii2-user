<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 07.09.14
 * @time 9:35
 * Created by JetBrains PhpStorm.
 */

/**
 * @property \dkeeper\yii2\user\Module $module
 */
namespace dkeeper\yii2\user\helpers;

trait ModuleTrait
{
    private $_module;

    /**
     * @return null|\dkeeper\yii2\user\Module
     */
    public function getModule()
    {
        if ($this->_module == null) {
            $this->_module = \Yii::$app->getModule('user');
        }

        return $this->_module;
    }
}
