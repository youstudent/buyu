<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
class p
{
    public function run()
    {
        return ['name'=>'lrdouble','age'=>18];
    }
}

class index extends p{
    public function run()
    {
        return ['parent'=>parent::run(),'index'=>'index'];
    }
}
$index = new index();
var_dump( $index->run());