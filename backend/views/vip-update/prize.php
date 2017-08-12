<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$i=1;

?>
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">修改VIP管理</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['vip-update/edit'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <div id="a1">
                <?php echo $form->field($model,'give_day',['inline'=>true])->checkboxList(\common\models\VipUpdate::$give,['style'=>'margin-left: 113px;'])?>
                <?php foreach ($data as $k=>$v):?>
                    <div class="form-group field-type-<?php  echo $k ?>" id=<?php echo $k?>>
                        <label class="col-lg-3 control-label" for="type-<?php  echo $k ?>"><?php echo \common\models\VipUpdate::$give[$k] ?></label>
                        <div class="col-lg-9">
                            <input type="text" id="type-<?php echo $k?>>" class="form-control" name="VipUpdate[day][<?php echo $k?>]" value="<?php echo $v?>" disabled="disabled">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
                <div id="a2">
                <?php echo $form->field($model,'give_upgrade',['inline'=>true])->checkboxList(\common\models\VipUpdate::$give_day,['style'=>'margin-left: 113px;'])?>
                <?php foreach ($datas as $k=>$v):?>
                    <div class="form-group field-give_upgrade-<?php  echo $k ?>" id=<?php echo $k?>>
                        <label class="col-lg-3 control-label" for="give_upgrade-<?php  echo $k ?>"><?php echo \common\models\VipUpdate::$give_day[$k] ?></label>
                        <div class="col-lg-9">
                            <input type="text" id="give_upgrade-<?php echo $k?>>" class="form-control" name="VipUpdate[upgrade][<?php echo $k?>]" value="<?php echo $v?>" disabled="disabled">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
            <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i><?=Yii::t('app','but_close')?></button>
        </div>
    </div>
</div>
<style>
    .modal-body{
        width: 100%;
        display: inline-block;
        padding-bottom: 0px;
    }
</style>

