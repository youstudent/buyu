<?php foreach ($data as $key => $value):?>
    <tr>
        <td  class="text-center"><?=\common\helps\players::getRoom($value['id'])?></td>
        <td  class="text-center"><?=\common\helps\players::getRoomNmu(\common\helps\players::getRoom($value['id']))?></td>
        <td  class="text-center"><?=\common\helps\players::getRoomRate(\common\helps\players::getRoom($value['id']))?></td>
        <td  class="text-center"><?=$value['name']?></td>
        <td  class="text-center"><?=$value['id']?></td>
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/get-gold', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=$value['gold']?>
            </a>
        </td>
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/get-diamond', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=$value['diamond']?>
            </a>
        </td>
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/get-fishgold', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=$value['fishGold']?>
            </a>
         
        </td>
        
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/rate', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=\common\helps\players::getPlayerRate($value['id'])?>%
            </a>
           </td>
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/warning', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=\common\helps\players::getwingvalue($value['id'],'gold')?>
            </a>
        </td>
        <td  class="text-center">
            <a href="<?php echo \yii\helpers\Url::to(['monitoring/warning', 'id' => $value['id']]) ?>"
               data-toggle="modal" data-target="#myModal" style="color: #00b3ee">
                <?=\common\helps\players::getwingvalue($value['id'],'fishgold')?>
            </a>
        </td>
        <td  class="text-center">
            <?php if (\common\helps\players::getwingvalue($value['id'],'gold')<$value['gold'] || \common\helps\players::getwingvalue($value['id'],'fishgold')<$value['fishGold'] ):?>
              <span style="color:red;">报警</span>
            <?php else:?>
                <span>正常</span>
            <?php endif;?>
        </td>
       
        <td  class="text-center">
            <a onclick="return openAgency(this,'是否将该账号掉线?')"
               href="<?php echo \yii\helpers\Url::to(['users/black']) ?>"
               class="btn btn-xs btn-danger">掉 线</a>
            <a onclick="return openAgency(this,'是否将该账号停封?')"
               href="<?php echo \yii\helpers\Url::to(['monitoring/stop','id'=>$value['id']]) ?>"
               class="btn btn-xs btn-primary">停 封</a>
            <a href="<?=\yii\helpers\Url::to(['monitoring/robot','id'=>$value['id']])?>"
               data-toggle="modal" data-target="#myModal"
               class="btn btn-xs btn-primary">机器人</a>
            
            
            <a href="<?=\yii\helpers\Url::to(['users/out-log',
                'Users'=>['select'=>'game_id']])?>" class="btn btn-xs btn-success">详情</a>
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

