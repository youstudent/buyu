<?php foreach ($data as $key => $value):?>
    <tr>
     
        <td  class="text-center"><?=\common\helps\players::getRoom($value['id'])?></td>
        <td  class="text-center"><?=\common\helps\players::getRoomNmu(\common\helps\players::getRoom($value['id']))?></td>
        <td  class="text-center">20%</td>
        <td  class="text-center"><?=$value['name']?></td>
        <td  class="text-center"><?=$value['id']?></td>
        <td  class="text-center"><?=$value['gold']?></td>
        <td  class="text-center"><?=$value['fishGold']?></td>
        <td  class="text-center"><?=$value['diamond']?></td>
        <td  class="text-center">1</td>
        <td  class="text-center">1%</td>
        <td  class="text-center">2</td>
        <td  class="text-center">3</td>
        <td  class="text-center">正常</td>
        <td  class="text-center">
            <!--<a onclick="return openAgency(this,'是否将该账号掉线?')"
               href="<?php /*echo \yii\helpers\Url::to(['users/black']) */?>"
               class="btn btn-xs btn-danger">掉 线</a>
            <a onclick="return openAgency(this,'是否将该账号停封?')"
               href="<?php /*echo \yii\helpers\Url::to(['monitoring/stop','id'=>$value['id']]) */?>"
               class="btn btn-xs btn-primary">停 封</a>
            <a href="<?/*=\yii\helpers\Url::to(['monitoring/robot','room_id'=>$value->room_id,'game_id'=>$value->users->game_id])*/?>" class="btn btn-xs btn-primary">机器人</a>
            <a href="<?/*=\yii\helpers\Url::to(['users/out-log',
                'Users'=>['select'=>'game_id','keyword'=>$value->game_id]])*/?>" class="btn btn-xs btn-success">详情</a>-->
        </td>
    
    </tr>

<?php endforeach;?>

<footer class="panel-footer">
    <div class="row">
        <div class="col-sm-12 text-right text-center-xs">
            <?=\yii\widgets\LinkPager::widget([
                'pagination'=>$pages,
                'hideOnSinglePage' => false,
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页',
                'nextPageLabel' => '下一页',
                'prevPageLabel' => '上一页',
                'options'   =>[
                    'class'=>'pagination pagination-sm m-t-none m-b-none',
                ]
            ])?>
        </div>
    </div>
</footer>
