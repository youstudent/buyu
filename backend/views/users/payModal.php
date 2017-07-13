<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */?>
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?=Yii::t('app','user_pay_modal_title')?></h4>
        </div>
        <div class="modal-body">
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['users/pay'],
                    'fieldConfig' => [
                        'template' => "{label}{input}",
                    ],
                ])?>
                <input type="hidden" name="id" value="<?=$model->id?>">
                <?php echo $form->field($model,'game_id')->textInput(['readonly'=>true])?>
                <?php echo $form->field($model,'nickname')->textInput(['readonly'=>true])?>

<!--                升级版本的多货币改动-->
                <?php foreach ($model->goldArr as $key=>$value):?>
                    <?php $pay_config_gold[$key] = $key?>
                    <div class="form-group field-users-gold">
                        <label class="control-label" for="users-gold"><?=$key?></label>
                        <input type="text" id="users-gold" class="form-control" name="" value="<?=$value?>" readonly="">
                    </div>
                <?php endforeach;?>
                <?php echo $form->field($model,'pay_gold_config')->dropDownList($pay_config_gold)?>
<!--                升级版本的多货币改动-->

                <?php echo $form->field($model,'pay_gold_num')->textInput([])?>
                <?php echo $form->field($model,'pay_money')->textInput([])?>
               
            <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('app','but_close')?></button>
            <button type="button" id="payModalSubmit" class="btn btn-primary"><?=Yii::t('app','but_submit_pay')?></button>
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
<script>
    $(document).ready(function () {
        //平台用户充值
        $("#payModalSubmit").click(function () {
            var  form   = $("#payModalForm");
            var  action = form.attr('action');
            var  data   = form.serialize();
            $.ajax({
                url:action,
                type:'POST',
                data:data,
                success:function (res) {
                    console.log(res);
                    if(res.code == 1)
                    {
                        swal({
                            title:res.message,
                            //text: "<?=Yii::t('app','swal_text_error')?>",
                            type: "success",
                            confirmButtonText: "<?=Yii::t('app','but_close')?>",
                            closeOnConfirm: false,
                        },
                        function(){
                            location.reload();
                        });
                    }else{
                        swal({
                            title:res.message,
                            //text: "<?=Yii::t('app','swal_text_error')?>",
                            type: "error",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "<?=Yii::t('app','but_close_ret')?>",
                            closeOnConfirm: false,
                        })
                    }
                },
            });
        });
    })
</script>
