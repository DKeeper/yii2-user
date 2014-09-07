<?php
/**
 * @author Капенкин Дмитрий <dkapenkin@rambler.ru>
 * @date 07.09.14
 * @time 9:35
 * Created by JetBrains PhpStorm.
 */
namespace dkeeper\yii2\user\helpers;

trait ModuleTrait
{
    /** @var \dkeeper\yii2\user\Module */
    private $_module;

    protected function getModule()
    {
        if ($this->_module == null) {
            $this->_module = \Yii::$app->getModule('user');
        }

        return $this->_module;
    }
}
