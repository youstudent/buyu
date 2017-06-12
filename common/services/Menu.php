<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
namespace common\services;

use Yii;
use yii\helpers\Url;

/**
 * 生成一个带二级菜单
 * Class Menu
 * @package common\services
 */
class Menu {

    /**
     * 生成一个带二级菜单
     * 调用方法
     *  ['label' => 'Home', 'icon'=>'fa fa-dashboard icon', 'url' => ['site/index'],'bg_color'=>'bg-danger'],
     *  ['label' => 'Products','icon'=>'fa fa-dashboard icon', 'url' => ['product/index'], 'items' => [
     *      ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
     *      ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
     *  ]],
     *  ['label' => 'Login','icon'=>'fa fa-dashboard icon', 'url' => ['site/login']],
     * @param array $config
     * @return string
     */
    public static function widget($config = [])
    {
        $route = Yii::$app->controller->route;
        $out = "<ul class='nav'>"; // 外层的Ul标签开始
        $li  = "";// 二级菜单
        $action = false;// 当前是否被选中
        foreach ($config as $key => $value)
        {
            if(isset($value['items']) && !empty($value['items'])) {
                $li  = "<ul class=\"nav lt\">";
                foreach ($value['items'] as $item)
                {
                    $icon = empty($item['icon'])? 'fa fa-angle-right' :$item['icon'];
                    if($item['url'][0] == $route) # 判断是否是选中状态
                    {
                        $li .= "<li class='active'> <a href='".Url::to($item['url'])."' > <i class='".$icon."'></i> <span>".$item['label']."</span> </a> </li>";
                        $action = true;
                    }
                    else

                        $li .= "<li> <a href='".Url::to($item['url'])."' > <i class='".$icon."'></i> <span>".$item['label']."</span> </a> </li>";
                }
                $li.="</ul>";
            }

            if($value['url'][0] == $route) # 判断是否是选中状态
                $action = true;
            $icon = empty($value['icon']) ? '' :$value['icon'];
            $bg_color = empty($value['bg_color']) ? "" : $value['bg_color'];
            $actionClass = $action ? 'active' : '';
            $action = false;
            if(empty($li))
                $out .= "<li class='$actionClass'> <a href='".Url::to($value['url'])."' > <i class='".$icon."'> <b class='$bg_color'></b> </i> <span>".$value['label']."</span> </a> </li>";
            else
            {
                $out .= "<li class='$actionClass' > <a href='#".Url::to($value['url'])."' > <i class='".$icon."'> <b class='$bg_color'></b> </i>  <span class=\"pull-right\"> <i class=\"fa fa-angle-down text\"></i><i class=\"fa fa-angle-up text-active\"></i></span><span>".$value['label']."</span> </a>";
                $out .=$li;
                $out .= " </li>";
                $li = null;
            }
        }
        $out .= "</ul>";
        return $out;
    }
}