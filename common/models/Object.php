<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace  common\models;

use yii\db\ActiveRecord;

/**
 ** @property string $pages
 *
 * @property Object $agency
 */
class Object extends  ActiveRecord
{
    /*
     * 分页组件
     * */
    public $pages = '';

}