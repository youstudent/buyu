<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace backend\models;

use common\models\RebateConfObject;

class RebateConf extends RebateConfObject{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['one', 'two', 'three','sum'], 'required'],
            [['one', 'two', 'three','sum'], 'integer'],
            [['one','two','three','sum'],'match','pattern'=>'/^\+?[1-9][0-9]*$/'],
            ['one','validateSum'],
        ];
    }

    public function validateSum($attribute ,$params)
    {
        if(!$this->hasErrors())
        {
            $sum = $this->one + $this->two + $this->three;
            if($sum > 100)
                $this->addError($attribute,'三级分佣比例之和为100');
        }
    }
}